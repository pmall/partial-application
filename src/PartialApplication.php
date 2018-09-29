<?php declare(strict_types=1);

namespace Quanta;

final class PartialApplication
{
    /**
     * The bound callable to invoke.
     *
     * @var \Quanta\BoundCallableInterface
     */
    private $callable;

    /**
     * Constructor.
     *
     * Automatically wrap a CallableAdapter around the given callable and bind
     * it with the given arguments using BoundCallable.
     *
     * It is done in the container because this class is meant to be an helper
     * to easily build the BoundCallable recursive structure.
     *
     * It is allowed because it is transparent for the end user and can't fail.
     *
     * @param callable  $callable
     * @param mixed     ...$xs
     */
    public function __construct(callable $callable, ...$xs)
    {
        $this->callable = array_reduce($xs, [$this, 'bound'], new CallableAdapter($callable));
    }

    /**
     * Invoke the bound callable with the given arguments.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs)
    {
        // fail when there is less given arguments than the number of expected
        // arguments (=== total number of placeholders).
        if (count($xs) >= $this->callable->expected()) {
            return ($this->callable)(...$xs);
        }

        // simulate an ArgumentCountError.
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);

        throw new \ArgumentCountError((string) new ArgumentCountErrorStr(...[
            $this->callable->str(),
            count($xs),
            $this->callable->expected(),
            $bt[0]['file'],
            $bt[0]['line'],
        ]));
    }

    /**
     * Return a BoundCallable from the given callable and the given argument.
     *
     * @param \Quanta\BoundCallableInterface    $callable
     * @param mixed                             $x
     * @return \Quanta\BoundCallableInterface
     */
     private function bound(BoundCallableInterface $callable, $x): BoundCallableInterface
     {
         return new BoundCallable($callable, $x);
     }
}
