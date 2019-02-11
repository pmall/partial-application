<?php declare(strict_types=1);

namespace Quanta\PA;

interface CallableInterface
{
    /**
     * Return the callable placeholder sequence.
     *
     * @param bool $optional Whether the optional placeholders should be included
     * @return \Quanta\PA\PlaceholderSequence
     */
    public function placeholders(bool $optional = false): PlaceholderSequence;

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
