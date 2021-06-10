<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Heatmap::class, mappedBy="customer", )
     */
    private $heatmaps;

    public function __construct()
    {
        $this->heatmaps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Heatmap[]
     */
    public function getHeatmaps(): Collection
    {
        return $this->heatmaps;
    }

    public function addHeatmap(Heatmap $heatmap): self
    {
        if (!$this->heatmaps->contains($heatmap)) {
            $this->heatmaps[] = $heatmap;
            $heatmap->setCustomer($this);
        }

        return $this;
    }

    public function removeHeatmap(Heatmap $heatmap): self
    {
        if ($this->heatmaps->removeElement($heatmap)) {
            // set the owning side to null (unless already changed)
            if ($heatmap->getCustomer() === $this) {
                $heatmap->setCustomer(null);
            }
        }

        return $this;
    }
}
