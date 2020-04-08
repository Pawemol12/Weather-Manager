<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Enum\UserRolesEnum;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */
class UserFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserFixture constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $usersInfo = [
            [
                'username' => 'user_user',
                'password' => '0000',
                'roles' => [
                    UserRolesEnum::USER_ROLE_USER,
                ]
            ],
            [
                'username' => 'user_mod',
                'password' => '0000',
                'roles' => [
                    UserRolesEnum::USER_ROLE_MOD,
                ]
            ],
            [
                'username' => 'user_admin',
                'password' => '0000',
                'roles' => [
                    UserRolesEnum::USER_ROLE_ADMIN,
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
