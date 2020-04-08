<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @var string|null
     */
    private $state;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     */
    private $apiCityId;

    /**
     * @ORM\Column(type="json")
     * @var array
     */
    private $lastWeatherData = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime|null
     */
    private $lastWeatherDataUpdate = null;

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
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     */
    public function setState(?string $state)
    {
        $this->state = $state;
    }

    /**
     * @return int|null
     */
    public function getApiCityId(): ?int
    {
        return $this->apiCityId;
    }

    /**
     * @param int|null $apiCityId
     */
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
