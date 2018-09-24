<?php declare(strict_types=1);

namespace Quanta;

abstract class AbstractPartialApplication implements PartialApplicationInterface
{
    /**
     * The callable to execute.
     *
     * @var callable
     */
    private $callable;

    /**
     * Constructor.
     *
     * @param callable  $callable
     * @param mixed     ...$xs
     */
    public function __construct(callable $callable, ...$xs)
    {
        $this->callable = $this->bound($callable, $xs);
    }

    /**
     * Return the value produced by the bound callable with the given arguments.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs)
    {
        return ($this->callable)(...$xs);
    }

    /**
     * Return the given callable bound to the first argument of the given array
     * of arguments.
     *
     * @param callable  $callable
     * @param array     $xs
     * @param int       $p
     * @return callable
     */
     private function bound(callable $callable, array $xs, int $p = 0): callable
     {
         if (count($xs) > 0) {
             $x = array_shift($xs);

             return new BoundCallable($this->bound($callable, $xs, $p + 1), $x, $p);
         }

         return $callable;
     }
}
