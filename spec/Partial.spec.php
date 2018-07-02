<?php

use Quanta\Partial;

describe('Partial', function () {

    it('should call the callable with the argument and the given arguments', function () {

        $callable = function ($p1, $p2, $p3) {
            return sprintf('test: %s, %s, %s', $p1, $p2, $p3);
        };

        $partial = new Partial($callable, 'p1');

        $test = $partial('p2', 'p3');

        expect($test)->toEqual('test: p1, p2, p3');

    });

});
