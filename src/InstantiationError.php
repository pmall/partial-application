<?php declare(strict_types=1);

namespace Quanta;

final class InstantiationError implements UndefinedArgumentErrorInterface
{
    private $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function message(array $undefined): string
    {
        $tpl = count($undefined) == 1
            ? "Unable to instantiate class '%s' because %s argument is undefined"
            : "Unable to instantiate class '%s' because %s arguments are undefined";

        return sprintf($tpl, $this->class, count($undefined));
    }
};
