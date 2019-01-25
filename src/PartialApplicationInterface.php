<?php declare(strict_types=1);

namespace Quanta;

use Quanta\PA\Constructor;

interface PartialApplicationInterface
{
    /**
     * Invoke the partial application with the given arguments.
     *
     * @param mixed ...$xs
     * @return mixed
     * @throws \ArgumentCountError
     */
    public function __invoke(...$xs);
}
