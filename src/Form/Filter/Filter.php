<?php

namespace App\Form\Filter;

use App\Entity\Campus;
use Symfony\Component\Validator\Constraints\Date;

class Filter
{
    private Campus $campus;
    private string $name;
    private Date $startDate;
    private Date $endDate;
    private bool $eventsPlanned;
    private bool $eventsRegistered;
    private bool $eventsNotRegistered;
    private bool $eventsPassed;

    /**
     * @return Campus
     */
    public function getCampus(): Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus $campus
     */
    public function setCampus(Campus $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Date
     */
    public function getStartDate(): Date
    {
        return $this->startDate;
    }

    /**
     * @param Date $startDate
     */
    public function setStartDate(Date $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return Date
     */
    public function getEndDate(): Date
    {
        return $this->endDate;
    }

    /**
     * @param Date $endDate
     */
    public function setEndDate(Date $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return bool
     */
    public function isEventsPlanned(): bool
    {
        return $this->eventsPlanned;
    }

    /**
     * @param bool $eventsPlanned
     */
    public function setEventsPlanned(bool $eventsPlanned): void
    {
        $this->eventsPlanned = $eventsPlanned;
    }

    /**
     * @return bool
     */
    public function isEventsRegistered(): bool
    {
        return $this->eventsRegistered;
    }

    /**
     * @param bool $eventsRegistered
     */
    public function setEventsRegistered(bool $eventsRegistered): void
    {
        $this->eventsRegistered = $eventsRegistered;
    }

    /**
     * @return bool
     */
    public function isEventsNotRegistered(): bool
    {
        return $this->eventsNotRegistered;
    }

    /**
     * @param bool $eventsNotRegistered
     */
    public function setEventsNotRegistered(bool $eventsNotRegistered): void
    {
        $this->eventsNotRegistered = $eventsNotRegistered;
    }

    /**
     * @return bool
     */
    public function isEventsPassed(): bool
    {
        return $this->eventsPassed;
    }

    /**
     * @param bool $eventsPassed
     */
    public function setEventsPassed(bool $eventsPassed): void
    {
        $this->eventsPassed = $eventsPassed;
    }




}