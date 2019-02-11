<?php declare(strict_types=1);

namespace Quanta;

use Quanta\PA\CallableAdapter;
use Quanta\PA\CallableInterface;
use Quanta\PA\ConstructorAdapter;
use Quanta\PA\CallableWithArgument;
use Quanta\PA\CallableWithRequiredParameter;

final class PartialApplication
{
    /**
     * The callable to invoke.
     *
     * @var \Quanta\PA\CallableInterface
     */
    private $callable;

    /**
     * The bound arguments.
     *
     * @var array $arguments
     */
    private $arguments;

    /**
     * Return a partial application of the given callable.
     *
     * @param callable  $callable
     * @param mixed     ...$arguments
     * @return \Quanta\PartialApplication
     */
    public static function fromCallable(callable $callable, ...$arguments): PartialApplication
    {
        return new PartialApplication(new CallableAdapter($callable), ...$arguments);
    }

    /**
     * Return a partial application of the constructor of the given class.
     *
     * @param string    $class
     * @param mixed     ...$arguments
     * @return \Quanta\PartialApplication
     */
    public static function fromClass(string $class, ...$arguments): PartialApplication
    {
        return new PartialApplication(new ConstructorAdapter($class), ...$arguments);
    }

    /**
     * Constructor.
     *
     * @param \Quanta\PA\CallableInterface  $callable
     * @param mixed                         ...$arguments
     */
    public function __construct(CallableInterface $callable, ...$arguments)
    {
        $this->callable = $callable;
        $this->arguments = $arguments;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        $bound = array_reduce($this->arguments, [$this, 'bound'], $this->callable);

        if (count($xs) >= $bound->placeholders()->number()) {
            return $bound(...$xs);
        }

        throw new \ArgumentCountError(
            vsprintf('Too few arguments to partial application of function %s() %s passed and exactly %s expected', [
                $bound->str(),
                count($xs),
                $bound->placeholders()->number(),
            ])
        );
    }

    /**
     * Bind the given callable to the given argument.
     *
     * Return a CallableWithRequiredParameter when the argument is
     * 'Quanta\Placeholder'.
     *
     * @param \Quanta\PA\CallableInterface  $callable
     * @param mixed                         $argument
     * @return \Quanta\PA\CallableInterface
     */
    private function bound(CallableInterface $callable, $argument): CallableInterface
    {
        return $argument !== Placeholder::class
            ? new CallableWithArgument($callable, $argument)
            : new CallableWithRequiredParameter($callable, 'p');
    }
}
