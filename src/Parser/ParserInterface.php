<?php

namespace App\Parser;

use App\Parser\Dto\ParserResult;

interface ParserInterface
{
    public function parse(): ParserResult;
    public function getName(): string;
}
