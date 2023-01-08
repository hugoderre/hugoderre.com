<?php

namespace App\Entity\PostType;

use App\Entity\Media;
use App\Entity\Tag;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project extends AbstractPostType
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $githubUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $websiteUrl;

    /**
     * @ORM\ManyToMany(targetEntity=Media::class)
	 * @ORM\OrderBy({"fileName" = "ASC"})
     */
    private $gallery;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="projects")
     */
    private $tags;

    /**
     * @ORM\Column(type="integer")
     */
    private $listOrder;

	/**
     * @ORM\ManyToMany(targetEntity=self::class, cascade={"persist"})
     * @JoinTable(name="project_translations",
     *     joinColumns={@JoinColumn(name="project_a_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="project_b_id", referencedColumnName="id")}
     * )
     */
	private $translatedProjects;

    public function __construct()
    {
        $this->gallery = new ArrayCollection();
        $this->tags = new ArrayCollection();
		$this->translatedProjects = new ArrayCollection();
    }

    public function getGithubUrl(): ?string
    {
        return $this->githubUrl;
    }

    public function setGithubUrl(?string $githubUrl): self
    {
        $this->githubUrl = $githubUrl;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(?string $websiteUrl): self
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getGallery(): Collection
    {
        return $this->gallery;
    }

    public function addGallery(Media $gallery): self
    {
        if (!$this->gallery->contains($gallery)) {
            $this->gallery[] = $gallery;
        }

        return $this;
    }

    public function removeGallery(Media $gallery): self
    {
        $this->gallery->removeElement($gallery);

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

	public function getListOrder(): ?int
	{
		return $this->listOrder;
	}

	public function setListOrder(int $listOrder): self
	{
		$this->listOrder = $listOrder;

		return $this;
	}

	/**
     * @return Collection<int, self>
     */
    public function getTranslatedProjects(): Collection
    {
        return $this->translatedProjects;
    }

    public function addTranslatedProject(self $translatedProject): self
    {
        if (!$this->translatedProjects->contains($translatedProject)) {
            $this->translatedProjects[] = $translatedProject;
        }

        return $this;
    }

    public function removeTranslatedProject(self $translatedProject): self
    {
        $this->translatedProjects->removeElement($translatedProject);

        return $this;
    }
}
