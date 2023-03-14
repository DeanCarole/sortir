<?php

namespace App\Form\Filter;

use App\Entity\Campus;
use DateTime;

class Filter
{
    private ?Campus $campus = null;
    private ?string $name = null;
    private DateTime|null $startDate = null;
    private DateTime|null $endDate = null;
    private ?bool $eventsPlanned = null;
    private ?bool $eventsRegistered = null;
    private ?bool $eventsNotRegistered = null;
    private ?bool $eventsPassed = null;

    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus|null $campus
     */
    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime|null $startDate
     */
    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime|null $endDate
     */
    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return bool|null
     */
    public function getEventsPlanned(): ?bool
    {
        return $this->eventsPlanned;
    }

    /**
     * @param bool|null $eventsPlanned
     */
    public function setEventsPlanned(?bool $eventsPlanned): void
    {
        $this->eventsPlanned = $eventsPlanned;
    }

    /**
     * @return bool|null
     */
    public function getEventsRegistered(): ?bool
    {
        return $this->eventsRegistered;
    }

    /**
     * @param bool|null $eventsRegistered
     */
    public function setEventsRegistered(?bool $eventsRegistered): void
    {
        $this->eventsRegistered = $eventsRegistered;
    }

    /**
     * @return bool|null
     */
    public function getEventsNotRegistered(): ?bool
    {
        return $this->eventsNotRegistered;
    }

    /**
     * @param bool|null $eventsNotRegistered
     */
    public function setEventsNotRegistered(?bool $eventsNotRegistered): void
    {
        $this->eventsNotRegistered = $eventsNotRegistered;
    }

    /**
     * @return bool|null
     */
    public function getEventsPassed(): ?bool
    {
        return $this->eventsPassed;
    }

    /**
     * @param bool|null $eventsPassed
     */
    public function setEventsPassed(?bool $eventsPassed): void
    {
        $this->eventsPassed = $eventsPassed;
    }



}