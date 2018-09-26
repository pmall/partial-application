<?php declare(strict_types=1);

namespace Quanta;

final class CallableAdapter implements PartialApplicationInterface
{
    private $callable;
    private $error;

    public function __construct(callable $callable, UndefinedArgumentErrorInterface $error)
    {
        $this->callable = $callable;
        $this->error = $error;
    }

    public function __invoke(...$xs)
    {
        $undefined = array_filter($xs, [$this, 'isUndefined']);

        if (count($undefined) == 0) {
            return ($this->callable)(...$xs);
        }

        $msg = $this->error->message($undefined);

        throw new \ArgumentCountError($msg);
    }

    private function isUndefined($x): bool
    {
        return $x === Undefined::class;
    }
}
