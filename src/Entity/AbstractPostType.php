<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractPostType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Media::class)
     * @ORM\JoinColumn(nullable=true)
     */
    protected $thumbnail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $status;

	const STATUS_PUBLISH = 'publish';
    const STATUS_DRAFT = 'draft';
    const STATUS_TRASH = 'trash';

	/**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    protected $author;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected $publishedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getThumbnail(): ?Media
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?Media $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public static function getStatusList(): array
    {
        return [
            self::STATUS_PUBLISH => 'Publié',
            self::STATUS_DRAFT => 'Brouillon',
            self::STATUS_TRASH => 'Corbeille',
        ];
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
