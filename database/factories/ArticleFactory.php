<?php

namespace Database\Factories;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\GenerationBatch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'generation_batch_id' => GenerationBatch::factory(),
            'row_index' => fake()->numberBetween(1, 100),
            'input_data' => [
                'sujet' => fake()->sentence(),
                'mot_cle' => fake()->word(),
            ],
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'steps_output' => [],
            'status' => ArticleStatus::Completed,
            'error_message' => null,
            'execution_time_ms' => fake()->numberBetween(1000, 5000),
            'tokens_used' => fake()->numberBetween(200, 1500),
            'started_at' => now()->subSeconds(10),
            'completed_at' => now(),
        ];
    }
}
