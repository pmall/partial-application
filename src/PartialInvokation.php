<?php declare(strict_types=1);

namespace Quanta;

final class PartialInvokation extends AbstractPartialApplication
{
    public function __construct(callable $callable, ...$xs)
    {
        $error = new InvokationError($callable);

        $partial = new CallableAdapter($callable, $error);

        parent::__construct($partial, ...$xs);
    }
}
