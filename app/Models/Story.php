<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Story extends Model
{
    // const CREATED_AT = 'create';
    // const UPDATED_AT = 'update';

    public $timestamps   = false;

    public $incrementing = false;
    
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'story';

    /**
     * Первичный ключ таблицы БД.
     * @var string
     */
    protected $primaryKey = 'post_id';

    protected $fillable = [
        'description',
        'post_id',
        'short', 
        'full',
    ];

    /**
     * Get the post that owns the phone.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
