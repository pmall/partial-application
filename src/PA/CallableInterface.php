<?php declare(strict_types=1);

namespace Quanta\PA;

interface CallableInterface
{
    /**
     * Return the callable parameter names.
     *
     * @return string[]
     */
    public function parameters(): array;

    /**
     * Return the callable required parameter names.
     *
     * @return string[]
     */
    public function required(): array;

    /**
     * Invoke the callable with the given arguments.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs);

    /**
     * Return a string representation of the callable.
     *
     * @return string str
     */
    public function str(): string;
}
