<?php 

namespace App;

use App\Favorite;


trait Favoritable {

	public function favorites()
	{
		return $this->morphMany(Favorite::class, 'favorited');
	}

	public function favorite()
	{
		$attributes = ['user_id' => auth()->id()];

		if (! $this->favorites()->where($attributes)->exists()) {
			return $this->favorites()->create($attributes);
		}
	}

	public function unfavorite()
	{
		$attributes = ['user_id' => auth()->id()];

		$this->favorites()->where($attributes)->get()->each->delete();
	}

}