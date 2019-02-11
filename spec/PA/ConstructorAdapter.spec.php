<?php

use Quanta\PA\CallableInterface;
use Quanta\PA\ConstructorAdapter;
use Quanta\PA\PlaceholderSequence;

require_once __DIR__ . '/../.test/classes.php';

describe('ConstructorAdapter', function () {

    beforeEach(function () {

        $this->callable = new ConstructorAdapter(Test\TestClass::class);

    });

    it('should implement CallableInterface', function () {

        expect($this->callable)->toBeAnInstanceOf(CallableInterface::class);

    });

    describe('->placeholders()', function () {

        it('should return an empty placeholder sequence', function () {

            $test = $this->callable->placeholders();

            expect($test)->toEqual(new PlaceholderSequence);

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
