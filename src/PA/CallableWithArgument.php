<?php declare(strict_types=1);

namespace Quanta\PA;

use Quanta\Placeholder;

final class CallableWithArgument implements CallableInterface
{
    /**
     * The callable to invoke.
     *
     * @var \Quanta\PA\CallableInterface
     */
    private $callable;

    /**
     * The argument bound to the first parameter of the callable.
     *
     * @var mixed
     */
    private $argument;

    /**
     * Constructor.
     *
     * @param \Quanta\PA\CallableInterface  $callable
     * @param mixed                         $argument
     */
    public function __construct(CallableInterface $callable, $argument)
    {
        $this->callable = $callable;
        $this->argument = $argument;
    }

    /**
     * @inheritdoc
     */
    public function parameters(bool $optional = false): ParameterCollection
    {
        return $this->callable->parameters($optional);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $position = $this->callable->parameters(true)->number();

        $xs = array_pad($xs, $position, Placeholder::class);

        array_splice($xs, $position, 0, [$this->argument]);

        return ($this->callable)(...$xs);
    }

    /**
     * @inheritdoc
     */
    public function str(): string
    {
        return $this->callable->str();
    }
}
