<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Portfolio extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'portfolios';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_url', // Untuk thumbnail/preview
        'file_url',  // Untuk file lampiran (PDF, Video, dll)
        'file_type', // Tipe file (pdf, video, doc, dll)
        'category',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
