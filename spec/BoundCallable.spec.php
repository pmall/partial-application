<?php

use function Eloquent\Phony\Kahlan\stub;

use Quanta\Undefined;
use Quanta\Placeholder;
use Quanta\BoundCallable;

describe('BoundCallable', function () {

    beforeEach(function () {

        $this->callable = stub();

    });

    describe('->__invoke()', function () {

        context('when the bound argument is a placeholder', function () {

            beforeEach(function () {

                $this->bound = new BoundCallable($this->callable, Placeholder::class, 2);

            });

            context('when enough arguments are given', function () {

                it('should call the callable with the given arguments', function () {

                    $this->callable->with('v1', 'v2', 'v3')->returns('value');

                    $test = ($this->bound)('v1', 'v2', 'v3');

                    expect($test)->toEqual('value');

                });

            });

            context('when more than enough arguments are given', function () {

                it('should call the callable with the given arguments', function () {

                    $this->callable->with('v1', 'v2', 'v3', 'v4')->returns('value');

                    $test = ($this->bound)('v1', 'v2', 'v3', 'v4');

                    expect($test)->toEqual('value');

                });

            });

            context('when not enough arguments are given', function () {

                it('should call the callable with the given arguments completed with undefined values', function () {

                    $this->callable->with('v1', Undefined::class, Undefined::class)->returns('value');

                    $test = ($this->bound)('v1');

                    expect($test)->toEqual('value');

                });

            });

        });

        context('when the bound argument is not a placeholder', function () {

            beforeEach(function () {

                $this->bound = new BoundCallable($this->callable, 'v3', 2);

            });

            context('when enough arguments are given', function () {

                it('should call the callable with the given arguments', function () {

                    $this->callable->with('v1', 'v2', 'v3')->returns('value');

                    $test = ($this->bound)('v1', 'v2');

                    expect($test)->toEqual('value');

                });

            });

            context('when more than enough arguments are given', function () {

                it('should call the callable with the given arguments', function () {

                    $this->callable->with('v1', 'v2', 'v3', 'v4')->returns('value');

                    $test = ($this->bound)('v1', 'v2', 'v4');

                    expect($test)->toEqual('value');

                });

            });

            context('when not enough arguments are given', function () {

                it('should call the callable with the given arguments completed with undefined values', function () {

                    $this->callable->with('v1', Undefined::class, 'v3')->returns('value');

                    $test = ($this->bound)('v1');

                    expect($test)->toEqual('value');

                });

            });

        });

    });

});
