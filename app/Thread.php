<?php

namespace App;

use App\User;
use App\Reply;
use App\Channel;
use App\recordsActivity;
use App\ThreadSubscription;
use Illuminate\Support\Str;
use App\Filters\ThreadFilters;
use Stevebauman\Purify\Purify;
use App\Events\ThreadReceivedNewReply;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

    use recordsActivity;

    protected $guarded = [];

    protected $with = ['channel', 'creator'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

        static::created(function ($thread) {
            $thread->update(['slug' => $thread->title]);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function replies()
    {
       return $this->hasMany(Reply::class);
    }

    public function path(): string
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = Str::slug($value))->exists()) {
            $slug = "{$slug}-{$this->id}";
        }
        $this->attributes['slug'] = $slug;
    }

    public function markBestReply(Reply $reply) 
    {
        $this->update(['best_reply_id' => $reply->id]);
    }

    public function addReply($reply) {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    public function subscriptions() {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function subscribe($userId = null) {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()             
        ]);

        return $this;
    }

    public function unsubscribe($userId = null) {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();

    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function getBodyAttribute($body)
    {
        return (new Purify())->clean($body);
    }

    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }
}
