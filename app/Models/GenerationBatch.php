<?php

namespace App\Models;

use App\Enums\BatchStatus;
use Database\Factories\GenerationBatchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GenerationBatch extends Model
{
    /** @use HasFactory<GenerationBatchFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'original_filename',
        'file_path',
        'prompt_sequence',
        'status',
        'total_tasks',
        'processed_tasks',
        'successful_tasks',
        'failed_tasks',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => BatchStatus::class,
            'prompt_sequence' => 'array',
            'total_tasks' => 'integer',
            'processed_tasks' => 'integer',
            'successful_tasks' => 'integer',
            'failed_tasks' => 'integer',
        ];
    }

    /**
     * @return HasMany<Article, $this>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
