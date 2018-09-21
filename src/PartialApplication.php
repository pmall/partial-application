<?php declare(strict_types=1);

namespace Quanta;

final class PartialApplication extends AbstractPartialApplication
{
    public function __construct(PartialApplicationInterface $callable, ...$xs)
    {
        parent::__construct($callable, ...$xs);
    }
}
