<?php declare(strict_types=1);

namespace Quanta;

final class PartialApplication
{
    /**
     * The bound callable.
     *
     * @var callable
     */
    private $callable;

    /**
     * Constructor.
     *
     * Automatically wrap a CallableAdapter around the given callable and bind
     * it with the given argument. This can't fail and it is transparent for the
     * end user.
     *
     * @param callable  $callable
     * @param mixed     ...$xs
     */
    public function __construct(callable $callable, ...$xs)
    {
        $this->callable = $this->bound(new CallableAdapter($callable), $xs);
    }

    /**
     * Invoke the bound callable with the given argument.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs)
    {
        return ($this->callable)(...$xs);
    }

    /**
     * Recursively bind the given callable to the given arguments.
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
