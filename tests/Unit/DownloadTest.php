<?php

use App\Dto\Contributor;
use App\Dto\ContributorResource;
use App\Dto\Download;
use Carbon\Carbon;

it('creates and modifies contributor resource instance', function () {
    $resource1 = new ContributorResource(new Contributor('Luke'), '123', 3);
    $resource2 = new ContributorResource(new Contributor('Han'), '456', 1);

    $date1 = Carbon::now()->subWeek();
    $date2 = Carbon::now()->subYear();
    $download = new Download($resource1, $date1);

    expect($download->getResource()->getId())->toBe('123');
    expect($download->getDate()->timestamp)->toBe($date1->timestamp);

    $download->setResource($resource2)
        ->setDate($date2);

    expect($download->getResource()->getId())->toBe('456');
    expect($download->getDate()->timestamp)->toBe($date2->timestamp);
});
