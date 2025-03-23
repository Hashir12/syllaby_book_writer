<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'user_id'];
    private $userId;
    private $data;
    private $bookId;
    private $book;

    public function setUser($authUser)
    {
        $this->userId = $authUser;
        return $this;
    }

    public function setData($data)
    {
        $this->data = [
            'title' => $data['title'],
            'user_id' => $this->userId,
        ];

        return $this;
    }

    public function setBookId($id)
    {
        $this->bookId = $id;
        return $this;
    }

    public function getBook($flag = null)
    {
        $book = $this->with('sections')->where('id', $this->bookId)->where('user_id', $this->userId)->first();

        if (!$book){
            return null;
        }
        if ($flag){
            return $book;
        } else {
            $this->book = $book;
            return $this;
        }
    }

    public function saveOrUpdateBook()
    {
        if (isset($this->book)) {
            $obj = $this->book;
        } else {
            $obj = $this;
        }

        $obj->title = $this->data['title'];
        $obj->user_id = $this->userId;
        $obj->save();

        return [
            'status' => true,
            'data' => $obj,
        ];
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
