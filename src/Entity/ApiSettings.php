<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 * @ORM\Entity(repositoryClass="App\Repository\ApiSettingsRepository")
 */
class ApiSettings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $value;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->getName();
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }
}
