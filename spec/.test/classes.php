<?php

namespace Test;

final class TestClass
{
    private $xs;

    public function __construct(...$xs)
    {
        $this->xs = $xs;
    }

    public static function staticTest()
    {
        //
    }

    public function test()
    {
        //
    }

    public function __invoke()
    {
        //
    }
}
