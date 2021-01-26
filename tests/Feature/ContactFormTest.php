<?php

namespace Tests\Feature;

use App\AnonymousNotifiableFactory;
use App\Models\ContactFormSubmission;
use App\Notifications\Admin\NewContactFormSubmission;
use App\Notifications\FormWillBeProcessedSoon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test */
    public function form_data_are_stored_in_database(): void
    {
        $data = ContactFormSubmission::factory()->raw();

        // act
        // submit the form
        $this->postJson('/forms/contact', $data + [
            'g-recaptcha-response' => 'ok',
        ])->assertOk();

        // assert
        // assert that the database contains the form data
        $this->assertDatabaseHas('contact_form_submissions', $data);
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

    /** @test */
    public function form_cannot_be_submitted_twice(): void
    {
        $data = ContactFormSubmission::factory()->raw();

        // act
        // submit the form
        $this->postJson('/forms/contact', $data + [
            'g-recaptcha-response' => 'ok',
        ])->assertOk();

        // act
        // submit the form
        // assert
        // assert that the form with the given uuid has already been submitted
        $this->postJson('/forms/contact', $data + [
            'g-recaptcha-response' => 'ok',
        ])->assertJsonValidationErrors([
            'uuid' => 'The form has already been submitted.',
        ]);
    }

    /** @test */
    public function admin_receives_the_notification_that_there_is_a_new_form_submission(): void
    {
        $this->postJson('/forms/contact', ContactFormSubmission::factory()->raw([
            'g-recaptcha-response' => 'ok',
        ]))->assertOk();

        Notification::assertSentTo(
            AnonymousNotifiableFactory::email('admin@app.test'),
            NewContactFormSubmission::class
        );
    }

    /** @test */
    public function the_form_submitter_receives_the_notification_that_the_form_will_be_processed_soon(): void
    {
        $email = 'john@doe.test';

        $this->postJson('/forms/contact', ContactFormSubmission::factory()->raw([
            'g-recaptcha-response' => 'ok',
            'email' => $email,
        ]))->assertOk();

        Notification::assertSentTo(
            AnonymousNotifiableFactory::email($email),
            FormWillBeProcessedSoon::class
        );
    }
}
