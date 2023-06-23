<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    public function author(){
        return $this->belongsToMany(Author::class);
    }

    public function source(){
        return $this->belongsTo(Source::class, 'source_id', 'id');
    }

    protected $guarded = [];

}
