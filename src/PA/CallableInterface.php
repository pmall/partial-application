<?php declare(strict_types=1);

namespace Quanta\PA;

interface CallableInterface
{
    /**
     * Return the callable parameters.
     *
     * When $optional is set to true the optional parameters must be included.
     *
     * @param bool $optional
     * @return \Quanta\PA\ParameterCollection
     */
    public function parameters(bool $optional = false): ParameterCollection;

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
