<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $apiCityId;

    /**
     * @ORM\Column(type="json")
     */
    private $lastWeatherData = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime|null
     */
    private $lastWeatherDataUpdate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state)
    {
        $this->state = $state;
    }

    public function getApiCityId(): ?int
    {
        return $this->apiCityId;
    }

    public function setApiCityId(?int $apiCityId)
    {
        $this->apiCityId = $apiCityId;
    }

    /**
     * @return array
     */
    public function getLastWeatherData(): array
    {
        return $this->lastWeatherData;
    }

    /**
     * @param array $lastWeatherData
     */
    public function setLastWeatherData(array $lastWeatherData): void
    {
        $this->lastWeatherData = $lastWeatherData;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getLastWeatherDataUpdate(): ?DateTimeInterface
    {
        return $this->lastWeatherDataUpdate;
    }

    /**
     * @param DateTimeInterface|null $lastWeatherDataUpdate
     */
    public function setLastWeatherDataUpdate(?DateTimeInterface $lastWeatherDataUpdate): void
    {
        $this->lastWeatherDataUpdate = $lastWeatherDataUpdate;
    }
}
