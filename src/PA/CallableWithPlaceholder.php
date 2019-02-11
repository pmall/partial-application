<?php declare(strict_types=1);

namespace Quanta\PA;

use Quanta\Placeholder;

final class CallableWithPlaceholder implements CallableInterface
{
    /**
     * The callable to invoke.
     *
     * @var \Quanta\PA\CallableInterface
     */
    private $callable;

    /**
     * The placeholder name.
     *
     * @var string
     */
    private $placeholder;

    /**
     * The default argument for the placeholder.
     *
     * @var mixed
     */
    private $default;

    /**
     * Constructor.
     *
     * @param \Quanta\PA\CallableInterface  $callable
     * @param string                        $placeholder
     * @param mixed                         $default
     */
    public function __construct(
        CallableInterface $callable,
        string $placeholder,
        $default = Placeholder::class
    ) {
        $this->callable = $callable;
        $this->placeholder = $placeholder;
        $this->default = $default;
    }

    /**
     * @inheritdoc
     */
    public function placeholders(bool $optional = false): PlaceholderSequence
    {
        if ($this->default === Placeholder::class) {
            return $this->callable->placeholders(true)->with($this->placeholder);
        }

        $placeholders = $this->callable->placeholders($optional);

        return $optional
            ? $placeholders->with($this->placeholder)
            : $placeholders;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $offset = $this->callable->placeholders(true)->number();

        $xs = array_pad($xs, $offset + 1, Placeholder::class);

        if ($xs[$offset] === Placeholder::class) {
            $xs[$offset] = $this->default;
        }

        if ($xs[$offset] !== Placeholder::class) {
            return ($this->callable)(...$xs);
        }

        throw new \ArgumentCountError(
            vsprintf('No argument given for placeholders [%s] of partial application of function %s()', [
                $this->placeholders()->unbound(...$xs),
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
}
