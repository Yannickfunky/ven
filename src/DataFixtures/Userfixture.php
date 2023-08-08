<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;


class Userfixture extends Fixture
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $user = new User ();

        $user->setEmail('mail@mail.com');
        $user->setUsername('Wassim');
// $product = new Product();
// $manager->persist($product);
        $user->setPassword($this->passwordHasher->hashPassword($user,'123'));
        $manager->persist($user);
        $manager->flush();
    }
}
?>