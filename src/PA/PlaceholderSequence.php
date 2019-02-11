<?php declare(strict_types=1);

namespace Quanta\PA;

use Quanta\Placeholder;

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
     * Return a new placeholder sequence containing only the placeholders
     * starting from the given position.
     *
     * @param int $position
     * @return \Quanta\PA\PlaceholderSequence
     */
    public function from(int $position): PlaceholderSequence
    {
        return new PlaceholderSequence(
            ...array_slice($this->placeholders, $position)
        );
    }

    /**
     * Return a new placeholder sequence containing only the placeholders at the
     * same position as the given unbound arguments.
     *
     * @param mixed ...$xs
     * @return \Quanta\PA\PlaceholderSequence
     */
    public function unbound(...$xs): PlaceholderSequence
    {
        return new PlaceholderSequence(...array_values(
            array_intersect_key($this->placeholders, ...[
                array_filter($xs, [$this, 'isPlaceholder'])
            ])
        ));
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

    /**
     * Return whether the given value is a placeholder.
     *
     * @param mixed $x
     * @return bool
     */
    private function isPlaceholder($x): bool
    {
        return $x === Placeholder::class;
    }
}
