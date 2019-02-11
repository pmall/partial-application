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
     * The callable first placeholder name.
     *
     * @var string
     */
    private $placeholder;

    /**
     * Constructor.
     *
     * @param \Quanta\PA\CallableInterface  $callable
     * @param string                        $placeholder
     */
    public function __construct(CallableInterface $callable, string $placeholder)
    {
        $this->callable = $callable;
        $this->placeholder = $placeholder;
    }

    /**
     * @inheritdoc
     */
    public function placeholders(bool $optional = false): PlaceholderSequence
    {
        return $this->callable->placeholders(true)->with($this->placeholder);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $offset = $this->callable->placeholders(true)->number();

        $xs = array_pad($xs, $offset + 1, Placeholder::class);

        if ($xs[$offset] !== Placeholder::class) {
            return ($this->callable)(...$xs);
        }

        throw new \ArgumentCountError(
            vsprintf('No argument given for placeholders [%s] of partial application of function %s()', [
                $this->placeholders()->only(...array_keys(
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
