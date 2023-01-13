<?php

namespace App\Entity;

use App\Repository\UserRestrictionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRestrictionRepository::class)
 */
class UserRestriction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ip;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ban;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function isBan(): ?bool
    {
        return $this->ban;
    }

    public function setBan(?bool $ban): self
    {
        $this->ban = $ban;

        return $this;
    }
}
