<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
    ];

    protected $guarded = [];

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function members() {
        return $this->belongsToMany(User::class, 'users_channels');
    }
}
