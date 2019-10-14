<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Guest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $attendCeremony;

    /**
     * @ORM\Column(type="boolean")
     */
    private $attendMeal;

    /**
     * @ORM\Column(type="boolean")
     */
    private $attendBrunch;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $answeredAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $veggie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $specialDiet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="guests")
     */
    private $user;

    public function __toString()
    {
        return $this->name;
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

    public function getAttendCeremony(): ?bool
    {
        return $this->attendCeremony;
    }

    public function setAttendCeremony(bool $attendCeremony): self
    {
        $this->attendCeremony = $attendCeremony;

        return $this;
    }

    public function getAttendMeal(): ?bool
    {
        return $this->attendMeal;
    }

    public function setAttendMeal(bool $attendMeal): self
    {
        $this->attendMeal = $attendMeal;

        return $this;
    }

    public function getAttendBrunch(): ?bool
    {
        return $this->attendBrunch;
    }

    public function setAttendBrunch(bool $attendBrunch): self
    {
        $this->attendBrunch = $attendBrunch;

        return $this;
    }

    public function getAnsweredAt(): ?\DateTimeInterface
    {
        return $this->answeredAt;
    }

    public function setAnsweredAt(?\DateTimeInterface $answeredAt): self
    {
        $this->answeredAt = $answeredAt;

        return $this;
    }

    public function getVeggie(): ?bool
    {
        return $this->veggie;
    }

    public function setVeggie(bool $veggie): self
    {
        $this->veggie = $veggie;

        return $this;
    }

    public function getSpecialDiet(): ?string
    {
        return $this->specialDiet;
    }

    public function setSpecialDiet(?string $specialDiet): self
    {
        $this->specialDiet = $specialDiet;

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

    public function isAnswered(): ?bool
    {
        return $this->answeredAt != null;
    }

    public function setAnswered(?bool $answered): self
    {
        $this->answeredAt = new \DateTime('now');

        return $this;
    }
}