<?php declare(strict_types=1);

namespace App\Repositories;

interface GeocodingRepositories
{
    public function findByPoint(string $latitude, string $longitude);
}
