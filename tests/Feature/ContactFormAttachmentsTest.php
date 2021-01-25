<?php

namespace Tests\Feature;

use App\Models\ContactFormSubmission;
use App\Models\TemporaryUpload;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContactFormAttachmentsTest extends TestCase
{
    use RefreshDatabase;

    public ?FilesystemAdapter $disk;

    public function setUp(): void
    {
        parent::setUp();

        $this->disk = Storage::fake('uploads');
    }

    /** @test */
    public function attachments_can_be_uploaded_separately(): void
    {
        // act
        // upload an attachment and receive the form uuid
        // assert that the response contains the form uuid
        $attachmentOne = $this->pdf();

        $response = $this
            ->postJson('/forms/contact/attachments', [
                'g-recaptcha-response' => 'ok',
                'attachment' => $attachmentOne,
            ])
            ->assertOk()
            ->assertJsonStructure(['uuid']);

        $uuid = $response->json('uuid');

        // act
        // upload an attachment using the received form uuid
        // assert that the response contains the same form uuid as retrieved before
        $attachmentTwo = $this->pdf();

        $this
            ->postJson('/forms/contact/attachments', [
                'g-recaptcha-response' => 'ok',
                'uuid' => $uuid,
                'attachment' => $attachmentTwo,
            ])
            ->assertOk()
            ->assertExactJson(['uuid' => $uuid]);

        // assert
        // assert that attachments are exist in the filesystem
        // assert that the database contains attachments in the temporary_uploads table
        $this->disk->assertExists($attachmentOne->hashName());
        $this->disk->assertExists($attachmentTwo->hashName());

        $this->assertDatabaseHas('temporary_uploads', [
            'uuid' => $uuid,
            'hash' => $attachmentOne->hashName(),
            'filename' => $attachmentOne->getClientOriginalName(),
        ]);
        $this->assertDatabaseHas('temporary_uploads', [
            'uuid' => $uuid,
            'hash' => $attachmentTwo->hashName(),
            'filename' => $attachmentTwo->getClientOriginalName(),
        ]);
    }

    /** @test */
    public function attachments_can_be_retrieved_using_form_uuid(): void
    {
        // arrange
        // create records in the database
        $uuid = (string) Str::uuid();

        TemporaryUpload::factory()->times(2)->create([
            'uuid' => $uuid,
        ]);

        // act
        // retrieve the form's attachments
        // assert
        // assert that the response contains created temporary uploads
        $this
            ->getJson("/forms/contact/{$uuid}/attachments")
            ->assertOk()
            ->assertExactJson([
                ['hash' => '::hash::', 'filename' => '::filename::'],
                ['hash' => '::hash::', 'filename' => '::filename::'],
            ]);
    }

    /** @test */
    public function attachments_can_be_deleted(): void
    {
        // arrange
        // put a file in the filesystem
        // create a record in the database
        $attachment = $this->pdf();

        $this->disk->putFileAs('', $attachment, $attachment->hashName());

        TemporaryUpload::factory()->create([
            'hash' => $attachment->hashName(),
        ]);

        // act
        // delete the attachment
        $this
            ->deleteJson("/forms/contact/attachments/{$attachment->hashName()}")
            ->assertOk();

        // assert
        // assert that the filesystem doesn't contain the attachment
        // assert that the database doesn't contain the attachment
        $this->disk->assertMissing($attachment->hashName());

        $this->assertDatabaseMissing('temporary_uploads', [
            'hash' => $attachment->hashName(),
            'filename' => $attachment->getClientOriginalName(),
        ]);
    }

    /** @test */
    public function captcha_is_required(): void
    {
        // act
        // submit the form with an invalid g-recaptcha-response field
        // assert
        // assert that the response contains the error for the g-recaptcha-response field
        $this->postJson('/forms/contact/attachments', [
            'g-recaptcha-response' => 'invalid',
        ])->assertJsonValidationErrors([
            'g-recaptcha-response',
        ]);
    }

    /** @test */
    public function it_is_not_allowed_to_upload_more_than_3_attachments(): void
    {
        $uuid = (string) Str::uuid();

        $data = fn () => [
            'g-recaptcha-response' => 'ok',
            'uuid' => $uuid,
            'attachment' => $this->pdf(),
        ];

        // act
        // upload 3 attachments
        $this->postJson('/forms/contact/attachments', $data())->assertOk();
        $this->postJson('/forms/contact/attachments', $data())->assertOk();
        $this->postJson('/forms/contact/attachments', $data())->assertOk();

        // act
        // upload an attachment
        // assert that the attachments limit is reached
        $this->postJson('/forms/contact/attachments', $data())->assertJsonValidationErrors([
            'attachment' => 'Attachments limit is reached.',
        ]);
    }

    /** @test */
    public function it_is_not_allowed_to_upload_an_attachment_when_the_form_is_submitted(): void
    {
        $uuid = (string) Str::uuid();

        // arrange
        // create a new form submission using the uuid
        ContactFormSubmission::factory()->create(['uuid' => $uuid]);

        // act
        // upload an attachment
        // assert
        // assert that the attachment cannot be uploaded as the form is submitted
        $this->postJson('/forms/contact/attachments', [
            'g-recaptcha-response' => 'ok',
            'uuid' => $uuid,
            'attachment' => $this->pdf(),
        ])->assertJsonValidationErrors([
            'attachment' => 'The attachment cannot be uploaded. The form has already been submitted.',
        ]);
    }

    private function pdf(string $name = 'doc.pdf', int $size = 1024): File
    {
        return UploadedFile::fake()->create($name, $size, 'application/pdf');
    }
}
