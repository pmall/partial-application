<?php declare(strict_types=1);

namespace Quanta;

final class Instantiation
{
    private $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function __invoke(...$xs)
    {
        return new $this->class(...$xs);
    }
}
