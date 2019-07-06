<?php declare(strict_types=1);

namespace App\Provider\Tmdb\Resources;

use App\ValueObject\Id;
use App\ValueObject\Name;

class ProductionCompany
{
    private $id;

    private $logoPath;

    private $name;

    private $originCountry;

    private function __construct(Id $id, Name $name, ?string $logoPath, ?string $originCountry)
    {
        $this->id            = $id;
        $this->name          = $name;
        $this->logoPath      = $logoPath;
        $this->originCountry = $originCountry;
    }

    public static function createFromArray(array $data)
    {
        return new self(
            Id::createFromInt($data['id']),
            Name::createFromString($data['name']),
            $data['logo_path'] ?? null,
            $data['origin_country'] ?? null
        );
    }

    public function getId() : Id
    {
        return $this->id;
    }

    public function getLogoPath() : string
    {
        return $this->logoPath;
    }

    public function getName() : Name
    {
        return $this->name;
    }

    public function getOriginCountry() : string
    {
        return $this->originCountry;
    }
}