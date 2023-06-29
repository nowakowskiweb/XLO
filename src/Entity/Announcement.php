<?php

namespace App\Entity;

use App\Repository\AnnouncementsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: AnnouncementsRepository::class)]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 50)]
    private ?string $voivodeship = null;

    #[ORM\Column(length: 50)]
    private ?string $city = null;

    #[ORM\Column(length: 50)]
    private ?string $conditionType = null;

    #[ORM\ManyToOne(inversedBy: 'announcements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\JoinTable(name: 'announcements_categories')]
    #[ORM\JoinColumn(name: 'announcement_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'category_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Category::class)]
    private Collection $categories;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\JoinTable(name: 'announcements_images')]
    #[ORM\JoinColumn(name: 'announcement_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'image_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Image::class)]
    private Collection $images;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getVoivodeship(): ?string
    {
        return $this->voivodeship;
    }

    public function setVoivodeship(string $voivodeship): self
    {
        $this->voivodeship = $voivodeship;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getConditionType(): ?string
    {
        return $this->conditionType;
    }

    public function setConditionType(string $conditionType): self
    {
        $this->conditionType = $conditionType;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
