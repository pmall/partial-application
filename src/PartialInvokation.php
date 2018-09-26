<?php declare(strict_types=1);

namespace Quanta;

final class PartialInvokation extends AbstractPartialApplication
{
    /**
     * Constructor.
     *
     * Concrete implementation of AbstractPartialApplication proxying the given
     * callable.
     *
     * @param callable  $callable
     * @param mixed     ...$xs
     */
    public function __construct(callable $callable, ...$xs)
    {
        $error = new InvokationError($callable);

        $partial = new CallableAdapter($callable, $error);

        parent::__construct($partial, ...$xs);
    }

    /**
     * Here to put PartialInvokation instead of AbstractPartialApplication in
     * the stack trace.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs)
    {
        return parent::__invoke(...$xs);
    }
}
