<?php declare(strict_types=1);

namespace Quanta;

final class CallableAdapter implements PartialApplicationInterface
{
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function __invoke(...$xs)
    {
        $undefined = count(array_filter($xs, function ($x) {
            return $x === Undefined::class;
        }));

        if ($undefined == 0) {
            return ($this->callable)(...$xs);
        }

        $tpl = $undefined == 1
            ? 'unable to execute %s because %s argument is undefined'
            : 'unable to execute %s because %s arguments are undefined';

        throw new \ArgumentCountError(vsprintf($tpl, [
            new Printable($this->callable, true),
            $undefined,
        ]));
    }
}
