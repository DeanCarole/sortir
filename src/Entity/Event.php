<?php

namespace App\Entity;


use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Le nom de ta sortie est trop petit. Elle doit avoir minimum {{ limit }} caractères',
        maxMessage: 'Le nom de ta sortie est trop grand. Elle doit avoir maximum {{ limit }} caractères',
    )]
    private ?string $name = null;



    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual("today", message: "La date de début doit être ultérieure à aujourd'hui.")]
    private ?\DateTimeInterface $startDateTime = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\LessThan(propertyPath: "startDateTime", message: "La date limite d'inscription doit être antérieure à la date de la sortie.")]
    private ?\DateTimeInterface $registrationDeadline = null;


    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $duration = null;


    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $nbRegistrationMax = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $eventData = null;


    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?State $state = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private Collection $user;

    #[ORM\ManyToOne(inversedBy: 'eventsPlanned')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $planner = null;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRegistrationDeadline(): ?\DateTimeInterface
    {
        return $this->registrationDeadline;
    }

    public function setRegistrationDeadline(\DateTimeInterface $registrationDeadline): self
    {
        $this->registrationDeadline = $registrationDeadline;

        return $this;
    }

    public function getNbRegistrationMax(): ?int
    {
        return $this->nbRegistrationMax;
    }

    public function setNbRegistrationMax(int $nbRegistrationMax): self
    {
        $this->nbRegistrationMax = $nbRegistrationMax;

        return $this;
    }

    public function getEventData(): ?string
    {
        return $this->eventData;
    }

    public function setEventData(?string $eventData): self
    {
        $this->eventData = $eventData;

        return $this;
    }


    public function getPlace(): ?Place
    {
        return $this->place;
    }





    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }


    #[ORM\PrePersist]
    public function setState(?State $state): self
    {

        $this->state = $state;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function getPlanner(): ?User
    {
        return $this->planner;
    }

    public function setPlanner(?User $planner): self
    {
        $this->planner = $planner;

        return $this;
    }



}
