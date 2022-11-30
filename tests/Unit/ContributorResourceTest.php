<?php

use App\Dto\Contributor;
use App\Dto\ContributorResource;

it('creates and modifies contributor resource instance', function () {
    $contributor1 = new Contributor('Luke');
    $contributor2 = new Contributor('Han');

    $resource = new ContributorResource($contributor1, '123', 3);
    expect($resource->getContributor()->getName())->toBe('Luke');
    expect($resource->getId())->toBe('123');
    expect($resource->getRating())->toBe(3);

    $resource->setContributor($contributor2)
        ->setId('456')
        ->setRating(1);

    expect($resource->getContributor()->getName())->toBe('Han');
    expect($resource->getId())->toBe('456');
    expect($resource->getRating())->toBe(1);
});
