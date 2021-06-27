<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UrlRepository::class)
 */
class Url
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
    private $link_type;

    /**
     * @ORM\OneToMany(targetEntity=AccessLog::class, mappedBy="url")
     */
    private $accessLogs;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $link;

    public function __construct()
    {
        $this->accessLogs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getLinkType(): string
    {
        return $this->link_type;
    }

    public function setLinkType(string $link_type): self
    {
        $this->link_type = $link_type;

        return $this;
    }

    /**
     * @return Collection|AccessLog[]
     */
    public function getAccessLogs(): Collection
    {
        return $this->accessLogs;
    }

    public function addAccessLog(AccessLog $accessLog): self
    {
        if (!$this->accessLogs->contains($accessLog)) {
            $this->accessLogs[] = $accessLog;
            $accessLog->setUrl($this);
        }

        return $this;
    }

    public function removeAccessLog(AccessLog $accessLog): self
    {
        if ($this->accessLogs->removeElement($accessLog)) {
            if ($accessLog->getUrl() === $this) {
                $accessLog->setUrl(null);
            }
        }

        return $this;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link): self
    {
        $this->link = $link;

        return $this;
    }
}
