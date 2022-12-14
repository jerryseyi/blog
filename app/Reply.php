<?php

namespace App;

use App\User;
use App\Thread;
use Carbon\Carbon;
use App\recordsActivity;
use Stevebauman\Purify\Purify;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use recordsActivity, Favoritable;

    protected $guarded = [];

    protected $with = ['owner'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });
        static::deleted(function($reply) {
            $reply->thread->update(['best_reply_id' => null]);
            $reply->thread->decrement('replies_count');
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }

    /**
     * Determine if the current reply is marked as the best.
     *
     * @return bool
     */
    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    /**
     *
     * Determine if the current reply is marked as the best.
     *
     * @return bool
     */
    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

     public function getBodyAttribute($body)
     {
         return (new Purify)->clean($body);
     }

     public function path() {
        return $this->thread->path() . "#reply-{$this->id}";
     }

    
}

