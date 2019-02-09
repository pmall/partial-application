<?php declare(strict_types=1);

namespace Quanta\PA;

final class CallableAdapter implements CallableInterface
{
    /**
     * The callable to invoke.
     *
     * @var callable
     */
    private $callable;

    /**
     * Constructor.
     *
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @inheritdoc
     */
    public function parameters(bool $optional = false): ParameterCollection
    {
        return new ParameterCollection;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        return ($this->callable)(...$xs);
    }

    /**
     * @inheritdoc
     */
    public function str(): string
    {
        if (is_object($this->callable)) {
            $class = get_class($this->callable);

            return $class != 'Closure'
                ? sprintf('%s::__invoke', new ClassName($class))
                : '{closure}';
        }

        if (is_array($this->callable)) {
            $class = is_object($this->callable[0])
                ? new ClassName(get_class($this->callable[0]))
                : $this->callable[0];

            return sprintf('%s::%s', $class, $this->callable[1]);
        }

        return strval($this->callable);
    }
}
