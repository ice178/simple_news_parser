<?php

namespace App\Command;

use App\Parser\ParserInterface;
use App\Parser\RbcParser;
use App\Service\ParserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NewsParserCommand extends Command
{
    private const ARG_INTERVAL   = 'interval';
    private const ARG_CYCLES_NUM = 'cycles-num';

    private const DEFAULT_INTERVAL   = 10;
    private const DEFAULT_NUM_CYCLES = 2;

    protected static $defaultName = 'NewsParser';
    protected static $defaultDescription = 'Command that starts parsers and saving results';

    private $parserService;
    private $logger;

    private $rbcParser;

    /**
     * NewsParserCommand constructor
     *
     * @param ParserService   $parserService
     * @param LoggerInterface $logger
     */
    public function __construct(ParserService $parserService, LoggerInterface $logger, RbcParser $rbcParser)
    {
        $this->parserService = $parserService;
        $this->logger        = $logger;

        $this->rbcParser = $rbcParser;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption(self::ARG_INTERVAL, null,InputArgument::OPTIONAL, 'Interval between parsing iteration in seconds', self::DEFAULT_INTERVAL)
            ->addOption(self::ARG_CYCLES_NUM, null, InputArgument::OPTIONAL, 'Number of cycles to execute, 0 for infinity num', self::DEFAULT_NUM_CYCLES)
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $interval  = (int) $input->getOption(self::ARG_INTERVAL);
        $cyclesNum = (int) $input->getOption(self::ARG_CYCLES_NUM);

        if ($interval <= 0 || $cyclesNum < 0) {
            $io->note('You must pass correct args! Interval and number of cycles must be integer more than zero!');
            return 1;
        }

        $parsers = $this->parserService->getActiveParsers();

        $this->logger->info('Got '.count($parsers).' for parse');

        if ($cyclesNum > 0) {
            $this->startLoops($interval, $cyclesNum, $parsers);
        } else {
            $this->startInfinityLoops($interval, $parsers);
        }

        return 0;
    }

    /**
     * Start loops
     *
     * @param int               $interval
     * @param int               $cyclesNum
     * @param ParserInterface[] $parsers
     *
     * @throws \Doctrine\DBAL\Exception\ConnectionException
     */
    private function startLoops(int $interval, int $cyclesNum, array $parsers): void
    {
        for ($i = 0; $i < $cyclesNum; $i++) {
            $this->tick($parsers);
            sleep($interval);
        }
    }

    /**
     * Start infinity loops
     *
     * @param int               $interval
     * @param ParserInterface[] $parsers
     */
    private function startInfinityLoops(int $interval, array $parsers): void
    {
        while (true) {
            $this->tick($parsers);
            sleep($interval);
        }
    }

    /**
     * Tick
     *
     * @param ParserInterface[] $parsers
     *
     * @throws \Doctrine\DBAL\Exception\ConnectionException
     */
    private function tick(array $parsers)
    {
        foreach ($parsers as $parserId => $parser) {
            $this->logger->info('Start parsing of '.$parser->getName().' parser');
            $parserResult = $parser->parse();
            $this->logger->info('End parsing of '.$parser->getName().' parser');
            $this->parserService->saveParserResult($parserId, $parserResult);
            $this->logger->info('End saving result of'.$parser->getName().' parser');
        }
    }
}
