<?php

namespace App\Entity;

use App\Repository\HeatmapRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=HeatmapRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Heatmap
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="heatmaps")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=LinkType::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $linkType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getLinkType(): ?LinkType
    {
        return $this->linkType;
    }

    public function setLinkType(?LinkType $linkType): self
    {
        $this->linkType = $linkType;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime("now");

        return $this;
    }
}
