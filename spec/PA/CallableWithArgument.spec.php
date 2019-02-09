<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\Placeholder;
use Quanta\PA\CallableInterface;
use Quanta\PA\ParameterCollection;
use Quanta\PA\CallableWithArgument;

describe('CallableWithArgument', function () {

    beforeEach(function () {

        $this->delegate = mock(CallableInterface::class);

        $this->callable = new CallableWithArgument($this->delegate->get(), 'argument');

    });

    it('should implement CallableInterface', function () {

        expect($this->callable)->toBeAnInstanceOf(CallableInterface::class);

    });

    describe('->parameters()', function () {

        beforeEach(function () {

            $this->parameters = new ParameterCollection(...[
                'parameter1',
                'parameter2',
                'parameter3',
            ]);

        });

        context('when optional parameters are not included', function () {

            it('should return the delegate parameters', function () {

                $this->delegate->parameters->with(false)->returns($this->parameters);

                $test = $this->callable->parameters();

                expect($test)->toBe($this->parameters);

            });

        });

        context('when optional parameters are included', function () {

            it('should return the delegate parameters', function () {

                $this->delegate->parameters->with(true)->returns($this->parameters);

                $test = $this->callable->parameters(true);

                expect($test)->toBe($this->parameters);

            });

        });

    });

    describe('->__invoke()', function () {

        context('when the delegate has no parameter', function () {

            beforeEach(function () {

                $this->delegate->parameters->returns(new ParameterCollection);

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

        context('when the delegate has at least one parameter', function () {

            beforeEach(function () {

                $this->delegate->parameters->returns(new ParameterCollection(...[
                    'parameter1',
                    'parameter2',
                    'parameter3',
                ]));

            });

            context('when less argument than the delegate parameters are given', function () {

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

            context('when as many arguments as the delegate parameters are given', function () {

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

            context('when more arguments than the delegate parameters are given', function () {

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
