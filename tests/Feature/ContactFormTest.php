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
}
