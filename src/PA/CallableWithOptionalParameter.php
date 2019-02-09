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
    public function parameters(bool $optional = false): ParameterCollection
    {
        $parameters = $this->callable->parameters($optional);

        return $optional ? $parameters->with($this->parameter): $parameters;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $offset = $this->callable->parameters(true)->number();

        $xs = array_pad($xs, $offset + 1, Placeholder::class);

        if ($xs[$offset] == Placeholder::class) {
            $xs[$offset] = $this->default;
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
