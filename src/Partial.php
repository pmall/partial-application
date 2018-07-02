<?php declare(strict_types=1);

namespace Quanta;

class Partial
{
    /**
     * The callable.
     *
     * @var callable
     */
    private $callable;

    /**
     * The first argument.
     *
     * @var mixed
     */
    private $argument;

    /**
     * Constructor.
     *
     * @param callable  $callable
     * @param mixed     $argument
     */
    public function __construct(callable $callable, $argument)
    {
        $this->callable = $callable;
        $this->argument = $argument;
    }

    /**
     * Call the callable with the argument and the given arguments.
     *
     * @param mixed ...$args
     * @return mixed
     */
     public function __invoke(...$arguments)
     {
         return ($this->callable)($this->argument, ...$arguments);
     }
}
