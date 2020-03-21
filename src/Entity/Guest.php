<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $absent;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private $invitedForCeremonyOnly;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="guests", cascade={"persist"})
     */
    private $users;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->attendCeremony = false;
        $this->attendMeal = false;
        $this->attendBrunch = false;
        $this->veggie;
        $this->invitedForCeremonyOnly = true;
        $this->users = new ArrayCollection();
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

    public function isAbsent(): ?bool
    {
        return $this->absent;
    }

    public function setAbsent(?bool $absent): self
    {
        $this->absent = $absent;

        return $this;
    }

    public function isInvitedForCeremonyOnly(): ?bool
    {
        return $this->invitedForCeremonyOnly;
    }

    public function setInvitedForCeremonyOnly(?bool $invitedForCeremonyOnly): self
    {
        $this->invitedForCeremonyOnly = $invitedForCeremonyOnly;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function hasAnswered(): ?bool
    {
        return 
            ($this->attendCeremony === true) || 
            ($this->attendMeal === true) || 
            ($this->attendBrunch === true) || 
            ($this->absent === true);
    }

    public function setAnswered(?bool $answered): self
    {
        $this->answeredAt = new \DateTime('now');

        return $this;
    }
}
