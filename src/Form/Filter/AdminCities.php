<?php

namespace App\Form\Filter;

use App\Entity\City;

class AdminCities
{
    private ?City $city = null;

    /**
     * @return City|null
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param City|null $city
     */
    public function setCity(?City $city): void
    {
        $this->city = $city;
    }
}