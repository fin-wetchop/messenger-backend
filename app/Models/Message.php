<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'channel_id',
        'content',
    ];

    protected $guarded = [];

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function channel() {
        return $this->belongsTo(Channel::class, 'channel_id');
    }
}