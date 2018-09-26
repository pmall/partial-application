<?php declare(strict_types=1);

namespace Quanta;

final class PartialInstantiation extends AbstractPartialApplication
{
    /**
     * Constructor.
     *
     * Concrete implementation of AbstractPartialApplication instantiating the
     * given class.
     *
     * @param string    $class
     * @param mixed     ...$xs
     */
    public function __construct(string $class, ...$xs)
    {
        $callable = new Instantiation($class);

        $error = new InstantiationError($class);

        $partial = new CallableAdapter($callable, $error);

        parent::__construct($partial, ...$xs);
    }

    /**
     * Here to put PartialInstantiation instead of AbstractPartialApplication in
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
