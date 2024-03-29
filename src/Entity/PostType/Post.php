<?php

namespace App\Entity\PostType;

use App\Entity\Comment;
use App\Entity\Tag;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post extends AbstractPostType
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $excerpt;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="posts")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=self::class)
     * @JoinTable(name="post_related",
     *     joinColumns={@JoinColumn(name="post_a_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="post_b_id", referencedColumnName="id")}
     * )
     */
    private $relatedPosts;

    /**
     * @ORM\ManyToMany(targetEntity=self::class, cascade={"persist"})
     * @JoinTable(name="post_translations",
     *     joinColumns={@JoinColumn(name="post_a_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="post_b_id", referencedColumnName="id")}
     * )
     */
	private $translatedPosts;

    const STATUS_PUBLISH = 'publish';
    const STATUS_DRAFT = 'draft';
    const STATUS_TRASH = 'trash';

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->relatedPosts = new ArrayCollection();
        $this->translatedPosts = new ArrayCollection();
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * @return Collection|Tag[]
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

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getRelatedPosts(): Collection
    {
        return $this->relatedPosts;
    }

    public function addRelatedPost(self $relatedPost): self
    {
        if (!$this->relatedPosts->contains($relatedPost)) {
            $this->relatedPosts[] = $relatedPost;
        }

        return $this;
    }

    public function removeRelatedPost(self $relatedPost): self
    {
        $this->relatedPosts->removeElement($relatedPost);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTranslatedPosts(): Collection
    {
        return $this->translatedPosts;
    }

    public function addTranslatedPost(self $translatedPost): self
    {
        if (!$this->translatedPosts->contains($translatedPost)) {
            $this->translatedPosts[] = $translatedPost;
        }

        return $this;
    }

    public function removeTranslatedPost(self $translatedPost): self
    {
        $this->translatedPosts->removeElement($translatedPost);

        return $this;
    }
}
