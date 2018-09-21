<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\Undefined;
use Quanta\Placeholder;
use Quanta\PartialApplication;
use Quanta\PartialApplicationInterface;

describe('PartialApplication', function () {

    beforeEach(function () {

        $this->callable = mock(PartialApplicationInterface::class);

        $this->partial = new PartialApplication($this->callable->get(), ...[
            'v1',
            Placeholder::class,
            Placeholder::class,
            'v4',
        ]);

    });

    describe('->__invoke()', function () {

        context('when enough arguments are given', function () {

            it('should call the callable once', function () {

                ($this->partial)('v2', 'v3');

                $this->callable->__invoke->once()->called();

            });

            it('should call the callable with the bound arguments completed with the given arguments', function () {

                ($this->partial)('v2', 'v3', 'v5');

                $this->callable->__invoke->calledWith('v1', 'v2', 'v3', 'v4', 'v5');

            });

            it('should return the value produced by the callable', function () {

                $this->callable->__invoke->returns('value');

                $test = ($this->partial)('v2', 'v3');

                expect($test)->toEqual('value');

            });

        });

        context('when less arguments than placeholders are given', function () {

            it('should replace the missing arguments with Quanta\Undefined::class', function () {

                $test = ($this->partial)('v2');

                $this->callable->__invoke->calledWith('v1', 'v2', Undefined::class, 'v4');

            });

        });

    });

});
