<?php

use function Eloquent\Phony\Kahlan\stub;

use Quanta\BoundCallable;

describe('BoundCallable', function () {

    beforeEach(function () {

        $this->callable = stub();

        $this->partial = new BoundCallable($this->callable, 'a3', 2);

    });

    describe('->__invoke()', function () {

        context('when enough arguments are given', function () {

            it('should call the callable once', function () {

                ($this->partial)('a1', 'a2');

                $this->callable->once()->called();

            });

            it('should call the callable with the given arguments completed with the bound argument', function () {

                ($this->partial)('a1', 'a2', 'a4');

                $this->callable->calledWith('a1', 'a2', 'a3', 'a4');

            });

            it('should return the value produced by the callable', function () {

                $this->callable->returns('value');

                $test = ($this->partial)('a1', 'a2');

                expect($test)->toEqual('value');

            });

        });

        context('when not enough arguments are given', function () {

            it('should throw an ArgumentCountError', function () {

                $test = function () { ($this->partial)('a1'); };

                expect($test)->toThrow(new ArgumentCountError);

            });

        });

    });

});
