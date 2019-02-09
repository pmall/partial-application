<?php declare(strict_types=1);

namespace Quanta\PA;

use Quanta\Placeholder;

final class UnboundCallable implements CallableInterface
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
            throw new \ArgumentCountError(
                $this->missingArgumentErrorMessage(...$xs)
            );
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

    /**
     * Prefix the given parameter name with '$'.
     *
     * @param string $name
     * @return string
     */
    private function prefixedParameterName(string $name): string
    {
        return '$' . $name;
    }

    /**
     * Return the message of the exception thrown when an argument is missing.
     *
     * @return string
     */
    private function missingArgumentErrorMessage(...$xs): string
    {
        $missing = array_intersect_key(
            $this->callable->parameters(),
            array_filter($xs, [$this, 'isPlaceholder'])
        );

        if (count($missing) > 0) {
            return vsprintf('No argument bound to parameters %s and $%s of function %s()', [
                implode(', ', array_map([$this, 'prefixedParameterName'], $missing)),
                $this->parameter,
                $this->callable->str(),
            ]);
        }

        return vsprintf('No argument bound to parameter $%s of function %s()', [
            $this->parameter,
            $this->callable->str(),
        ]);
    }
}
