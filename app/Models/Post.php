<?php

namespace App\Models;

use IntlDateFormatter;
use Illuminate\Database\Eloquent\{
    Model,
    Builder,
    SoftDeletes
};

use Illuminate\Database\Eloquent\Relations\{
    HasOne,
    HasMany
};

class Post extends Model
{
    public const FIELDS = [
        'id',
        'date',
        'title', 
        'image', 
        'views', 
        'votes',
        'hidden',
        'author',
        'user_id', 
        'comments',
    ];

    /**
     * Типы в модели Post для постов
     * @var array
     */
    public const TYPES = [
        'blog'  => 'Блог',
        'post'  => 'Пост',
        'shop'  => 'Магазин',
        'guest' => 'Гостевая',
        'draft' => 'Черновик',
    ];

    /**
     * Следует ли обрабатывать временные метки модели.
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'url',
        'date',
        'type',
        'votes',
        'title',
        'image',
        'hidden',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date'   => 'timestamp',
            'hidden' => 'boolean',
        ];
    }

    /**
     * Get the story associated with the user.
     */
    public function story(): HasOne
    {
        return $this->hasOne(Story::class, 'post_id', 'id');
    }

    /**
     * Get the users associated with the user.
     */
    public function users(): HasOne
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'post_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')->where('hidden', false);
    }

    public function scopeSearch($query, ?string $search): Builder
    {
        return $query->when($search, fn($q) => 
            $q->where('title', 'like', '%'.$search.'%')
                ->orWhereHas('story', fn($sub) => $sub->where('short', 'like', '%'.$search.'%')
        ));
    }

    public function scopeEditAdmin($query): Builder
    {
        return auth()->user()?->isAdmin() ? $query->withoutGlobalScope('hidden') : $query;
    }

    /**
     * Scope с author Опубликованные посты пользователя
     * $Blog = Blog::isHidden($hidden)->byAuthor()->paginate(7);
     */
    public function scopeByAuthor(Builder $query, ?string $author = null): Builder
    {
        return $query->where('author', $author ?? auth()->user()->username);
    }

    public function scopeIsHidden(Builder $query, int $hidden = 0): Builder
    {
        return $query->where('hidden', $hidden);
    }

    public function scopeIsType($query, string $type = 'blog'): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeInCategory($query, $categories): Builder
    {
        $categories = is_array($categories) ? $categories : [$categories];
        
        foreach ($categories as $category) {
            $query->whereRaw("FIND_IN_SET(?, `category`)", [$category]);
        }
        return $query;
    }

    // Опционально: аксессор
    public function getExcerptAttribute(): string
    {
        return str(strip_tags($this->story?->short))->words(40);
    }

    public function getContentAttribute(): string
    {
        return $this->story?->full ?? $this->story->short;
    }

    public function getPostAuthorAttribute(): string
    {
        return $this->users?->name ?? $this->author ?? 'Unknown';
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('uploads/posts/' . $this->image) : '/img/404.jpg';
    }

    /**
     * @var string|null
     */
    public function getDateFormattedAttribute(): ?string
    {
        $formatter = (new IntlDateFormatter(
            app()->getLocale(), 
            IntlDateFormatter::FULL, 
            IntlDateFormatter::NONE, 
            'Europe/Moscow'
        ))->format($this->date);
        return mb_strtoupper(mb_substr($formatter, 0, 1)) . mb_substr($formatter, 1);
    }

    protected static function booted()
    {
        static::creating(function (Post $post) {
            $post->url ??= str($post->title)->slug();
            $post->user_id ??= auth()->id();
            $post->author ??= auth()->user()->username;
        });

        static::updating(function (Post $post) { // Автоматически генерируем URL если не задан
            $post->url ??= str($post->title)->slug();
        });

        // Автоинкремент счётчика ПОСЛЕ успешного создания
        static::created(function (Post $post) {
            User::whereKey($post->user_id)->increment('publications');
        });

        // Автодекремент при удалении
        static::deleting(function (Post $post) {
            $post->story?->delete();
            $post->votes()->delete();
            $post->comments()->delete();
            User::whereKey($post->user_id)->decrement('publications');
        });

        static::addGlobalScope('hidden', function (Builder $builder) {
            if ( !auth()->check() || !auth()->user()->isAdmin() ) {
                $builder->where('hidden', false);
            }
        });
    }

    private function generateUniqueSlug($title)
    {
        $slug  = str($title)->slug();
        $count = 0;
        
        while (Post::where('url', $slug)->where('id', '<>', $this->id)->exists()) {
            $slug = str($title)->slug() . '-' . ++$count;
        }
        return $slug;
    }
}
