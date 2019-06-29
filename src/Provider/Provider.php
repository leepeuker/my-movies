<?php declare(strict_types=1);

namespace App\Provider;

interface Provider
{
    public static function getPageIdPattern() : string;
}