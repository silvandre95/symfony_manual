<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager)
    {
        $userAdminA = new User();
        $userAdminA->setUsername('joselourenco');
        $userAdminA->setRoles(array('ROLE_ADMIN'));

        $hashedPasswordA = $this->passwordHasher->hashPassword(
            $userAdminA,
            'administrador'
        );

        $userAdminA->setPassword($hashedPasswordA);
        $manager->persist($userAdminA);

        $userAdminS = new User();
        $userAdminS->setUsername('andresilva');
        $userAdminS->setRoles(array('ROLE_USER'));

        $hashedPasswordS = $this->passwordHasher->hashPassword(
            $userAdminA,
            'utilizador'
        );

        $userAdminS->setPassword($hashedPasswordS);
        $manager->persist($userAdminS);

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 0;
    }
}
