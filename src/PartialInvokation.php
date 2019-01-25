<?php declare(strict_types=1);

namespace Quanta;

use Quanta\PA\CallableAdapter;

final class PartialInvokation extends AbstractPartialApplication
{
    /**
     * Constructor.
     *
     * @param callable  $callable
     * @param mixed     ...$arguments
     */
    public function __construct(callable $callable, ...$arguments)
    {
        parent::__construct(new CallableAdapter($callable), ...$arguments);
    }
}
