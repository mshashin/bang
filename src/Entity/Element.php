<?php

namespace App\Entity;

use App\Repository\ElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ElementRepository::class)
 */
class Element
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
    private $caption;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alias;

    /**
     * @ORM\ManyToOne(targetEntity=TypeElement::class, inversedBy="elements")
     */
    private $typeElement;

    /**
     * @ORM\ManyToMany(targetEntity=Rule::class, inversedBy="elements")
     */
    private $rules;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $views;

    /**
     * @ORM\ManyToOne(targetEntity=Addition::class, inversedBy="elements")
     */
    private $addition;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $short;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getTypeElement(): ?TypeElement
    {
        return $this->typeElement;
    }

    public function getType(): ?TypeElement
    {
        return $this->typeElement;
    }

    public function setTypeElement(?TypeElement $typeElement): self
    {
        $this->typeElement = $typeElement;

        return $this;
    }

    /**
     * @return Collection<int, Rule>
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(Rule $rule): self
    {
        if (!$this->rules->contains($rule)) {
            $this->rules[] = $rule;
        }

        return $this;
    }

    public function removeRule(Rule $rule): self
    {
        $this->rules->removeElement($rule);

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getAddition(): ?Addition
    {
        return $this->addition;
    }

    public function setAddition(?Addition $addition): self
    {
        $this->addition = $addition;

        return $this;
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(?string $short): self
    {
        $this->short = $short;

        return $this;
    }
}
