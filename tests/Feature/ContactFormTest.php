<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function form_data_are_stored_in_database(): void
    {
        // act
        // submit the form
        $this->postJson('/forms/contact', [
            'g-recaptcha-response' => 'ok',
            'name' => '::name::',
            'email' => 'email@test.com',
            'phone' => '::phone::',
            'notes' => '::notes::',
        ])->assertOk();

        // assert
        // assert that the database contains the form data
        $this->assertDatabaseHas('contact_form_submissions', [
            'name' => '::name::',
            'email' => 'email@test.com',
            'phone' => '::phone::',
            'notes' => '::notes::',
        ]);
    }

    /** @test */
    public function captcha_is_required(): void
    {
        // act
        // submit the form with an invalid g-recaptcha-response field
        // assert
        // assert that the response contains the error for the g-recaptcha-response field
        $this->postJson('/forms/contact', [
            'g-recaptcha-response' => 'invalid',
        ])->assertJsonValidationErrors([
            'g-recaptcha-response',
        ]);
    }
}
