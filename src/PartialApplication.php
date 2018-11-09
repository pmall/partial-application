<?php declare(strict_types=1);

namespace Quanta;

use Quanta\PartialApplication\BoundCallable;
use Quanta\PartialApplication\CallableAdapter;
use Quanta\PartialApplication\BoundCallableInterface;
use Quanta\Exceptions\ArgumentCountErrorMessage;

final class PartialApplication
{
    /**
     * The bound callable to invoke.
     *
     * @var \Quanta\PartialApplication\BoundCallableInterface
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
     * @throws \ArgumentCountError
     */
    public function __invoke(...$xs)
    {
        // fail when there is less given arguments than the number of expected
        // arguments (=== total number of placeholders).
        if (count($xs) >= $this->callable->expected()) {
            return ($this->callable)(...$xs);
        }

        throw new \ArgumentCountError(
            (string) new ArgumentCountErrorMessage(
                $this->callable->expected(), count($xs)
            )
        );
    }

    /**
     * Return a BoundCallable from the given callable and the given argument.
     *
     * @param \Quanta\PartialApplication\BoundCallableInterface $callable
     * @param mixed                                             $x
     * @return \Quanta\PartialApplication\BoundCallableInterface
     */
     private function bound(BoundCallableInterface $callable, $x): BoundCallableInterface
     {
         return new BoundCallable($callable, $x);
     }
}
