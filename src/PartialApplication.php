<?php declare(strict_types=1);

namespace Quanta;

final class PartialApplication extends AbstractPartialApplication
{
    /**
     * Constructor.
     *
     * Concrete implementation of AbstractPartialApplication.
     *
     * @param \Quanta\PartialApplicationInterface   $callable
     * @param mixed                                 ...$xs
     */
    public function __construct(PartialApplicationInterface $callable, ...$xs)
    {
        parent::__construct($callable, ...$xs);
    }

    /**
     * Here to put PartialApplication instead of AbstractPartialApplication in
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
