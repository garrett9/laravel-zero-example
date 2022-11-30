<?php

use App\Dto\Contributor;

it('creates and modifies contributor instance', function () {
    $contributor = new Contributor('Luke');
    expect($contributor->getName())->toBe('Luke');

    $contributor->setName('Han');
    expect($contributor->getName())->toBe('Han');
});
