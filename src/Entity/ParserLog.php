<?php

namespace App\Entity;

use App\Repository\ParserLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParserLogRepository::class)
 */
class ParserLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $request_datetime;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $request_method;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $request_url;

    /**
     * @ORM\Column(type="integer")
     */
    private $response_http_code;

    /**
     * @ORM\Column(type="text")
     */
    private $response_body;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Parser::class, inversedBy="parserLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestDateTime(): ?\DateTimeInterface
    {
        return $this->request_datetime;
    }

    public function setRequestDateTime(\DateTimeInterface $request_datetime): self
    {
        $this->request_datetime = $request_datetime;

        return $this;
    }

    public function getRequestMethod(): ?string
    {
        return $this->request_method;
    }

    public function setRequestMethod(string $request_method): self
    {
        $this->request_method = $request_method;

        return $this;
    }

    public function getRequestUrl(): ?string
    {
        return $this->request_url;
    }

    public function setRequestUrl(string $request_url): self
    {
        $this->request_url = $request_url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponseHttpCode()
    {
        return $this->response_http_code;
    }

    /**
     * @param mixed $response_http_code
     * @return ParserLog
     */
    public function setResponseHttpCode($response_http_code)
    {
        $this->response_http_code = $response_http_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponseBody()
    {
        return $this->response_body;
    }

    /**
     * @param mixed $response_body
     * @return ParserLog
     */
    public function setResponseBody($response_body)
    {
        $this->response_body = $response_body;
        return $this;
    }

    /**
     * @return ?\DateTimeInterface
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param \DateTimeInterface $created_at
     * @return ParserLog
     */
    public function setCreatedAt(\DateTimeInterface $created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getParser(): ?Parser
    {
        return $this->parser;
    }

    public function setParser(?Parser $parser): self
    {
        $this->parser = $parser;

        return $this;
    }
}
