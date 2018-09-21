<?php

use function Eloquent\Phony\Kahlan\stub;

use Quanta\Placeholder;
use Quanta\PartialInstantiation;

class PartialInstantiationTest
{
    private $xs;

    public function __construct(...$xs)
    {
        $this->xs = $xs;
    }
}

describe('PartialInstantiation', function () {

    beforeEach(function () {

        $this->callable = stub();

        $this->partial = new PartialInstantiation(PartialInstantiationTest::class, ...[
            'v1',
            Placeholder::class,
            Placeholder::class,
            'v4',
        ]);

    });

    describe('->__invoke()', function () {

        context('when enough arguments are given', function () {

            it('should return an instance of the class with the bound arguments completed with the given arguments', function () {

                $test = ($this->partial)('v2', 'v3', 'v5');

                expect($test)->toEqual(new PartialInstantiationTest('v1', 'v2', 'v3', 'v4', 'v5'));

            });

        });

        context('when not enough arguments are given', function () {

            it('should throw an ArgumentCountError', function () {

                $test = function () { ($this->partial)('v1'); };

                expect($test)->toThrow(new ArgumentCountError);

            });

        });

    });

});
