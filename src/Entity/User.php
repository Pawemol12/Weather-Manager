<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Serializable;
use DateTime;
use DateTimeInterface;

/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $lastLoginDate;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function __construct() {
        $this->createdAt = new DateTime();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }


    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface|null $createdAt
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getRoles(): array
    {
        return array_unique(array_merge(['ROLE_USER'], $this->roles));
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function resetRoles()
    {
        $this->roles = [];
    }

    /**
     *
     */
    public function getSalt() {
        ;
    }

    /**
     *
     */
    public function eraseCredentials() {
        ;
    }

    /**
     *
     * @return string
     */
    public function serialize(): string {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->createdAt,
            $this->roles
        ]);
    }

    /**
     *
     * @param mixed $serialized
     */
    public function unserialize($serialized): void {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->createdAt,
            $this->roles
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     *
     * @return DateTime|null
     */
    public function getLastLoginDate(): ?DateTime {
        return $this->lastLoginDate;
    }

    /**
     * @param DateTime $lastLoginDate
     */
    public function setLastLoginDate(?DateTime $lastLoginDate): void {
        $this->lastLoginDate = $lastLoginDate;
    }
}