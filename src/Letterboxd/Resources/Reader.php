<?php declare(strict_types=1);

namespace App\Letterboxd\Resources;

interface Reader
{
    public function loadFromCsv(string $csvPath);
}