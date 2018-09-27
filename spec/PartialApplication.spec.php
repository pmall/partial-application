<?php

use function Eloquent\Phony\Kahlan\stub;

use Quanta\Placeholder;
use Quanta\PartialApplication;

describe('PartialApplication', function () {

    beforeEach(function () {

        $this->callable = stub();

        $this->partial = new PartialApplication($this->callable, ...[
            'v1',
            Placeholder::class,
            Placeholder::class,
            'v4',
        ]);

    });

    describe('->__invoke()', function () {

        context('when as many arguments as placeholders are given', function () {

            it('should call the callable with the bound arguments completed with the given arguments', function () {

                $this->callable->with('v1', 'v2', 'v3', 'v4')->returns('value');

                $test = ($this->partial)('v2', 'v3');

                expect($test)->toEqual('value');

            });

        });

        context('when more arguments than placeholders are given', function () {

            it('should call the callable with the bound arguments completed with the given arguments', function () {

                $this->callable->with('v1', 'v2', 'v3', 'v4', 'v5')->returns('value');

                $test = ($this->partial)('v2', 'v3', 'v5');

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
