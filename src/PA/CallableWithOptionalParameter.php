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
     * The callable first placeholder.
     *
     * @var string
     */
    private $placeholder;

    /**
     * The callable first placeholder default value.
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
    public function __construct(CallableInterface $callable, string $placeholder, $default)
    {
        $this->callable = $callable;
        $this->placeholder = $placeholder;
        $this->default = $default;
    }

    /**
     * @inheritdoc
     */
    public function placeholders(bool $optional = false): PlaceholderSequence
    {
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
