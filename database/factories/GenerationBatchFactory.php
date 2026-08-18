<?php

namespace Database\Factories;

use App\Enums\BatchStatus;
use App\Models\GenerationBatch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GenerationBatch>
 */
class GenerationBatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'original_filename' => fake()->word().'.xlsx',
            'file_path' => 'batches/'.fake()->uuid().'.xlsx',
            'prompt_sequence' => [
                ['step' => 1, 'prompt' => 'Rédige un plan pour le sujet : {sujet}'],
                ['step' => 2, 'prompt' => 'Rédige l article complet en suivant le plan.'],
            ],
            'status' => BatchStatus::Pending,
            'total_tasks' => 10,
            'processed_tasks' => 0,
            'successful_tasks' => 0,
            'failed_tasks' => 0,
        ];
    }
}
