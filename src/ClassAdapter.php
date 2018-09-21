<?php declare(strict_types=1);

namespace Quanta;

final class ClassAdapter implements PartialApplicationInterface
{
    private $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function __invoke(...$xs)
    {
        $undefined = count(array_filter($xs, function ($x) {
            return $x === Undefined::class;
        }));

        if ($undefined == 0) {
            return new $this->class(...$xs);
        }

        $tpl = $undefined == 1
            ? 'unable to instantiate class %s because %s argument is undefined'
            : 'unable to instantiate class %s because %s arguments are undefined';

        throw new \ArgumentCountError(sprintf($tpl, $this->class, $undefined));
    }
}
