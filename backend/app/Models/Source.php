<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function news(){
        return $this->hasMany(News::class);
    }
}
