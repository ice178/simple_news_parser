<?php

namespace App\Entity;

use App\Repository\ParserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParserRepository::class)
 */
class Parser
{
    public const STATUS_ACTIVE = 'active';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $class;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=ParserLog::class, mappedBy="parser")
     */
    private $parserLogs;

    /**
     * @ORM\OneToMany(targetEntity=News::class, mappedBy="parser")
     */
    private $news;

    public function __construct()
    {
        $this->parserLogs = new ArrayCollection();
        $this->news = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return Parser
     */
    public function setClass(string $class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return Collection|ParserLog[]
     */
    public function getParserLogs(): Collection
    {
        return $this->parserLogs;
    }

    public function addParserLog(ParserLog $parserLog): self
    {
        if (!$this->parserLogs->contains($parserLog)) {
            $this->parserLogs[] = $parserLog;
            $parserLog->setParser($this);
        }

        return $this;
    }

    public function removeParserLog(ParserLog $parserLog): self
    {
        if ($this->parserLogs->removeElement($parserLog)) {
            // set the owning side to null (unless already changed)
            if ($parserLog->getParser() === $this) {
                $parserLog->setParser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|News[]
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): self
    {
        if (!$this->news->contains($news)) {
            $this->news[] = $news;
            $news->setParser($this);
        }

        return $this;
    }

    public function removeNews(News $news): self
    {
        if ($this->news->removeElement($news)) {
            // set the owning side to null (unless already changed)
            if ($news->getParser() === $this) {
                $news->setParser(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Parser
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}
