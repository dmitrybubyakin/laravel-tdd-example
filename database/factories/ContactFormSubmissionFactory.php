<?php

namespace Database\Factories;

use App\Models\ContactFormSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFormSubmissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContactFormSubmission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'name' => '::name::',
            'email' => 'email@test.com',
            'phone' => '::phone::',
            'notes' => '::notes::',
        ];
    }
}
