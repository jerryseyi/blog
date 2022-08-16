<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

   /** @test */
    function a_user_has_a_profile()
    {
        $user = create('App\User', ['name' => 'JohnDoe']);

        $response = $this->getJson(route('profile', $user), $user->toArray())->json();

        $this->assertEquals($user->name, $response['profileUser']['name']);
    }

    /** @test */
    function profiles_display_all_threads_created_by_the_associated_user()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $response = $this->getJson(route('profile', auth()->user()->name), auth()->user()->toArray())->json();

        // dd($response['profileUser']['thread'][0]['title']);
        
        $this->assertEquals($thread->title, $response['profileUser']['thread'][0]['title']);

    }
}
