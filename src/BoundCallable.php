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
     * The number of placeholders before the argument.
     *
     * @var int
     */
    private $offset;

    /**
     * Constructor.
     *
     * @param callable  $callable
     * @param mixed     $x
     * @param int       $offset
     */
    public function __construct(callable $callable, $x, int $offset = 0)
    {
        $this->callable = $callable;
        $this->x = $x;
        $this->offset = $offset;
    }

    /**
     * Return the value produced by the callable.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs)
    {
        if (count($xs) < $this->offset) {
            $xs = array_pad($xs, $this->offset, Undefined::class);
        }

        array_splice($xs, $this->offset, 0, [$this->x]);

        return ($this->callable)(...$xs);
    }
}
