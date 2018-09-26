<?php declare(strict_types=1);

namespace Quanta;

final class PartialInstantiation extends AbstractPartialApplication
{
    public function __construct(string $class, ...$xs)
    {
        $callable = function (...$xs) use ($class) {
            return new $class(...$xs);
        };

        $error = new InstantiationError($class);

        $partial = new CallableAdapter($callable, $error);

        parent::__construct($partial, ...$xs);
    }
}
