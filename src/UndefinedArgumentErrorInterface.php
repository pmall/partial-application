<?php declare(strict_types=1);

namespace Quanta;

interface UndefinedArgumentErrorInterface
{
    /**
     * Return the error message of the ArgumentCountError thrown when some
     * arguments are undefined.
     *
     * An array of the undefined argument positions is given so it can be used
     * to generate an informative error message.
     *
     * @param int[] $undefined
     * @return string
     */
    public function message(array $undefined): string;
}
