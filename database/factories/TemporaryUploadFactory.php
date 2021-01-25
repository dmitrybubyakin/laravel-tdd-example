<?php

namespace Database\Factories;

use App\Models\TemporaryUpload;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemporaryUploadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TemporaryUpload::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => '::uuid::',
            'hash' => '::hash::',
            'filename' => '::filename::',
        ];
    }
}
