<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity(fields={"email"}, message="Customer with that email already exists")
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=AccessLog::class, mappedBy="customer")
     */
    private $accessLogs;
    private $journeyHash = null;

    public function __construct()
    {
        $this->accessLogs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|AccessLog[]
     */
    public function getAccessLogs(): Collection
    {
        return $this->accessLogs;
    }

    /**
     * @param AccessLog $accessLog
     * @return $this
     */
    public function addAccessLog(AccessLog $accessLog): self
    {
        if (!$this->accessLogs->contains($accessLog)) {
            $this->accessLogs[] = $accessLog;
            $accessLog->setCustomer($this);
        }

        return $this;
    }

    /**
     * @param AccessLog $accessLog
     * @return $this
     */
    public function removeAccessLog(AccessLog $accessLog): self
    {
        if ($this->accessLogs->removeElement($accessLog)) {
            if ($accessLog->getCustomer() === $this) {
                $accessLog->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCustomerJourneyHash(): string
    {
        if ($this->journeyHash) {
            return $this->journeyHash;
        }

        $pairs = array();
        foreach ($this->accessLogs as $accessEntry) {
            /* @var $accessEntry AccessLog */
            if($accessEntry->getUrl()) {
                $url_name = $accessEntry->getUrl()->getId();
                $url_type = $accessEntry->getUrl()->getLinkType();
                $pairs[] = ["url_id" => $url_name, "url_type" => $url_type];
            }
        }
        $serializedJourney = serialize($pairs);

        $this->journeyHash = md5($serializedJourney);

        return $this->journeyHash;
    }
}
