<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\Placeholder;
use Quanta\PartialApplication;

use Quanta\PA\Constructor;
use Quanta\PA\CallableAdapter;
use Quanta\PA\CallableInterface;

describe('PartialApplication::fromCallable()', function () {

    it('should return a partial application from the given callable', function () {

        $callable = function () {};

        $test = PartialApplication::fromCallable($callable, 'a', 'b', 'c');

        expect($test)->toEqual(
            new PartialApplication(
                new CallableAdapter($callable), 'a', 'b', 'c'
            )
        );

    });

});

describe('PartialApplication::fromClass()', function () {

    it('should return a partial application of the constructor of the given class', function () {

        $test = PartialApplication::fromClass(SomeClass::class, 'a', 'b', 'c');

        expect($test)->toEqual(
            new PartialApplication(
                new Constructor(SomeClass::class), 'a', 'b', 'c'
            )
        );

    });


});

describe('PartialApplication', function () {

    beforeEach(function () {

        $this->callable = mock(CallableInterface::class);

        $this->pa = new PartialApplication($this->callable->get(), ...[
            'bound1',
            Placeholder::class,
            Placeholder::class,
            'bound2',
            Placeholder::class,
        ]);

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
