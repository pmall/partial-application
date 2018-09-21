<?php declare(strict_types=1);

namespace Quanta;

interface PartialApplicationInterface
{
    public function __invoke(...$xs);
}
