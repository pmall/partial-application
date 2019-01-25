<?php

use function Eloquent\Phony\Kahlan\mock;
use function Eloquent\Phony\Kahlan\partialMock;

use Quanta\Placeholder;
use Quanta\AbstractPartialApplication;
use Quanta\PartialApplicationInterface;

use Quanta\PA\CallableInterface;

describe('AbstractPartialApplication', function () {

    beforeEach(function () {

        $this->callable = mock(CallableInterface::class);

        $this->pa = partialMock(AbstractPartialApplication::class, [
            $this->callable->get(),
            'bound1',
            Placeholder::class,
            Placeholder::class,
            'bound2',
            Placeholder::class,
        ])->get();

    });

    it('should implement PartialApplicationInterface', function () {

        expect($this->pa)->toBeAnInstanceOf(PartialApplicationInterface::class);

    });

    describe('->__invoke()', function () {

        context('when less arguments than placeholders are given', function () {

            it('should throw an ArgumentCountError', function () {

                $test = function () { ($this->pa)('value1', 'value2'); };

                expect($test)->toThrow(new ArgumentCountError);

            });

            it('should display the callable string representation and the number of passed and expected arguments', function () {

                $this->callable->str->returns('str');

                try {
                    ($this->pa)('value1', 'value2');
                }

                catch (ArgumentCountError $e) {
                    $test = $e->getMessage();
                }

                expect($test)->toContain('function str');
                expect($test)->toContain('2 passed');
                expect($test)->toContain('3 expected');

            });

        });

        context('when as many arguments as placeholders are given', function () {

            it('should invoke the callable with both the bound arguments and the given arguments', function () {

                $xs = ['bound1', 'value1', 'value2', 'bound2', 'value3'];

                $this->callable->__invoke->with(...$xs)->returns('value');

                $test = ($this->pa)('value1', 'value2', 'value3');

                expect($test)->toEqual('value');

            });

        });

        context('when more arguments than placeholders are given', function () {

            it('should invoke the callable with both the bound arguments and the given arguments', function () {

                $xs = ['bound1', 'value1', 'value2', 'bound2', 'value3', 'value4'];

                $this->callable->__invoke->with(...$xs)->returns('value');

                $test = ($this->pa)('value1', 'value2', 'value3', 'value4');

                expect($test)->toEqual('value');

            });

        });

    });

});
