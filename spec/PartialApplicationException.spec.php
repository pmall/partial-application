<?php

use function Eloquent\Phony\Kahlan\stub;

use Quanta\PartialApplicationException;

describe('PartialApplicationException', function () {

    it('should implement Throwable', function () {

        $exception = new PartialApplicationException(stub(), 1);

        expect($exception)->toBeAnInstanceOf(Throwable::class);

    });

});
