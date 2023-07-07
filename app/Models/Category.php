<?php

namespace App\Models;

use App\Lib\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable=[
             'parent_id',
             'name',
             'image',
             'status',
    ];

    public function getImageAttribute($image){
        if (isset($image)){
            return Image::url($image);
        }else{
            return  asset('image/avatar.png');
        }
    }



}
