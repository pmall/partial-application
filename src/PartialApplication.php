<?php declare(strict_types=1);

namespace Quanta;

final class PartialApplication
{
    /**
     * The callable to execute.
     *
     * @var callable
     */
    private $callable;

    /**
     * The list of arguments which may contain placeholders.
     *
     * @var array
     */
    private $unbound;

    /**
     * The list of arguments bound to the callable parameters.
     *
     * @var array
     */
    private $bound;

    /**
     * Constructor.
     *
     * @param callable  $callable
     * @param array     $unbound
     * @param array     $bound
     */
    public function __construct(callable $callable, array $unbound = [], array $bound = [])
    {
        $this->callable = $callable;
        $this->unbound = array_values($unbound);
        $this->bound = $bound;
    }

    /**
     * Return an array of placeholder positions.
     *
     * @return array[int]
     */
    public function placeholders(): array
    {
        $placeholders = array_filter($this->unbound, [$this, 'isPlaceholder']);

        return array_keys($placeholders);
    }

    /**
     * Return the value produced by the callable using the partially applied
     * arguments completed with the given ones.
     *
     * @param mixed ...$xs
     * @return mixed
     */
    public function __invoke(...$xs)
    {
        if (count($this->unbound) > 0) {
            $is_placeholder = $this->isPlaceholder($this->unbound[0]);

            if (! $is_placeholder || count($xs) > 0) {
                $x = $is_placeholder ? array_shift($xs) : $this->unbound[0];

                $unbound = array_slice($this->unbound, 1);
                $bound = array_merge($this->bound, [$x]);

                $partial = new PartialApplication($this->callable, $unbound, $bound);

                return $partial(...$xs);
            }

            throw new PartialApplicationException($this->callable, count($this->bound));
        }

        return ($this->callable)(...$this->bound, ...$xs);
    }

    /**
     * Return whether the given argument is a placeholder.
     *
     * @param mixed
     * @return bool
     */
    private function isPlaceholder($argument): bool
    {
        return $argument == Placeholder::class;
    }
}
