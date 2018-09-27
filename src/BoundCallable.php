<?php declare(strict_types=1);

namespace Quanta;

final class BoundCallable
{
    /**
     * The callable to invoke.
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
     * Invoke the callable with the given arguments + this bound argument when
     * it is not a placeholder.
     *
     * The callable expects this position + 1 arguments (positions are 0 based)
     * so when fewer arguments are given the list is completed with undefined.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs)
    {
        if ($this->x !== Placeholder::class) {
            array_splice($xs, $this->position, 0, [$this->x]);
        }

        $xs = array_pad($xs, $this->position + 1, Undefined::class);

        return ($this->callable)(...$xs);
    }
}
