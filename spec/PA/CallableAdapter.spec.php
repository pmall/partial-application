<?php

use function Eloquent\Phony\Kahlan\stub;

use Quanta\PA\CallableAdapter;
use Quanta\PA\CallableInterface;

require_once __DIR__ . '/../.test/classes.php';

describe('CallableAdapter', function () {

    context('with any callable', function () {

        beforeEach(function () {

            $this->delegate = stub();

            $this->callable = new CallableAdapter($this->delegate);

        });

        it('should implement CallableInterface', function () {

            expect($this->callable)->toBeAnInstanceOf(CallableInterface::class);

        });

        describe('->parameters()', function () {

            it('should return an empty array', function () {

                $test = $this->callable->parameters();

                expect($test)->toEqual([]);

            });

        });

        describe('->__invoke()', function () {

            it('should invoke the callable with the given arguments', function () {

                $this->delegate->with('value1', 'value2', 'value3')->returns('value');

                $test = ($this->callable)('value1', 'value2', 'value3');

                expect($test)->toEqual('value');

            });

        });

    });

    context('when the callable is an object', function () {

        context('when the object is a Closure', function () {

            describe('->str()', function () {

                it('should return {closure}', function () {

                    $callable = new CallableAdapter(function () {});

                    $test = $callable->str();

                    expect($test)->toEqual('{closure}');

                });

            });

        });

        context('when the object is not a Closure', function () {

            describe('->str()', function () {

                it('should return a string representation of the object __invoke() method', function () {

                    $callable = new CallableAdapter(new Test\TestClass);

                    $test = $callable->str();

                    expect($test)->toEqual('Test\TestClass::__invoke');

                });

            });

        });

    });

    context('when the callable is an array', function () {

        context('when the callable is a static method', function () {

            describe('->str()', function () {

                it('should return a string representation of the static method', function () {

                    $callable = new CallableAdapter([Test\TestClass::class, 'staticTest']);

                    $test = $callable->str();

                    expect($test)->toEqual('Test\TestClass::staticTest');

                });

            });

        });

        context('when the callable is an object method', function () {

            describe('->str()', function () {

                it('should return a string representation of the object method', function () {

                    $callable = new CallableAdapter([new Test\TestClass, 'test']);

                    $test = $callable->str();

                    expect($test)->toEqual('Test\TestClass::test');

                });

            });

        });

    });

    context('when the callable is a function name', function () {

        describe('->str()', function () {

            it('should return the function name', function () {

                if (! function_exists('test_function')) {
                    function test_function () {};
                }

                $callable = new CallableAdapter('test_function');

                $test = $callable->str();

                expect($test)->toEqual('test_function');

            });

        });

    });

});
