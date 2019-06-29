<?php declare(strict_types=1);

namespace App\Provider\Imdb;

class Provider implements \App\Provider\Provider
{
    public static function create() : self
    {
        return new self();
    }

    public static function getPageIdPattern() : string
    {
        return '/imdb.com\/title\/(\w*)\//';
    }

    public static function getName() : string
    {
        return 'imdb';
    }
}