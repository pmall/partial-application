<?php declare(strict_types=1);

namespace Quanta;

use ArgumentCountError;

final class PartialApplication
{
    /**
     * The callable to execute.
     *
     * @var callable
     */
    private $callable;

    /**
     * The list of arguments bound to the callable.
     *
     * @var array
     */
    private $xs;

    /**
     * Constructor.
     *
     * @param callable  $callable
     * @param mixed     ...$xs
     */
    public function __construct(callable $callable, ...$xs)
    {
        $this->callable = $callable;
        $this->xs = $xs;
    }

    /**
     * Return the value produced by the callable with the given arguments
     * completed with the bound arguments.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs)
    {
        $required = count(array_filter($this->xs, [$this, 'isPlaceholder']));

        if (count($xs) >= $required) {
            return $this->bound($this->callable)(...$xs);
        }

        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);

        $tpl = 'Too few arguments to partial application of %s, %s passed in %s on line %s and exactly %s expected';

        throw new ArgumentCountError(vsprintf($tpl, [
            new Printable($this->callable, true),
            count($xs),
            $bt[0]['file'],
            $bt[0]['line'],
            $required,
        ]));
    }

    /**
     * Return whether the given argument is a placeholder.
     *
     * @param mixed $x
     * @return bool
     */
    private function isPlaceholder($x): bool
    {
        return $x === Placeholder::class;
    }

    /**
     * Bind the given arguments to the given callable.
     *
     * @param callable  $callable
     * @param array     $xs
     * @param int       $p
     * @param int       $o
     * @return callable
     */
    private function bound(callable $callable, int $p = 0, int $o = 0): callable
    {
        if ($p < count($this->xs)) {
            return $this->xs[$p] === Placeholder::class
                ? $this->bound($callable, ++$p, ++$o)
                : $this->bound(new BoundCallable($callable, $this->xs[$p], $o), ++$p, $o);
        }

        return $callable;
    }
}
