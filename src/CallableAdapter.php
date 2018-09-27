<?php declare(strict_types=1);

namespace Quanta;

final class CallableAdapter
{
    /**
     * The callable to invoke.
     *
     * @var callable
     */
    private $callable;

    /**
     * Constructor.
     *
     * @param callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * Invoke the callable with the given arguments.
     *
     * An ArgumentCountError is thrown when some arguments are undefined.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs)
    {
        $undefined = array_filter($xs, [$this, 'isUndefined']);

        if (count($undefined) == 0) {
            return ($this->callable)(...$xs);
        }

        $tpl = count($undefined) == 1
            ? 'Unable to invoke %s because %s argument is undefined'
            : 'Unable to invoke %s because %s arguments are undefined';

        throw new \ArgumentCountError(vsprintf($tpl, [
            new Printable($this->callable, true),
            count($undefined),
        ]));
    }

    /**
     * Return whether the given argument is undefined.
     *
     * @param mixed $x
     * @return bool
     */
    private function isUndefined($x): bool
    {
        return $x === Undefined::class;
    }
}
