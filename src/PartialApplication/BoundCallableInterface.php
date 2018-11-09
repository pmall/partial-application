<?php declare(strict_types=1);

namespace Quanta\PartialApplication;

interface BoundCallableInterface
{
    /**
     * Return the expected number of argument.
     *
     * @return int
     */
    public function expected(): int;

    /**
     * Invoke the callable.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs);
}
