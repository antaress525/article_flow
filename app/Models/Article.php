<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    protected $fillable = [
        'generation_batch_id',
        'row_index',
        'input_data',
        'title',
        'content',
        'steps_output',
        'status',
        'error_message',
        'execution_time_ms',
        'tokens_used',
        'started_at',
        'completed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
            'input_data' => 'array',
            'steps_output' => 'array',
            'row_index' => 'integer',
            'execution_time_ms' => 'integer',
            'tokens_used' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<GenerationBatch, $this>
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(GenerationBatch::class, 'generation_batch_id');
    }
}
