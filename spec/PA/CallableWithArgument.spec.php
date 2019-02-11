<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\Placeholder;
use Quanta\PA\CallableInterface;
use Quanta\PA\PlaceholderSequence;
use Quanta\PA\CallableWithArgument;

describe('CallableWithArgument', function () {

    beforeEach(function () {

        $this->delegate = mock(CallableInterface::class);

        $this->callable = new CallableWithArgument(
            $this->delegate->get(),
            'argument'
        );

    });

    it('should implement CallableInterface', function () {

        expect($this->callable)->toBeAnInstanceOf(CallableInterface::class);

    });

    describe('->placeholders()', function () {

        beforeEach(function () {

            $this->placeholders = new PlaceholderSequence(...[
                'placeholder1',
                'placeholder2',
                'placeholder3',
            ]);

        });

        context('when optional placeholders are not included', function () {

            it('should return the delegate placeholders', function () {

                $this->delegate->placeholders->with(false)->returns($this->placeholders);

                $test = $this->callable->placeholders();

                expect($test)->toBe($this->placeholders);

            });

        });

        context('when optional placeholders are included', function () {

            it('should return the delegate placeholders', function () {

                $this->delegate->placeholders->with(true)->returns($this->placeholders);

                $test = $this->callable->placeholders(true);

                expect($test)->toBe($this->placeholders);

            });

        });

    });

    describe('->__invoke()', function () {

        context('when the delegate has no placeholder', function () {

            beforeEach(function () {

                $this->delegate->placeholders->returns(new PlaceholderSequence);

            });

            context('when no argument is given', function () {

                it('should invoke the delegate with the argument', function () {

                    $this->delegate->__invoke->with('argument')->returns('value');

                    $test = ($this->callable)();

                    expect($test)->toEqual('value');

                });

            });

            context('when at least one argument is given', function () {

                it('should invoke the delegate with the argument and the given arguments', function () {

                    $this->delegate->__invoke->with(...[
                        'argument',
                        'value1',
                        'value2',
                        'value3',
                    ])->returns('value');

                    $test = ($this->callable)('value1', 'value2', 'value3');

                    expect($test)->toEqual('value');

                });

            });

        });

        context('when the delegate has at least one placeholder', function () {

            beforeEach(function () {

                $this->delegate->placeholders->returns(new PlaceholderSequence(...[
                    'placeholder1',
                    'placeholder2',
                    'placeholder3',
                ]));

            });

            context('when less argument than the delegate placeholders are given', function () {

                it('should invoke the delegate with the given arguments, placeholders and the argument', function () {

                    $this->delegate->__invoke->with(...[
                        'value1',
                        Placeholder::class,
                        Placeholder::class,
                        'argument',
                    ])->returns('value');

                    $test = ($this->callable)('value1');

                    expect($test)->toEqual('value');

                });

            });

            context('when as many arguments as the delegate placeholders are given', function () {

                it('should invoke the delegate with the given arguments and the argument', function () {

                    $this->delegate->__invoke->with(...[
                        'value1',
                        'value2',
                        'value3',
                        'argument',
                    ])->returns('value');

                    $test = ($this->callable)('value1', 'value2', 'value3');

                    expect($test)->toEqual('value');

                });

            });

            context('when more arguments than the delegate placeholders are given', function () {

                it('should invoke the delegate with the given arguments, the argument and the extra arguments', function () {

                    $this->delegate->__invoke->with(...[
                        'value1',
                        'value2',
                        'value3',
                        'argument',
                        'value4',
                        'value5',
                    ])->returns('value');

                    $test = ($this->callable)('value1', 'value2', 'value3', 'value4', 'value5');

                    expect($test)->toEqual('value');

                });

            });

        });

    });

    describe('->str()', function () {

        it('should return the delegate string representation', function () {

            $this->delegate->str->returns('str');

            $test = $this->callable->str();

            expect($test)->toEqual('str');

        });

    });

});
