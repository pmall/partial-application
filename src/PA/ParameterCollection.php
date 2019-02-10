<?php declare(strict_types=1);

namespace Quanta\PA;

final class ParameterCollection
{
    /**
     * The parameters.
     *
     * @var string ...$parameters
     */
    private $parameters;

    /**
     * Constructor.
     *
     * @param string ...$parameters
     */
    public function __construct(string ...$parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Return the number of parameters.
     *
     * @return int
     */
    public function number(): int
    {
        return count($this->parameters);
    }

    /**
     * Return the parameter names formatted with the given sprintf format.
     *
     * @param string $format
     * @return string[]
     */
    public function names(string $format = '%s'): array
    {
        return array_map('sprintf', ...[
            array_pad([], count($this->parameters), $format),
            $this->parameters,
        ]);
    }

    /**
     * Return a new parameter collection containing only the parameters at the
     * given positions.
     *
     * @param int ...$positions
     * @return \Quanta\PA\ParameterCollection
     */
    public function only(int ...$positions): ParameterCollection
    {
        return new ParameterCollection(...array_values(
            array_intersect_key($this->parameters, array_flip($positions))
        ));
    }

    /**
     * Return a new parameter collection containing only the first n parameters.
     *
     * @param int $n
     * @return \Quanta\PA\ParameterCollection
     */
    public function head(int $n): ParameterCollection
    {
        return new ParameterCollection(
            ...array_slice($this->parameters, 0, $n)
        );
    }

    /**
     * Return a new parameter collection containing only the last n parameters.
     *
     * @param int $n
     * @return \Quanta\PA\ParameterCollection
     */
    public function tail(int $n): ParameterCollection
    {
        $xs = $n == 0 ? [-1 * $n, 0] : [-1 * $n];

        return new ParameterCollection(
            ...array_slice($this->parameters, ...$xs)
        );
    }

    /**
     * Return a new parameter collection containing the parameters and the given
     * parameters.
     *
     * @param string ...$parameters
     * @return \Quanta\PA\ParameterCollection
     */
    public function with(string ...$parameters): ParameterCollection
    {
        return new ParameterCollection(...$this->parameters, ...$parameters);
    }

    /**
     * Return the default string representation of the parameter collection.
     *
     * @return string
     */
    public function __toString()
    {
        if (count($this->parameters) == 0) {
            return '';
        }

        if (count($this->parameters) == 1) {
            return vsprintf('parameter %s', $this->names('$%s'));
        }

        return vsprintf('parameters %s and %s', [
            implode(', ', $this->head(-1)->names('$%s')),
            current($this->tail(1)->names('$%s')),
        ]);
    }
}
