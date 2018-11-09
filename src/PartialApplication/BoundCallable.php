<?php declare(strict_types=1);

namespace Quanta\PartialApplication;

use Quanta\Placeholder;
use Quanta\Exceptions\ArgumentCountErrorMessage;

final class BoundCallable implements BoundCallableInterface
{
    /**
     * The callable to invoke.
     *
     * @var \Quanta\PartialApplication\BoundCallableInterface
     */
    private $callable;

    /**
     * The the argument bound to the callable.
     *
     * @var mixed
     */
    private $x;

    /**
     * Constructor.
     *
     * @param \Quanta\PartialApplication\BoundCallableInterface $callable
     * @param mixed                                             $x
     */
    public function __construct(BoundCallableInterface $callable, $x)
    {
        $this->callable = $callable;
        $this->x = $x;
    }

    /**
     * @inheritdoc
     */
    public function expected(): int
    {
        $expected = $this->callable->expected();

        return $this->x === Placeholder::class ? $expected + 1 : $expected;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(...$xs)
    {
        // number of expected arguments === number of remaining placeholders.
        $expected = $this->expected();

        // this argument position within the given arguments is the number of
        // expected arguments.
        if ($this->x !== Placeholder::class) {
            array_splice($xs, $expected, 0, [$this->x]);
        }

        // fail when there is less arguments than the number of expected
        // arguments.
        if (count($xs) >= $expected) {
            return ($this->callable)(...$xs);
        }

        throw new \ArgumentCountError(
            (string) new ArgumentCountErrorMessage(
                $this->expected(), count($xs)
            )
        );
    }
}
