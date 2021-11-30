<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_at',
        'updated_at',
        'deleted_at',
        'category_id',
        'question_text',
    ];

    public function options()
    {

        return $this->hasMany(Option::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
