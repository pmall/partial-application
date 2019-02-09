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
     * Return a new parameter collection with only the parameters at the given
     * positions.
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
     * Return a new parameter collection with the given parameters.
     *
     * @param string ...$parameters
     * @return \Quanta\PA\ParameterCollection
     */
    public function with(string ...$parameters): ParameterCollection
    {
        return new ParameterCollection(...$this->parameters, ...$parameters);
    }

    /**
     * Return a string representation of the parameter collection using the
     * given sprintf format.
     *
     * @param string $format
     * @return string
     */
    public function str(string $format = '$%s'): string
    {
        if (count($this->parameters) == 0) {
            return '';
        }

        if (count($this->parameters) == 1) {
            return vsprintf('parameter %s', $this->names($format));
        }

        $prev = new ParameterCollection(...array_slice($this->parameters, 0, -1));
        $last = new ParameterCollection(...array_slice($this->parameters, -1));

        return vsprintf('parameters %s and %s', [
            implode(', ', $prev->names($format)),
            current($last->names($format)),
        ]);
    }

    /**
     * Return the default string representation of the parameter collection.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->str('$%s');
    }
}
