<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_EXPERT_REFERENCE = 'user-expert-reference';
    public const USER_IPRUS_REFERENCE = 'user-iprus-reference';
    public const USER_TILL_LINDEMANN_REFERENCE = 'user-till-lindemann-reference';

    public const USERS_LINK_REFERENCES = [
        self::USER_EXPERT_REFERENCE,
        self::USER_IPRUS_REFERENCE,
        self::USER_TILL_LINDEMANN_REFERENCE,
    ];

    public function __construct(
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        foreach ($this->getUsers() as $item) {
            $user = new User(
                username: $item['username']
            );

            $user->setPassword($this->hasher->hashPassword($user, $item['password']));
            $user->setFirstName($item['firstName']);
            $user->setSurname($item['surname']);
            $user->setAbout($item['about']);

            $this->addReference($item['reference'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUsers(): array
    {
        return [
            [
                'reference' => self::USER_EXPERT_REFERENCE,
                'username' => 'expert',
                'firstName' => 'Игорь',
                'surname' => 'Попрядухин',
                'about' => 'Expert extra',
                'password' => '12345',
            ],
            [
                'reference' => self::USER_IPRUS_REFERENCE,
                'username' => 'iprus',
                'firstName' => 'Александр',
                'surname' => 'Янушковский',
                'about' => 'Delphi Developer, сис. админ',
                'password' => '12345',
            ],
            [
                'reference' => self::USER_TILL_LINDEMANN_REFERENCE,
                'username' => 'till-lindemann',
                'firstName' => 'Тиль',
                'surname' => 'Линдерман',
                'about' => 'Ich Will',
                'password' => '12345',
            ],
        ];
    }
}
