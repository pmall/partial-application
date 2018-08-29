<?php

use function Eloquent\Phony\Kahlan\stub;

use Quanta\Placeholder;
use Quanta\PartialApplication;
use Quanta\PartialApplicationException;

describe('PartialApplication', function () {

    beforeEach(function () {

        $this->callable = stub();

    });

    describe('->placeholders()', function () {

        it('should return an array of placeholder positions', function () {

            $partial = new PartialApplication($this->callable, [
                'v1',
                Placeholder::class,
                'v3',
                Placeholder::class,
            ]);

            $test = $partial->placeholders();

            expect($test)->toEqual([1, 3]);

        });

    });

    describe('->__invoke()', function () {

        context('when the partial application do not have placeholder', function () {

            it('should return the value produced by the callable with those arguments', function () {

                $partial = new PartialApplication($this->callable, ['v1', 'v2']);

                $this->callable->with('v1', 'v2')->returns('value');

                $test = $partial();

                expect($test)->toEqual('value');

            });

        });

        context('when the partial application have at least one placeholder', function () {

            beforeEach(function () {

                $this->partial = new PartialApplication($this->callable, [
                    'v1',
                    Placeholder::class,
                    'v3',
                    Placeholder::class,
                ]);

            });

            context('when as many arguments as placeholders are given', function () {

                it('should return the value produced by the callable with those arguments', function () {

                    $this->callable->with('v1', 'v2', 'v3', 'v4', 'v5')->returns('value');

                    $test = ($this->partial)('v2', 'v4', 'v5');

                    expect($test)->toEqual('value');

                });

            });

            context('when less arguments than placeholders are given', function () {

                it('should throw a PartialApplicationException', function () {

                    $test = function () { ($this->partial)('v2'); };

                    $exception = new PartialApplicationException($this->callable, 3);

                    expect($test)->toThrow($exception);

                });

            });

        });

    });

});
