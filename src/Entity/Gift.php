<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Gift
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="gifts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $giver;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ListItem", inversedBy="gifts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $listItem;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGiver(): ?User
    {
        return $this->giver;
    }

    public function setGiver(?User $giver): self
    {
        $this->giver = $giver;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getListItem(): ?ListItem
    {
        return $this->listItem;
    }

    public function setListItem(?ListItem $listItem): self
    {
        $this->listItem = $listItem;

        return $this;
    }
}
