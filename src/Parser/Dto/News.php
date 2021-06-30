<?php


namespace App\Parser\Dto;


class News
{
    public $externalId;

    public $newsTitle;

    public $link;

    public $imageLink;

    public $description;

    /**
     * Date time string in format
     *
     * @var string
     */
    public $publicationDateTime;

    public $author;
}
