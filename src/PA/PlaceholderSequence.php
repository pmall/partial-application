<?php declare(strict_types=1);

namespace Quanta\PA;

final class PlaceholderSequence
{
    /**
     * The placeholders.
     *
     * @var string[] $placeholders
     */
    private $placeholders;

    /**
     * Constructor.
     *
     * @param string ...$placeholders
     */
    public function __construct(string ...$placeholders)
    {
        $this->placeholders = $placeholders;
    }

    /**
     * Return the number of placeholders.
     *
     * @return int
     */
    public function number(): int
    {
        return count($this->placeholders);
    }

    /**
     * Return the placeholder names formatted with the given sprintf format.
     *
     * @param string $format
     * @return string[]
     */
    public function names(string $format = '%s'): array
    {
        return array_map('sprintf', ...[
            array_pad([], count($this->placeholders), $format),
            $this->placeholders,
        ]);
    }

    /**
     * Return a new placeholder sequence containing only the placeholders at the
     * given positions.
     *
     * @param int ...$positions
     * @return \Quanta\PA\PlaceholderSequence
     */
    public function only(int ...$positions): PlaceholderSequence
    {
        return new PlaceholderSequence(...array_values(
            array_intersect_key($this->placeholders, array_flip($positions))
        ));
    }

    /**
     * Return a new placeholder sequence containing only the parameters from the
     * given position.
     *
     * @param int $position
     * @return \Quanta\PA\PlaceholderSequence
     */
    public function from(int $position): PlaceholderSequence
    {
        $nb = count($this->placeholders);

        return $position < $nb
            ? $this->only(...range($position, $nb - 1))
            : new PlaceholderSequence;
    }

    /**
     * Return a new placeholder sequence containing the placeholders and the
     * given placeholders.
     *
     * @param string ...$placeholders
     * @return \Quanta\PA\PlaceholderSequence
     */
    public function with(string ...$placeholders): PlaceholderSequence
    {
        return new PlaceholderSequence(...$this->placeholders, ...$placeholders);
    }

    /**
     * Return a strign representation of the placeholder sequence with the given
     * format and separator.
     *
     * @param string $format
     * @param string $separator
     * @return string
     */
    public function str(string $format, string $separator = ', '): string
    {
        return implode($separator, $this->names($format));
    }

    /**
     * Return the default string representation of the placeholder sequence.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->str('\'%s\'', ', ');
    }
}
