use App\Enums\ArticleStatus;
use App\Enums\BatchStatus;
use App\Models\Article;
use App\Models\GenerationBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create generation batch with articles and retrieve relationships', function () {
    $batch = GenerationBatch::factory()->create([
        'name' => 'Lot Test Août',
        'status' => BatchStatus::Pending,
        'prompt_sequence' => [
            ['step' => 1, 'prompt' => 'Créer un plan'],
            ['step' => 2, 'prompt' => 'Créer l article'],
        ],
        'total_tasks' => 2,
    ]);

    $article1 = Article::factory()->create([
        'generation_batch_id' => $batch->id,
        'title' => 'Article 1',
        'status' => ArticleStatus::Completed,
        'execution_time_ms' => 2500,
    ]);

    $article2 = Article::factory()->create([
        'generation_batch_id' => $batch->id,
        'title' => 'Article 2',
        'status' => ArticleStatus::Failed,
        'error_message' => 'API DeepSeek indisponible',
        'execution_time_ms' => 500,
    ]);

    expect($batch->articles)->toHaveCount(2)
        ->and($batch->status)->toBe(BatchStatus::Pending)
        ->and($batch->prompt_sequence)->toBeArray()
        ->and($batch->prompt_sequence[0]['prompt'])->toBe('Créer un plan');

    expect($article1->batch->id)->toBe($batch->id)
        ->and($article1->status)->toBe(ArticleStatus::Completed)
        ->and($article2->status)->toBe(ArticleStatus::Failed)
        ->and($article2->error_message)->toBe('API DeepSeek indisponible');
});
