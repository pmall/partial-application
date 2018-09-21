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
     * Return the given callable bound to the first argument of the given list.
     *
     * @param callable  $callable
     * @param array     $xs
     * @param int       $i
     * @param int       $o
     * @return callable
     */
     private function bound(callable $callable, array $xs, int $i = 0, int $o = 0): callable
     {
         if (count($xs) > 0) {
             $x = array_shift($xs);

             return $x === Placeholder::class
                 ? $this->bound($callable, $xs, ++$i, ++$o)
                 : $this->bound(new BoundCallable($callable, $x, $o), $xs, ++$i, $o);
         }

         return $callable;
     }
}
