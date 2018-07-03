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
     * The predefined arguments.
     *
     * @var array
     */
    private $arguments;

    /**
     * Constructor.
     *
     * @param callable  $callable
     * @param mixed     ...$arguments
     */
    public function __construct(callable $callable, ...$arguments)
    {
        $this->callable = $callable;
        $this->arguments = $arguments;
    }

    /**
     * Call the callable with the predefined arguments and the given arguments.
     *
     * @param mixed ...$args
     * @return mixed
     */
     public function __invoke(...$arguments)
     {
         return ($this->callable)(...$this->arguments, ...$arguments);
     }
}
