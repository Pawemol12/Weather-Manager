<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Enum\UserRoles;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $usersInfo = [
            [
                'username' => 'user_user',
                'password' => '0000',
                'roles' => [
                    UserRoles::USER_ROLE_USER,
                ]
            ],
            [
                'username' => 'user_mod',
                'password' => '0000',
                'roles' => [
                    UserRoles::USER_ROLE_MOD,
                ]
            ],
            [
                'username' => 'user_admin',
                'password' => '0000',
                'roles' => [
                    UserRoles::USER_ROLE_MOD,
                ]
            ],
        ];

        foreach($usersInfo as $userInfo) {
            $user = new User();
            $user->setUsername($userInfo['username']);
            $user->setPassword(
                $this->encoder->encodePassword($user, $userInfo['password'])
            );
            $user->setRoles($userInfo['roles']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
