<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $this->addCity($manager);
        $this->addState($manager);
        $this->addPlace($manager, $faker);
        $this->addCampus($manager);
        $this->addUser($manager, $faker);
        $this->addEvent($manager, $faker);

        //$manager->flush();
    }

    public function addUser(ObjectManager $manager, Generator $generator)
    {
        $campus = $manager->getRepository(Campus::class)->findAll();

        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user
                ->setUsername($generator->userName)
                ->setRoles(["ROLE_USER"]);
                $password = $this->passwordHasher->hashPassword($user,'123');
            $user
                ->setPassword($password)
                ->setName($generator->lastName)
                ->setFirstName($generator->firstName)
                ->setPhone($generator->phoneNumber)
                ->setEmail($generator->email)
                ->setActive($generator->boolean())
                ->setCampus($generator->randomElement($campus));
            $manager->persist($user);
        }

        $manager->flush();

    }

    public function addEvent(ObjectManager $manager, Generator $generator)
    {
        $campus = $manager->getRepository(Campus::class)->findAll();
        $place = $manager->getRepository(Place::class)->findAll();
        $state = $manager->getRepository(State::class)->findAll();
        $planner = $manager->getRepository(User::class)->findAll();
       // $users = $manager->getRepository(Co::class)->findAll();

        for ($i = 0; $i < 50; $i++) {
            $event = new Event();
            $event
                ->setName($generator->word)
                ->setStartDateTime($generator->dateTimeBetween("-1 month", "+6 month"))
                ->setDuration($generator->numberBetween(30, 240))
                ->setRegistrationDeadline($generator->dateTimeBetween("-1 month", "+6 month"))
                ->setNbRegistrationMax($generator->numberBetween(5, 30))
                ->setEventData(implode(" ",$generator->words(10)))
            ->setCampus($generator->randomElement($campus))
            ->setPlace($generator->randomElement($place))
            ->setState($generator->randomElement($state))
            ->setPlanner($generator->randomElement($planner));

            $manager->persist($event);
        }

        $manager->flush();

    }

    public function addCampus(ObjectManager $manager)
    {
        $campusRennes = new Campus();
        $campusRennes
            ->setName("Rennes");
        $manager->persist($campusRennes);

        $campusNantes = new Campus();
        $campusNantes
            ->setName("Nantes");
        $manager->persist($campusNantes);

        $campusQuimper = new Campus();
        $campusQuimper
            ->setName("Quimper");
        $manager->persist($campusQuimper);

        $campusNiort = new Campus();
        $campusNiort
            ->setName("Niort");
        $manager->persist($campusNiort);

        $manager->flush();

    }

    public function addCity(ObjectManager $manager)
    {
        $cityRennes = new City();
        $cityRennes
            ->setName("Rennes")
            ->setZipCode("35000");
        $manager->persist($cityRennes);

        $cityNantes = new City();
        $cityNantes
            ->setName("Nantes")
            ->setZipCode("44000");
        $manager->persist($cityNantes);

        $cityQuimper = new City();
        $cityQuimper
            ->setName("Quimper")
            ->setZipCode("29000");
        $manager->persist($cityQuimper);

        $cityNiort = new City();
        $cityNiort
            ->setName("Niort")
            ->setZipCode("79000");
        $manager->persist($cityNiort);

        $manager->flush();

    }

    public function addPlace(ObjectManager $manager, Generator $generator)
    {
        $cities = $manager->getRepository(City::class)->findAll();
        for ($i = 0; $i < 10; $i++) {
            $place = new Place();
            $place
                ->setName($generator->word)
                ->setStreet($generator->streetAddress)
                ->setLatitude($generator->latitude)
                ->setLongitude($generator->longitude)
                ->setCity($generator->randomElement($cities));
            $manager->persist($place);
        }
        $manager->flush();

    }

    public function addState(ObjectManager $manager)
    {
        $stateCreated = new State();
        $stateCreated
            ->setLabel("created");
        $manager->persist($stateCreated);

        $stateOpen = new State();
        $stateOpen
            ->setLabel("open");
        $manager->persist($stateOpen);

        $stateClosed = new State();
        $stateClosed
            ->setLabel("closed");
        $manager->persist($stateClosed);

        $stateInProgress = new State();
        $stateInProgress
            ->setLabel("inProgress");
        $manager->persist($stateInProgress);

        $stateFinished = new State();
        $stateFinished
            ->setLabel("finished");
        $manager->persist($stateFinished);

        $stateCanceled = new State();
        $stateCanceled
            ->setLabel("canceled");
        $manager->persist($stateCanceled);

        $stateArchived = new State();
        $stateArchived
            ->setLabel("archived");
        $manager->persist($stateArchived);

        $manager->flush();

    }

}
