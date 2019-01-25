<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\Placeholder;
use Quanta\PA\BoundCallable;
use Quanta\PA\CallableInterface;

describe('BoundCallable', function () {

    beforeEach(function () {

        $this->delegate = mock(CallableInterface::class);

        $this->callable = new BoundCallable($this->delegate->get(), 'argument');

    });

    it('should implement CallableInterface', function () {

        expect($this->callable)->toBeAnInstanceOf(CallableInterface::class);

    });

    describe('->parameters()', function () {

        it('should return the delegate parameters', function () {

            $this->delegate->parameters->returns(['parameter1', 'parameter2', 'parameter3']);

            $test = $this->callable->parameters();

            expect($test)->toEqual(['parameter1', 'parameter2', 'parameter3']);

        });

    });

    describe('->required()', function () {

        it('should return the delegate required parameters', function () {

            $this->delegate->required->returns(['parameter1', 'parameter2', 'parameter3']);

            $test = $this->callable->required();

            expect($test)->toEqual(['parameter1', 'parameter2', 'parameter3']);

        });

    });

    describe('->__invoke()', function () {

        context('when the delegate has no parameter', function () {

            beforeEach(function () {

                $this->delegate->parameters->returns([]);

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

                $this->delegate->parameters->returns(['parameter1', 'parameter2', 'parameter3']);

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
