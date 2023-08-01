<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $encoderFactory
     */
    public function __construct(UserPasswordEncoderInterface $encoderFactory)
    {
        $this->passwordEncoder = $encoderFactory;
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $userAdmin = new User();
        $userAdmin->setUsername('Admin');
        $userAdmin->setEmail('admin@aa.aa');
        $userAdmin->setPassword($this->passwordEncoder->encodePassword(
            $userAdmin,
          'secret'
        ));
        $userAdmin->setRoles([User::ROLE_ADMIN]);
        $manager->persist($userAdmin);

        $userModerator = new User();
        $userModerator->setUsername('Moderator');
        $userModerator->setEmail('moderator@aa.aa');
        $userModerator->setPassword($this->passwordEncoder->encodePassword(
            $userModerator,
            'secret'
        ));
        $userModerator->setRoles([User::ROLE_MODERATOR]);
        $manager->persist($userModerator);
        $manager->flush();

        $manager->flush();
    }
}
