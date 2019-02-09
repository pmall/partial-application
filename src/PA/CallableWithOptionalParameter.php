<?php declare(strict_types=1);

namespace Quanta\PA;

use Quanta\Placeholder;

final class CallableWithOptionalParameter implements CallableInterface
{
    /**
     * The callable to invoke.
     *
     * @var \Quanta\PA\CallableInterface
     */
    private $callable;

    /**
     * The callable first parameter name.
     *
     * @var string
     */
    private $parameter;

    /**
     * The callable first parameter default value.
     *
     * @var mixed
     */
    private $default;

    /**
     * Constructor.
     *
     * @param \Quanta\PA\CallableInterface  $callable
     * @param string                        $parameter
     * @param mixed                         $default
     */
    public function __construct(CallableInterface $callable, string $parameter, $default)
    {
        $this->callable = $callable;
        $this->parameter = $parameter;
        $this->default = $default;
    }

    /**
     * @inheritdoc
     */
    public function parameters(): array
    {
        $parameters = $this->callable->parameters();

        return array_merge($parameters, [$this->parameter]);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $position = count($this->callable->parameters());

        $xs = array_pad($xs, $position + 1, Placeholder::class);

        if ($xs[$position] == Placeholder::class) {
            $xs[$position] = $this->default;
        }

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
