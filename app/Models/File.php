<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'location',
        'description',
        'file',
        'civil_case_number',
        'lot_number',
        'status',
        'file_category_id',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(FileCategory::class, 'file_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
