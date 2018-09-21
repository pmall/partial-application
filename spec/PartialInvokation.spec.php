<?php

use function Eloquent\Phony\Kahlan\stub;

use Quanta\Placeholder;
use Quanta\PartialInvokation;

describe('PartialInvokation', function () {

    beforeEach(function () {

        $this->callable = stub();

        $this->partial = new PartialInvokation($this->callable, ...[
            'v1',
            Placeholder::class,
            Placeholder::class,
            'v4',
        ]);

    });

    describe('->__invoke()', function () {

        context('when enough arguments are given', function () {

            it('should call the callable once', function () {

                ($this->partial)('v1', 'v2');

                $this->callable->once()->called();

            });

            it('should call the callable with the bound arguments completed with the given arguments', function () {

                ($this->partial)('v2', 'v3', 'v5');

                $this->callable->calledWith('v1', 'v2', 'v3', 'v4', 'v5');

            });

            it('should return the value produced by the callable', function () {

                $this->callable->returns('value');

                $test = ($this->partial)('v1', 'v2');

                expect($test)->toEqual('value');

            });

        });

        context('when not enough arguments are given', function () {

            it('should throw an ArgumentCountError', function () {

                $test = function () { ($this->partial)('v1'); };

                expect($test)->toThrow(new ArgumentCountError);

            });

        });

    });

});
