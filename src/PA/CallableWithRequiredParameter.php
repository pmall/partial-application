<?php declare(strict_types=1);

namespace Quanta\PA;

use Quanta\Placeholder;

final class CallableWithRequiredParameter implements CallableInterface
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
     * Constructor.
     *
     * @param \Quanta\PA\CallableInterface  $callable
     * @param string                        $parameter
     */
    public function __construct(CallableInterface $callable, string $parameter)
    {
        $this->callable = $callable;
        $this->parameter = $parameter;
    }

    /**
     * @inheritdoc
     */
    public function parameters(bool $optional = false): ParameterCollection
    {
        return $this->callable->parameters(true)->with($this->parameter);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $offset = $this->callable->parameters(true)->number();

        $xs = array_pad($xs, $offset + 1, Placeholder::class);

        if ($xs[$offset] !== Placeholder::class) {
            return ($this->callable)(...$xs);
        }

        throw new \ArgumentCountError(
            vsprintf('No argument bound to %s of function %s()', [
                $this->parameters()->only(...array_keys(
                    array_filter($xs, [$this, 'isPlaceholder'])
                )),
                $this->callable->str(),
            ])
        );
    }

    /**
     * @inheritdoc
     */
    public function str(): string
    {
        return $this->callable->str();
    }

    /**
     * Return whether the given value is a placeholder.
     *
     * @param mixed $x
     * @return bool
     */
    private function isPlaceholder($x): bool
    {
        return $x === Placeholder::class;
    }
}
