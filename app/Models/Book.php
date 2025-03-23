<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'user_id'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
