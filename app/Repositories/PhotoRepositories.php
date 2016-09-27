<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\Photo;

class PhotoRepositories
{
    public function getAll()
    {
        return Photo::all();
    }

    public function save(Photo $photo)
    {
        return $photo->save();
    }
}
