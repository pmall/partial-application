<?php

use function Eloquent\Phony\Kahlan\mock;

use Quanta\PA\CallableInterface;
use Quanta\PA\ParameterCollection;
use Quanta\PA\CallableWithRequiredParameter;

describe('CallableWithRequiredParameter', function () {

    beforeEach(function () {

        $this->delegate = mock(CallableInterface::class);

        $this->callable = new CallableWithRequiredParameter($this->delegate->get(), 'parameter');

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

            it('should return the delegate parameters with this parameter', function () {

                $this->delegate->parameters->with(true)->returns($this->parameters);

                $test = $this->callable->parameters();

                expect($test)->toEqual(new ParameterCollection(...[
                    'parameter1',
                    'parameter2',
                    'parameter3',
                    'parameter',
                ]));

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

                it('should throw an ArgumentCountError', function () {

                    expect($this->callable)->toThrow(new ArgumentCountError);

                });

                it('should display the delegate string representation and the parameter name', function () {

                    $this->delegate->str->returns('str');

                    try {
                        ($this->callable)();
                    }

                    catch (ArgumentCountError $e) {
                        $test = $e->getMessage();
                    }

                    expect($test)->toContain('function str()');
                    expect($test)->toContain('parameter $parameter ');

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

                it('should throw an ArgumentCountError', function () {

                    $test = function () { ($this->callable)('value1', 'value2'); };

                    expect($this->callable)->toThrow(new ArgumentCountError);

                });

                it('should display the delegate string representation and format one unbound parameter', function () {

                    $this->delegate->str->returns('str');

                    try {
                        ($this->callable)('value1', 'value2');
                    }

                    catch (ArgumentCountError $e) {
                        $test = $e->getMessage();
                    }

                    expect($test)->toContain('function str()');
                    expect($test)->toContain('parameters $parameter3 and $parameter ');

                });

                it('should display the delegate string representation and format multiple unbound parameters', function () {

                    $this->delegate->str->returns('str');

                    try {
                        ($this->callable)('value1');
                    }

                    catch (ArgumentCountError $e) {
                        $test = $e->getMessage();
                    }

                    expect($test)->toContain('function str()');
                    expect($test)->toContain('parameters $parameter2, $parameter3 and $parameter ');

                });

            });

            context('when as many arguments as the delegate parameters are given', function () {

                it('should throw an ArgumentCountError', function () {

                    $test = function () { ($this->callable)('value1', 'value2', 'value3'); };

                    expect($this->callable)->toThrow(new ArgumentCountError);

                });

                it('should display the delegate string representation and the parameter name', function () {

                    $this->delegate->str->returns('str');

                    try {
                        ($this->callable)('value1', 'value2', 'value3');
                    }

                    catch (ArgumentCountError $e) {
                        $test = $e->getMessage();
                    }

                    expect($test)->toContain('function str()');
                    expect($test)->toContain('parameter $parameter ');

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