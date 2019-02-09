<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\Placeholder;
use Quanta\PA\CallableInterface;
use Quanta\PA\ParameterCollection;
use Quanta\PA\CallableWithOptionalParameter;

describe('CallableWithOptionalParameter', function () {

    beforeEach(function () {

        $this->delegate = mock(CallableInterface::class);

        $this->callable = new CallableWithOptionalParameter(...[
            $this->delegate->get(),
            'parameter',
            'default',
        ]);

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

            it('should return the delegate parameters with this parameter', function () {

                $this->delegate->parameters->with(true)->returns($this->parameters);

                $test = $this->callable->parameters(true);

                expect($test)->toEqual(new ParameterCollection(...[
                    'parameter1',
                    'parameter2',
                    'parameter3',
                    'parameter',
                ]));

            });

        });

    });

    describe('->__invoke()', function () {

        context('when the delegate has no parameter', function () {

            beforeEach(function () {

                $this->delegate->parameters->returns(new ParameterCollection);

            });

            context('when no argument is given', function () {

                it('should invoke the delegate with the default value', function () {

                    $this->delegate->__invoke->with('default')->returns('value');

                    $test = ($this->callable)();

                    expect($test)->toEqual('value');

                });

            });

            context('when at least one argument is given', function () {

                it('should invoke the delegate with the given arguments', function () {

                    $this->delegate->__invoke->with(...[
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

                it('should invoke the delegate with the given arguments, placeholders and the default value', function () {

                    $this->delegate->__invoke->with(...[
                        'value1',
                        Placeholder::class,
                        Placeholder::class,
                        'default',
                    ])->returns('value');

                    $test = ($this->callable)('value1');

                    expect($test)->toEqual('value');

                });

            });

            context('when as many arguments as the delegate parameters are given', function () {

                it('should invoke the delegate with the given arguments and the default value', function () {

                    $this->delegate->__invoke->with(...[
                        'value1',
                        'value2',
                        'value3',
                        'default',
                    ])->returns('value');

                    $test = ($this->callable)('value1', 'value2', 'value3');

                    expect($test)->toEqual('value');

                });

            });

            context('when more arguments than the delegate parameters are given', function () {

                it('should invoke the delegate with the given arguments', function () {

                    $this->delegate->__invoke->with(...[
                        'value1',
                        'value2',
                        'value3',
                        'value4',
                    ])->returns('value');

                    $test = ($this->callable)('value1', 'value2', 'value3', 'value4');

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
