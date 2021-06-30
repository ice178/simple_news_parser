<?php

namespace App\Service;

use App\Entity\Parser;
use App\Entity\ParserLog;
use App\Entity\News;
use App\Parser\Dto\News as NewsDto;
use App\Parser\Dto\ParserResult;
use App\Parser\ParserInterface;
use App\Repository\NewsRepository;
use App\Repository\ParserRepository;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;

class ParserService
{
    private const PARSER_PREFIX = 'App\\Parser\\';

    private $parserRepository;
    private $newsRepository;
    private $entityManager;
    private $parsers;

    /**
     * ParserService constructor
     *
     * @param EntityManagerInterface $entityManager
     * @param ParserRepository       $parserRepository
     * @param NewsRepository         $newsRepository
     * @param array                  $parsers
     */
    public function __construct(EntityManagerInterface $entityManager,
                                ParserRepository $parserRepository,
                                NewsRepository $newsRepository,
                                array $parsers)
    {
        $this->entityManager    = $entityManager;
        $this->parserRepository = $parserRepository;
        $this->newsRepository   = $newsRepository;

        foreach ($parsers as $parser) {
            $this->parsers[get_class($parser)] = $parser;
        }
    }

    /**
     * @return ParserInterface[]
     */
    public function getActiveParsers(): array
    {
        $parsers = $this->parserRepository->findBy(['status' => Parser::STATUS_ACTIVE]);
        $result  = [];

        foreach ($parsers as $parser) {
            if (isset($this->parsers[self::PARSER_PREFIX.$parser->getClass()])) {
                $result[$parser->getId()] = $this->parsers[self::PARSER_PREFIX.$parser->getClass()];
            }
        }

        return $result;
    }

    /**
     * Save parser result (news and parser log)
     *
     * @param ParserResult $parserResult
     *
     * @throws ConnectionException
     */
    public function saveParserResult(int $parserId, ParserResult $parserResult)
    {
        $parser = $this->parserRepository->find($parserId);

        $parserLog = new ParserLog();
        $parserLog->setRequestDateTime(new \DateTime($parserResult->requestDateTime))
            ->setCreatedAt(new \DateTime('now'))
            ->setRequestMethod($parserResult->requestMethod)
            ->setRequestUrl($parserResult->requestUrl)
            ->setResponseBody($parserResult->responseBody)
            ->setResponseHttpCode($parserResult->responseHttpCode)
            ->setParser($parser);

        $newsItems = [];

        foreach ($parserResult->news as $item) {
            $newsItems[] = $this->convertNewsDtoToEntity($parser, $item);
        }

        try {
            $this->entityManager->beginTransaction();
            $this->entityManager->persist($parserLog);
            foreach ($newsItems as $newsItem) {
                $this->entityManager->persist($newsItem);
            }
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (ConnectionException $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    /**
     * Convert News DTO to Entity
     *
     * @param Parser  $parser
     * @param NewsDto $newsDto
     *
     * @return News
     *
     * @throws \Exception
     */
    private function convertNewsDtoToEntity(Parser $parser, NewsDto $newsDto): News
    {
        $newsEntity = null;
        $now        = new \DateTime();

        if (!empty($newsDto->externalId)) {
            $newsEntity = $this->newsRepository->findOneBy(['external_id' => $newsDto->externalId]);

            if ($newsEntity) {
                $newsEntity->setUpdatedAt($now);
            }
        }

        if (!$newsEntity) {
            $newsEntity = new News();
            $newsEntity->setCreatedAt($now);
            $newsEntity->setUpdatedAt($now);
        }

        $newsEntity
            ->setAuthor($newsDto->author)
            ->setDescription($newsDto->description)
            ->setTitle($newsDto->newsTitle)
            ->setPublishDatetime(new \DateTime($newsDto->publicationDateTime))
            ->setExternalId($newsDto->externalId)
            ->setImage($newsDto->imageLink)
            ->setParser($parser)
            ->setStatus(News::STATUS_ACTIVE)
            ->setLink($newsDto->link);

        return $newsEntity;
    }
}
