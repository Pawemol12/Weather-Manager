<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */
class CityFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $citiesArray = [
            [
                'name' => 'Racibórz',
                'state' => 'pl',
            ],
            [
                'name' => 'Katowice',
                'state' => 'pl',
            ],
            [
                'name' => 'Warszawa',
                'state' => 'pl',
            ],
            [
                'name' => 'Gdańsk',
                'state' => 'pl',
            ],
            [
                'name' => 'Wrocław',
                'state' => 'pl',
            ],
        ];

        foreach($citiesArray as $cityInfo) {
            $city = new City();
            $city->setName($cityInfo['name']);
            $city->setState($cityInfo['state']);

            $manager->persist($city);
        }

        $manager->flush();
    }
}
