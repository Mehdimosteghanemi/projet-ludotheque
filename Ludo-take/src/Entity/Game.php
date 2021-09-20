<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $difficulty;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $players;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $time_of;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $stock;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $available;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="games")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=ListOf::class, mappedBy="games")
     */
    private $listOfs;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="games", orphanRemoval=true)
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Advice::class, mappedBy="games", orphanRemoval=true)
     */
    private $advice;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->listOfs = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->advice = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(string $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getPlayers(): ?string
    {
        return $this->players;
    }

    public function setPlayers(?string $players): self
    {
        $this->players = $players;

        return $this;
    }

    public function getTimeOf(): ?int
    {
        return $this->time_of;
    }

    public function setTimeOf(?int $time_of): self
    {
        $this->time_of = $time_of;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getAvailable(): ?int
    {
        return $this->available;
    }

    public function setAvailable(?int $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|ListOf[]
     */
    public function getListOfs(): Collection
    {
        return $this->listOfs;
    }

    public function addListOf(ListOf $listOf): self
    {
        if (!$this->listOfs->contains($listOf)) {
            $this->listOfs[] = $listOf;
            $listOf->addGame($this);
        }

        return $this;
    }

    public function removeListOf(ListOf $listOf): self
    {
        if ($this->listOfs->removeElement($listOf)) {
            $listOf->removeGame($this);
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setGames($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getGames() === $this) {
                $order->setGames(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Advice[]
     */
    public function getAdvice(): Collection
    {
        return $this->advice;
    }

    public function addAdvice(Advice $advice): self
    {
        if (!$this->advice->contains($advice)) {
            $this->advice[] = $advice;
            $advice->setGames($this);
        }

        return $this;
    }

    public function removeAdvice(Advice $advice): self
    {
        if ($this->advice->removeElement($advice)) {
            // set the owning side to null (unless already changed)
            if ($advice->getGames() === $this) {
                $advice->setGames(null);
            }
        }

        return $this;
    }
}
