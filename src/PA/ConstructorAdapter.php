<?php declare(strict_types=1);

namespace Quanta\PA;

final class ConstructorAdapter implements CallableInterface
{
    /**
     * The name of the class to instantiate.
     *
     * @var string
     */
    private $class;

    /**
     * Constructor.
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @inheritdoc
     */
    public function parameters(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        return new $this->class(...$xs);
    }

    /**
     * @inheritdoc
     */
    public function str(): string
    {
        return sprintf('%s::__construct', new ClassName($this->class));
    }
}
