<?php declare(strict_types=1);

namespace Quanta;

use ArgumentCountError;

final class BoundCallable
{
    /**
     * The callable to execute.
     *
     * @var callable
     */
    private $callable;

    /**
     * The the argument bound to the callable.
     *
     * @var mixed
     */
    private $x;

    /**
     * The position of the bound argument.
     *
     * @var int
     */
    private $position;

    /**
     * Constructor.
     *
     * @param callable  $callable
     * @param mixed     $x
     * @param int       $position
     */
    public function __construct(callable $callable, $x, int $position)
    {
        $this->callable = $callable;
        $this->x = $x;
        $this->position = $position;
    }

    /**
     * Return the value produced by the callable.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs)
    {
        $is_placeholder = $this->x === Placeholder::class;

        $offset = $is_placeholder ? $this->position + 1 : $this->position;

        $xs = array_pad($xs, $offset, Undefined::class);

        if (! $is_placeholder) {
            array_splice($xs, $this->position, 0, [$this->x]);
        }

        return ($this->callable)(...$xs);
    }
}
