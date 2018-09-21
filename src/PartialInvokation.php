<?php declare(strict_types=1);

namespace Quanta;

final class PartialInvokation extends AbstractPartialApplication
{
    public function __construct(callable $callable, ...$xs)
    {
        parent::__construct(new CallableAdapter($callable), ...$xs);
    }
}
