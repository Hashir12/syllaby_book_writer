<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['book_id', 'parent_id', 'title', 'content'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function parent()
    {
        return $this->belongsTo(Section::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Section::class, 'parent_id');
    }

    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'section_collaborators');
    }
}
