<?php declare(strict_types=1);

namespace Quanta;

final class InvokationError implements UndefinedArgumentErrorInterface
{
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function message(array $undefined): string
    {
        $tpl = count($undefined) == 1
            ? 'Unable to execute %s because %s argument is undefined'
            : 'Unable to execute %s because %s arguments are undefined';

        return vsprintf($tpl, [
            new Printable($this->callable, true),
            count($undefined),
        ]);
    }
};
