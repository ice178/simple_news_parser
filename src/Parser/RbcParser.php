<?php

namespace App\Parser;

use App\Parser\Dto\News;
use App\Parser\Dto\ParserResult;
use SimpleXMLElement;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RbcParser implements ParserInterface
{
    private const HTTP_STATUS_OK = 200;

    private const NAME = 'RbcParser';

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function parse(): ParserResult
    {
        $httpResult = $this->httpClient->request(
            'GET',
            'http://static.feed.rbc.ru/rbc/logical/footer/news.rss',
        );

        $result = new ParserResult();

        $result->requestMethod    = 'GET';
        $result->requestUrl       = 'http://static.feed.rbc.ru/rbc/logical/footer/news.rss';
        $result->responseHttpCode = $httpResult->getStatusCode();
        $result->responseBody     = $httpResult->getContent();

        if (self::HTTP_STATUS_OK === $result->responseHttpCode) {
            $result->news = $this->parseNews($httpResult->getContent());
        } else {
            $result->news = [];
        }

        return $result;
    }

    public function parserTest()
    {
        $content = file_get_contents('/Users/art/www/news_parser_back/rbc.xml');

        $result = $this->parseNews($content);
    }

    /**
     * Parse XML content into list of news DTO
     *
     * @param string $response
     *
     * @return News[]
     *
     * @throws \Exception
     */
    private function parseNews(string $response): array
    {
        $result = [];
        $xml    = new SimpleXMLElement($response);

        if (isset($xml->channel->item)) {
            foreach ($xml->channel->item as $item) {
                $newsDto = new News();

                if (!isset($item->title) || !isset($item->link) || !isset($item->pubDate) || !isset($item->description)) {
                    continue;
                }

                $newsDto->newsTitle   = (string) $item->title;
                $newsDto->link        = (string) $item->link;
                $newsDto->description = (string) $item->description;

                $newsDto->publicationDateTime = \DateTime::createFromFormat(\DateTime::RSS, (string) $item->pubDate)->format('Y-m-d H:i:s');

                $newsDto->externalId = isset($item->guuid)
                    ? (string) $item->guid
                    : md5($newsDto->newsTitle.'|'.$newsDto->description);

                $newsDto->author = isset($item->author)
                    ? (string) $item->author
                    : '';

                $newsDto->imageLink = isset($item->enclosure)
                    ? (string) $item->enclosure->attributes()
                    : '';

                $result[] = $newsDto;
            }
        }

        return $result;
    }
}
