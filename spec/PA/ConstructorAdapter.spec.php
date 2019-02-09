<?php

use Quanta\PA\CallableInterface;
use Quanta\PA\ConstructorAdapter;
use Quanta\PA\ParameterCollection;

require_once __DIR__ . '/../.test/classes.php';

describe('ConstructorAdapter', function () {

    beforeEach(function () {

        $this->callable = new ConstructorAdapter(Test\TestClass::class);

    });

    it('should implement CallableInterface', function () {

        expect($this->callable)->toBeAnInstanceOf(CallableInterface::class);

    });

    describe('->parameters()', function () {

        it('should return an empty array', function () {

            $test = $this->callable->parameters();

            expect($test)->toEqual(new ParameterCollection);

        });

    });

    describe('->__invoke()', function () {

        it('should instantiate the class with the given arguments', function () {

            $test = ($this->callable)('value1', 'value2', 'value3');

            expect($test)->toEqual(new Test\TestClass('value1', 'value2', 'value3'));

        });

    });

    describe('->str()', function () {

        it('should return a string representation of the class constructor', function () {

            $test = $this->callable->str();

            expect($test)->toEqual('Test\TestClass::__construct');

        });

    });

});
