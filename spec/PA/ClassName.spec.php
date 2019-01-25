<?php

use Quanta\PA\ClassName;

describe('ClassName', function () {

    context('when the name is not one of an anonymous class', function () {

        describe('->__toString()', function () {

            it('should return the class name', function () {

                $class = new ClassName(Test\TestClass::class);

                expect((string) $class)->toEqual(Test\TestClass::class);

            });

        });

    });

    context('when the name is the one of an anonymous class', function () {

        describe('->__toString()', function () {

            it('should return class@anonymous', function () {

                $class = new ClassName(get_class(new class {}));

                expect((string) $class)->toEqual('class@anonymous');

            });

        });

    });

});
