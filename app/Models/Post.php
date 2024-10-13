<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $primarykey = 'id';
    protected $fillable = ['postContent','image',"user_id"];

    public function users(){
        return $this->belongsTo(User::class);
    }
}
