<?php declare(strict_types=1);

namespace App\Provider\Tmdb;

class Provider implements \App\Provider\Provider
{
    public static function create() : self
    {
        return new self();
    }

    public static function getPageIdPattern() : string
    {
        return '/data-tmdb-id="(\d*)"/';
    }

    public static function getName() : string
    {
        return 'tmdb';
    }
}