<?php declare(strict_types=1);

namespace Quanta\PA;

final class ClassName
{
    /**
     * The class name.
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
     * Return a string representation of the object class name.
     *
     * @return string
     */
    public function __toString()
    {
        return preg_match('/^class@anonymous/', $this->class) === 1
            ? 'class@anonymous'
            : $this->class;
    }
}
