<?php

namespace App\Models;

//use Overtrue\LaravelFollow\Traits\CanBeLiked;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    //use CanBeLiked;


    // create a relationship between user and model
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
