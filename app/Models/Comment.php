<?php

namespace App\Models;

use IntlDateFormatter;
use Illuminate\Database\Eloquent\{
    Model,
    Builder
};

use Illuminate\Database\Eloquent\Relations\{
    HasMany,
    BelongsTo
};

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    /**
     * Поля в модели Comment
     * @var array
     */
    public const FIELDS = [
        'id',
        'ip',
        'date',
        'type',
        'post_id',
        'user_id',
        'reply',
        'author',
        'comment',
        'parent',
        'level',
        'hidden'
    ];
    
    /**
     * Типы в модели Comment
     * @var array
     */
    public const TYPES = [
        'post'  => 'Посты',
        'blog'  => 'Блоги',
        'shop'  => 'Магазин',
        'guest' => 'Гостевая',
        'draft' => 'Черновик',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mail', 
        'type', 
        'reply', 
        'level',
        'post_id',
        'author',
        'parent',
        'hidden', 
        'comment',
    ];

    /**
     * Следует ли обрабатывать временные метки модели.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the post that owns the comment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id')->select(['id', 'title', 'type']);
    }

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->where('deleted', false);
    }

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent')->select(self::FIELDS)->where('hidden', false);
    }

    public function scopeIsHidden(Builder $query, bool $hidden = false): Builder
    {
        return $query->where('hidden', $hidden);
    }

    /**
     * Scope с author Опубликованные посты пользователя
     * $Blog = Comment::byAuthor()->paginate(7);
     */
    public function scopeByAuthor(Builder $query, ?int $id = null): Builder
    {
        return $query->where('user_id', $id ?? auth()->id());
    }

    public function getExcerptAttribute(): string
    {
        return str(strip_tags($this->comment))->limit(50);
    }

    public function getPostTitleAttribute(): string
    {
        return $this->post?->title ?? 'title';
    }

    public function getAvatarUrlAttribute(): string
    {
        return ($this->user && $this->user->avatar) ? asset('uploads/userpics/thumbs/' . $this->author . '.' . $this->user->avatar) : '/img/default.png';
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
        static::creating(function (Comment $comment)
        {     
            if (auth()->check()) {
                $user = auth()->user();
                $comment->mail    = $user->userMail; // mail стандартное в Laravel
                $comment->author  = $user->userName; // name стандартное
                $comment->user_id = $user->id;
            } else {
                $comment->hidden  = 1;
                $comment->user_id = 0; // author и mail уже прошли валидацию и переданы в $validated
            }
            $comment->ip   = request()->ip();
            $comment->date = now()->timestamp;
        });

        // 🔑 Автоинкремент счётчика ПОСЛЕ успешного создания
        static::created(function (Comment $comment) {
            Post::whereKey($comment->post_id)->increment('comments');
        });

        // 🔑 Автодекремент при удалении
        static::deleted(function (Comment $comment) {
            Post::whereKey($comment->post_id)->decrement('comments');
        });

        // Вместо deleted используйте trashed/restored
        // static::trashed(function (Comment $comment) {
        //     Post::whereKey($comment->post_id)->decrement('comments');
        // });

        // static::restored(function (Comment $comment) {
        //     Post::whereKey($comment->post_id)->increment('comments');
        // });
    }
}
