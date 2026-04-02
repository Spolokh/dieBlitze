<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{
    HasOne,
    HasMany,
    BelongsTo,
};

class Groups extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'usergroups';

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'usergroup');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'usergroups');
    }
}
