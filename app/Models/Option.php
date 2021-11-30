<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['choice_text','question_id','choice_id','correct_choice','category_id'];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
