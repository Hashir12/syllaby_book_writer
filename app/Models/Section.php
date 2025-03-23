<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['book_id', 'parent_id', 'title', 'content'];
    /**
     * @var mixed
     */
    private $section;
    /**
     * @var array
     */
    private $data;
    private $sectionId;

    public function setData($data)
    {
        $this->data = [
            'book_id' => isset($data['book_id']) ? $data['book_id'] : null,
            'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
            'title' => $data['title'],
            'content' => $data['content'],
        ];
        return $this;
    }
    public function setId($id)
    {
        $this->sectionId = $id;
        return $this;
    }

    public function getSection($flag = null)
    {
        $section = $this->where('id', $this->sectionId)->first();

        if(!$section) {
            return null;
        }

        if ($flag) {
            return $section;
        } else {
            $this->section = $section;
            return $this;
        }

    }

    public function saveOrUpdate()
    {
        if (isset($this->id)) {
            $obj = $this->section;
        } else {
            $obj = $this;
        }

        if (isset($this->data['book_id'])) {
            $obj->book_id = $this->data['book_id'];
        }
        $obj->parent_id = $this->data['parent_id'];
        $obj->title = $this->data['title'];
        $obj->content = $this->data['content'];
        $obj->save();

        return [
            'status' => true,
            'data' => $obj
        ];
    }

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
