<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForgotPasswordRepository")
 */
class ForgotPassword
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiration_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="forgotPasswords")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(\DateTimeInterface $expiration_date): self
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }
}
