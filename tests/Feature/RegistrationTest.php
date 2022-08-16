<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;
    
    // /** @test */
    // function a_confirmation_email_is_sent_upon_registration()
    // {
    //     Mail::fake();

    //     $this->post(route('register'), [
    //         'name' => 'John',
    //         'email' => 'john@example.com',
    //         'password' => 'foobar',
    //         'password_confirmation' => 'foobar'
    //     ]);

    //     Mail::assertQueued(MustVerifyEmail::class);
    // }
}
