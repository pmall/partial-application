<?php declare(strict_types=1);

namespace Quanta;

use Quanta\PA\Constructor;

final class PartialInstantiation extends AbstractPartialApplication
{
    /**
     * Constructor.
     *
     * @param string    $class
     * @param mixed     ...$arguments
     */
    public function __construct(string $class, ...$arguments)
    {
        parent::__construct(new Constructor($class), ...$arguments);
    }
}
