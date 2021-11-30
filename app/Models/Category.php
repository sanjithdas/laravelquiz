<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    public $table = 'categories';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'display_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getCategoryAttribute(){
        return "{$this->id} {$this->name}";
    }

//     public function getFullNameAttribute()
// {
//     return "{$this->first_name} {$this->last_name}";
// }

    public function categoryQuestions()
    {
        return $this->hasMany(Question::class, 'category_id', 'id');
    }
}
