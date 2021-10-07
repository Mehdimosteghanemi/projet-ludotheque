<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(message = "E-mail incorrect.")
     * @Assert\NotBlank(message="Merci de saisir un email")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lastname;

    /**
     * @ORM\Column(type="smallint")
     */
    private $delivery_mode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address_road;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $address_number;

    /**
     * @ORM\Column(type="integer")
     */
    private $address_zip_code;

    /**
     * @ORM\Column(type="string", length=75)
     */
    private $address_city;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $address_detail;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Subscription::class, inversedBy="users")
     */
    private $subscription;

    /**
     * @ORM\OneToMany(targetEntity=ListOf::class, mappedBy="users", orphanRemoval=true)
     */
    private $listOfs;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="users", orphanRemoval=true)
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Advice::class, mappedBy="users", orphanRemoval=true)
     */
    private $advice;

    public function __construct()
    {
        $this->listOfs = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->advice = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
        $this->status = 0;
        $this->roles = ["ROLE_USER"];
        $this->delivery_mode = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDeliveryMode(): ?int
    {
        return $this->delivery_mode;
    }

    public function setDeliveryMode(int $delivery_mode): self
    {
        $this->delivery_mode = $delivery_mode;

        return $this;
    }

    public function getAddressRoad(): ?string
    {
        return $this->address_road;
    }

    public function setAddressRoad(string $address_road): self
    {
        $this->address_road = $address_road;

        return $this;
    }

    public function getAddressNumber(): ?int
    {
        return $this->address_number;
    }

    public function setAddressNumber(?int $address_number): self
    {
        $this->address_number = $address_number;

        return $this;
    }

    public function getAddressZipCode(): ?int
    {
        return $this->address_zip_code;
    }

    public function setAddressZipCode(int $address_zip_code): self
    {
        $this->address_zip_code = $address_zip_code;

        return $this;
    }

    public function getAddressCity(): ?string
    {
        return $this->address_city;
    }

    public function setAddressCity(string $address_city): self
    {
        $this->address_city = $address_city;

        return $this;
    }

    public function getAddressDetail(): ?string
    {
        return $this->address_detail;
    }

    public function setAddressDetail(?string $address_detail): self
    {
        $this->address_detail = $address_detail;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): self
    {
        $this->subscription = $subscription;

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
            $listOf->setUsers($this);
        }

        return $this;
    }

    public function removeListOf(ListOf $listOf): self
    {
        if ($this->listOfs->removeElement($listOf)) {
            // set the owning side to null (unless already changed)
            if ($listOf->getUsers() === $this) {
                $listOf->setUsers(null);
            }
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
            $order->setUsers($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUsers() === $this) {
                $order->setUsers(null);
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
            $advice->setUsers($this);
        }

        return $this;
    }

    public function removeAdvice(Advice $advice): self
    {
        if ($this->advice->removeElement($advice)) {
            // set the owning side to null (unless already changed)
            if ($advice->getUsers() === $this) {
                $advice->setUsers(null);
            }
        }

        return $this;
    }
}
