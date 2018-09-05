<?php

use function Eloquent\Phony\Kahlan\stub;

use Quanta\Placeholder;
use Quanta\PartialApplication;

describe('PartialApplication', function () {

    beforeEach(function () {

        $this->callable = stub();

        $this->partial = new PartialApplication($this->callable, ...[
            'a1',
            Placeholder::class,
            Placeholder::class,
            'a4',
        ]);

    });

    describe('->__invoke()', function () {

        context('when enough arguments are given', function () {

            it('should call the callable once', function () {

                ($this->partial)('a2', 'a3');

                $this->callable->once()->called();

            });

            it('should call the callable with the given arguments completed with the bound arguments', function () {

                ($this->partial)('a2', 'a3', 'a5');

                $this->callable->calledWith('a1', 'a2', 'a3', 'a4', 'a5');

            });

            it('should return the value produced by the callable', function () {

                $this->callable->returns('value');

                $test = ($this->partial)('a2', 'a3');

                expect($test)->toEqual('value');

            });

        });

        context('when less arguments than placeholders are given', function () {

            it('should throw an ArgumentCountError', function () {

                $test = function () { ($this->partial)('v2'); };

                expect($test)->toThrow(new ArgumentCountError);

            });

        });

    });

});
