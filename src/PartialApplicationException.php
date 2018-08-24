<?php declare(strict_types=1);

namespace Quanta;

use Exception;

final class PartialApplicationException extends Exception
{
    /**
     * Constructor.
     *
     * @param callable  $callable
     * @param int       $position
     */
    public function __construct(callable $callable, int $position)
    {
        $tpl = 'Partial execution of %s failed: no argument bound to placeholder at position %s.';

        $msg = sprintf($tpl, new Printable($callable, true), $position);

        parent::__construct($msg);
    }
}
