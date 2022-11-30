<?php

namespace App\Dto;

use Carbon\Carbon;

class Download
{
    private ContributorResource $resource;

    private Carbon $date;

    public function __construct(ContributorResource $resource, Carbon $date)
    {
        $this->resource = $resource;
        $this->date = $date;
    }

    /**
     * Get the value of resource
     *
     * @return ContributorResource
     */
    public function getResource(): ContributorResource
    {
        return $this->resource;
    }

    /**
     * Set the value of resource
     *
     * @param  ContributorResource  $resource
     * @return self
     */
    public function setResource(ContributorResource $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get the value of date
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @param  Carbon  $date
     * @return self
     */
    public function setDate(Carbon $date): self
    {
        $this->date = $date;

        return $this;
    }
}
