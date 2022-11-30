<?php

namespace App\Dto;

class ContributorResource
{
    private Contributor $contributor;

    private string $id;

    private int $rating;

    public function __construct(Contributor $contributor, string $id, int $rating)
    {
        $this->contributor = $contributor;
        $this->id = $id;
        $this->rating = $rating;
    }

    /**
     * Get the value of contributor
     *
     * @return Contributor
     */
    public function getContributor(): Contributor
    {
        return $this->contributor;
    }

    /**
     * Set the value of contributor
     *
     * @param  Contributor  $contributor
     * @return self
     */
    public function setContributor(Contributor $contributor): self
    {
        $this->contributor = $contributor;

        return $this;
    }

    /**
     * Get the value of id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  string  $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of rating
     *
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * Set the value of rating
     *
     * @param  int  $rating
     * @return self
     */
    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
