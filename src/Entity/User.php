<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gift", mappedBy="giver")
     */
    private $gifts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Guest", mappedBy="user")
     */
    private $guests;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gallery", mappedBy="creator")
     */
    private $galleries;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        parent::__construct();
        $this->gifts = new ArrayCollection();
        $this->guests = new ArrayCollection();
        $this->galleries = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->username;
    }

    /**
     * @return Collection|Gift[]
     */
    public function getGifts(): Collection
    {
        return $this->gifts;
    }

    public function addGift(Gift $gift): self
    {
        if (!$this->gifts->contains($gift)) {
            $this->gifts[] = $gift;
            $gift->setGiver($this);
        }

        return $this;
    }

    public function removeGift(Gift $gift): self
    {
        if ($this->gifts->contains($gift)) {
            $this->gifts->removeElement($gift);
            // set the owning side to null (unless already changed)
            if ($gift->getGiver() === $this) {
                $gift->setGiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Guest[]
     */
    public function getGuests(): Collection
    {
        return $this->guests;
    }

    public function addGuest(Guest $guest): self
    {
        if (!$this->guests->contains($guest)) {
            $this->guests[] = $guest;
            $guest->setUser($this);
        }

        return $this;
    }

    public function removeGuest(Guest $guest): self
    {
        if ($this->guests->contains($guest)) {
            $this->guests->removeElement($guest);
            // set the owning side to null (unless already changed)
            if ($guest->getUser() === $this) {
                $guest->setUser(null);
            }
        }

        return $this;
    }

    public function hasAnswered(): bool
    {
        foreach ($this->guests as $guest) {
            if (!$guest->hasAnswered()) {
                return false;
            }
        }
        return true;
    }

    public function isInvitedForCeremonyOnly(): bool
    {
        if ($this->guests->isEmpty()) {
            return true;
        }
        return $this->guests->first()->isInvitedForCeremonyOnly();
    }

    /**
     * @return Collection|Gallery[]
     */
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function addGallery(Gallery $gallery): self
    {
        if (!$this->galleries->contains($gallery)) {
            $this->galleries[] = $gallery;
            $gallery->setCreator($this);
        }

        return $this;
    }

    public function removeGallery(Gallery $gallery): self
    {
        if ($this->galleries->contains($gallery)) {
            $this->galleries->removeElement($gallery);
            // set the owning side to null (unless already changed)
            if ($gallery->getCreator() === $this) {
                $gallery->setCreator(null);
            }
        }

        return $this;
    }
}
