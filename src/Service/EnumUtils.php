<?php

namespace App\Service;

interface EnumUtils
{
    public function fromName(string $name): ?string;
}
