<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vote extends Model
{
    use HasFactory;

    /**
     * Следует ли обрабатывать временные метки модели.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'post_id',
    ];

    protected static function booted()
    {
        static::creating(function (Vote $vote)
        {
            $user = auth()->user();
            $vote->ip = request()->ip();
            $vote->user_id = $user?->id ?? 0;
        });
    }
}
