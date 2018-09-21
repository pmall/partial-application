<?php declare(strict_types=1);

namespace Quanta;

final class PartialInstantiation extends AbstractPartialApplication
{
    public function __construct(string $class, ...$xs)
    {
        parent::__construct(new ClassAdapter($class), ...$xs);
    }
}
