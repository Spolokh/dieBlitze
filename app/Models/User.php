<?php

namespace App\Models;

use DateTimeInterface;
use IntlDateFormatter;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{
    HasOne,
    HasMany,
};

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Следует ли обрабатывать временные метки модели.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Поля в модели User
     * @var array
     */
    public const FIELDS = [
        'id',
        'date',
        'mail',
        'name',
        'avatar',
        'username',
        'usergroup',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'mail',
        'phone',
        'about',
        'password',
        'location',
        'usergroup',
        'avatar',
        'deleted'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deleted' => 'boolean',
            'password' => 'hashed',
            'birthdate' => 'timestamp',
        ];
    }

    /**
     * Get the user's group.
     */
    public function group(): HasOne
    {
        return $this->hasOne(Groups::class, 'id', 'usergroup');
    }

    /**
     * Get the user's posts.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the user's comments.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @var boolean
     */
    public function isAdmin(): bool
    {
        return $this->usergroup === 1;
    }

    /**
     * @var boolean
     */
    public function isGroup(): bool
    {
        return $this->usergroup;
    }

    /**
     * @var boolean
     */
    // public function isEditor(): bool
    // {
    //     return $this->usergroup < 4;
    // }

    /**
     * @var boolean
     */
    public function isNotAdmin(): bool
    {
        return $this->usergroup > 1;
    }

    public function scopeInGroups(Builder $query, $usergroup = 0): Builder
    {
        return $query->where('usergroup', $usergroup);
    }

    public function scopeIsActive(Builder $query, $deleted = 0): Builder
    {
        return $query->where('deleted', $deleted);
    }

    public function getUserMailAttribute($value): string
    {
        return $this->attributes['mail'] ?? $value;
    }
    
    public function getUserNameAttribute($value): string
    {
        return $this->attributes['username'] ?? $value;
    }

    public function getFirstNameAttribute(): string
    {
        return $this?->name ?? $this->username;
    }

    public function getAvatarUrlAttribute(): string
    {
        return asset('uploads/userpics/thumbs/' . ($this->avatar ? $this->username . '.' . $this->avatar : 'default.png'));
    }

    /**
     * @var string|null
     */
    public function getDateFormattedAttribute(): string
    {
        $formatter = (new IntlDateFormatter(
            app()->getLocale(), 
            IntlDateFormatter::FULL, 
            IntlDateFormatter::NONE, 
            'Europe/Moscow'
        ))->format($this->date);
        return mb_strtoupper(mb_substr($formatter, 0, 1)) . mb_substr($formatter, 1);
    }
}
