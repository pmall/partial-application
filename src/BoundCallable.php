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
     * The the argument bound to the callable parameter.
     *
     * @var mixed
     */
    private $x;

    /**
     * The position of the callable parameter to bind to the argument.
     *
     * @var int
     */
    private $position;

    /**
     * Constructor.
     *
     * @param callable  $callable
     * @param mixed     $x
     * @param int       $position
     */
    public function __construct(callable $callable, $x, int $position = 0)
    {
        $this->callable = $callable;
        $this->x = $x;
        $this->position = $position;
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

        if ($given >= $this->position) {
            array_splice($xs, $this->position, 0, [$this->x]);

            return ($this->callable)(...$xs);
        }

        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);

        $tpl = 'Too few arguments to partial application of %s, %s passed in %s on line %s and exactly %s expected';

        throw new ArgumentCountError(vsprintf($tpl, [
            new Printable($this->callable, true),
            $given,
            $bt[0]['file'],
            $bt[0]['line'],
            $this->position,
        ]));
    }
}
