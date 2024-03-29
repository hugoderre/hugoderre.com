<?php

namespace App\Entity\PostType;

use App\Entity\Media;
use App\Entity\User;
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
    protected $title;

	/**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

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
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $metaDescription;

	/**
     * @ORM\ManyToOne(targetEntity=Media::class)
     * @ORM\JoinColumn(nullable=true)
     */
    protected $metaImage;

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

	/**
     * @ORM\Column(type="string", length=255)
     */
    protected $lang;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

	public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

	public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

	public function getMetaImage(): ?Media
    {
        return $this->metaImage;
    }

    public function setMetaImage(?Media $metaImage): self
    {
        $this->metaImage = $metaImage;

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

	public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

	public function __toString()
    {
        return $this->title;
    }
}
