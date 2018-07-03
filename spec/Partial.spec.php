<?php

use Quanta\Partial;

describe('Partial', function () {

    it('should call the callable with the predefined arguments and the given arguments', function () {

        $callable = function ($p1, $p2, $p3, $p4) {
            return sprintf('test: %s, %s, %s, %s', $p1, $p2, $p3, $p4);
        };

        $partial = new Partial($callable, 'p1', 'p2');

        $test = $partial('p3', 'p4');

        expect($test)->toEqual('test: p1, p2, p3, p4');

    });

});
