<?php declare(strict_types=1);

namespace Quanta\PartialApplication;

final class ArgumentCountErrorStr
{
    const TPL = 'Too few arguments to partial application of %s, %s passed in %s on line %s and exactly %s expected';

    private $callable;
    private $given;
    private $expected;
    private $file;
    private $line;

    public function __construct(string $callable, int $given, int $expected, string $file, int $line)
    {
        $this->callable = $callable;
        $this->given = $given;
        $this->expected = $expected;
        $this->file = $file;
        $this->line = $line;
    }

    public function __toString()
    {
        return vsprintf(self::TPL, [
            $this->callable,
            $this->given,
            $this->file,
            $this->line,
            $this->expected,
        ]);
    }
}
