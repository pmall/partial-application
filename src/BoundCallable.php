<?php declare(strict_types=1);

namespace Quanta;

use ArgumentCountError;

final class BoundCallable
{
    /**
     * The callable to execute.
     *
     * @var callable
     */
    private $callable;

    /**
     * The the argument bound to the callable.
     *
     * @var mixed
     */
    private $x;

    /**
     * The number of placeholders before the argument.
     *
     * @var int
     */
    private $offset;

    /**
     * Constructor.
     *
     * @param callable  $callable
     * @param mixed     $x
     * @param int       $offset
     */
    public function __construct(callable $callable, $x, int $offset = 0)
    {
        $this->callable = $callable;
        $this->x = $x;
        $this->offset = $offset;
    }

    /**
     * Return the value produced by the callable with the given arguments
     * completed with the bound argument.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs)
    {
        $given = count($xs);

        if ($given >= $this->offset) {
            array_splice($xs, $this->offset, 0, [$this->x]);

            return ($this->callable)(...$xs);
        }

        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);

        $tpl = 'Too few arguments to partial application of %s, %s passed in %s on line %s and exactly %s expected';

        throw new ArgumentCountError(vsprintf($tpl, [
            new Printable($this->callable, true),
            $given,
            $bt[0]['file'],
            $bt[0]['line'],
            $this->offset,
        ]));
    }
}
