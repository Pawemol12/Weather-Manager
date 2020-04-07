<?php

namespace App\DataFixtures;

use App\Entity\ApiSettings;
use App\Enum\ApiSettingsEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ApiSettingsFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $apiSettingsArray = [
            [
                'name' => ApiSettingsEnum::API_KEY,
                'value' => 'aec384a6604123db063b539170a17b95',
            ],
            [
                'name' => ApiSettingsEnum::WEATHER_INFO_BY_CITY_AND_STATE_LINK,
                'value' => 'http://api.openweathermap.org/data/2.5/weather?q={city name},{state}&appid={api Key}&units=metric'
            ],
            [
                'name' => ApiSettingsEnum::WEATHER_INFO_BY_CITY_LINK,
                'value' => 'http://api.openweathermap.org/data/2.5/weather?q={city name}&appid={api Key}&units=metric'
            ],
            [
                'name' => ApiSettingsEnum::WEATHER_INFO_BY_CITY_ID_LINK,
                'value' => 'http://api.openweathermap.org/data/2.5/weather?id={city id}&appid={api Key}&units=metric'
            ],
        ];

        foreach($apiSettingsArray as $apiSettingsInfo) {
            $apiSettings = new ApiSettings();
            $apiSettings->setName($apiSettingsInfo['name']);
            $apiSettings->setValue($apiSettingsInfo['value']);
            $manager->persist($apiSettings);
        }

        $manager->flush();
    }
}
