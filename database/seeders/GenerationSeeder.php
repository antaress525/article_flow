<?php

namespace Database\Seeders;

use App\Enums\ArticleStatus;
use App\Enums\BatchStatus;
use App\Models\Article;
use App\Models\GenerationBatch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class GenerationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            'SEO Tech' => [
                'Les meilleures pratiques Laravel 11 & PHP 8.4',
                'Guide complet de l architecture Clean Code',
                'Comment optimiser les requêtes SQL avec Eloquent',
                'Utiliser Redis pour le caching d API haute performance',
                'Introduction à la conteneurisation Docker pour Laravel',
            ],
            'IA & Automation' => [
                'Générer du contenu avec DeepSeek V3 API',
                'Comparatif entre les LLM open source et propriétaires',
                'Automatisations de workflows avec Webhooks & Queues',
                'Intégration d assistants IA dans une SPA Vue.js',
                'Optimisation des prompts pour le traitement par lots',
            ],
            'E-commerce' => [
                'Stratégies d acquisition de trafic pour boutique en ligne',
                'Les clés pour améliorer le taux de conversion checkout',
                'Intégrer Stripe & Paypal dans une application web',
                'Gestion des stocks et logistique e-commerce 2026',
            ],
            'Finance' => [
                'Gestion du budget d entreprise avec des outils SaaS',
                'Comprendre l impact des taux d intérêt sur la tech',
                'Guide de l investissement pour les startups',
            ],
        ];

        // 1. Past completed batches (Days -12 to -1)
        for ($day = 12; $day >= 1; $day--) {
            $date = Carbon::now()->subDays($day);
            $count = rand(4, 10);

            $batch = GenerationBatch::create([
                'name' => "Batch du {$date->format('d/m/Y')}",
                'original_filename' => "mots_cles_{$date->format('Y_m_d')}.xlsx",
                'file_path' => "batches/import_{$date->timestamp}.xlsx",
                'prompt_sequence' => [
                    ['step' => 1, 'prompt' => 'Génère un plan détaillé sur : {sujet}'],
                    ['step' => 2, 'prompt' => 'Rédige l article en français avec le mot clé : {mot_cle}'],
                ],
                'status' => BatchStatus::Completed,
                'total_tasks' => $count,
                'processed_tasks' => $count,
                'successful_tasks' => 0,
                'failed_tasks' => 0,
                'created_at' => $date->copy()->subHours(2),
                'updated_at' => $date,
            ]);

            $successCount = 0;
            $failCount = 0;

            for ($i = 1; $i <= $count; $i++) {
                $isFailure = (rand(1, 100) <= 12);
                $execTime = rand(2200, 8500);
                $startedAt = $date->copy()->subMinutes(rand(10, 110));
                $completedAt = $startedAt->copy()->addMilliseconds($execTime);

                $category = array_rand($topics);
                $sujet = $topics[$category][array_rand($topics[$category])];

                if ($isFailure) {
                    $failCount++;
                    Article::create([
                        'generation_batch_id' => $batch->id,
                        'row_index' => $i,
                        'input_data' => ['sujet' => $sujet, 'mot_cle' => 'guide 2026', 'categorie' => $category],
                        'title' => $sujet,
                        'content' => null,
                        'status' => ArticleStatus::Failed,
                        'error_message' => rand(0, 1) ? 'Erreur 429: DeepSeek Rate Limit exceeded' : 'Timeout de réponse API (> 30s)',
                        'execution_time_ms' => $execTime,
                        'tokens_used' => null,
                        'started_at' => $startedAt,
                        'completed_at' => $completedAt,
                        'created_at' => $startedAt,
                        'updated_at' => $completedAt,
                    ]);
                } else {
                    $successCount++;
                    Article::create([
                        'generation_batch_id' => $batch->id,
                        'row_index' => $i,
                        'input_data' => ['sujet' => $sujet, 'mot_cle' => 'tutoriel facile', 'categorie' => $category],
                        'title' => $sujet,
                        'content' => "## Introduction\n\nCet article traite de {$sujet}.\n\n## Corps de l'article\n\nVoici les explications détaillées et les points essentiels à retenir.\n\n## Conclusion\n\nEn résumé, l'application de ces conseils garantit d'excellents résultats.",
                        'steps_output' => [
                            'step_1' => 'Plan détaillé généré avec succès.',
                            'step_2' => 'Article rédigé avec succès.',
                        ],
                        'status' => ArticleStatus::Completed,
                        'error_message' => null,
                        'execution_time_ms' => $execTime,
                        'tokens_used' => rand(800, 2600),
                        'started_at' => $startedAt,
                        'completed_at' => $completedAt,
                        'created_at' => $startedAt,
                        'updated_at' => $completedAt,
                    ]);
                }
            }

            $batch->update([
                'successful_tasks' => $successCount,
                'failed_tasks' => $failCount,
            ]);
        }

        // 2. Active batch for today (Processing)
        $todayBatch = GenerationBatch::create([
            'name' => 'Lot En Cours - Stratégie Contenu IA',
            'original_filename' => 'mots_cles_aujourdhui.xlsx',
            'file_path' => 'batches/import_today.xlsx',
            'prompt_sequence' => [
                ['step' => 1, 'prompt' => 'Créer un titre accrocheur pour : {sujet}'],
                ['step' => 2, 'prompt' => 'Rédiger l article complet.'],
            ],
            'status' => BatchStatus::Processing,
            'total_tasks' => 15,
            'processed_tasks' => 10,
            'successful_tasks' => 9,
            'failed_tasks' => 1,
            'created_at' => Carbon::now()->subMinutes(45),
            'updated_at' => Carbon::now(),
        ]);

        for ($i = 1; $i <= 15; $i++) {
            $sujet = "Article exemple #{$i} - IA & Automatisation";
            $status = ArticleStatus::Pending;
            $execTime = null;
            $completedAt = null;
            $startedAt = null;
            $content = null;
            $errorMsg = null;

            if ($i <= 9) {
                $status = ArticleStatus::Completed;
                $execTime = rand(2400, 6800);
                $startedAt = Carbon::now()->subMinutes(40 - $i * 3);
                $completedAt = $startedAt->copy()->addMilliseconds($execTime);
                $content = "Contenu généré pour {$sujet}.";
            } elseif ($i === 10) {
                $status = ArticleStatus::Failed;
                $execTime = 1200;
                $startedAt = Carbon::now()->subMinutes(5);
                $completedAt = $startedAt->copy()->addMilliseconds($execTime);
                $errorMsg = 'API Key invalid or quota reached';
            } elseif ($i === 11) {
                $status = ArticleStatus::Processing;
                $startedAt = Carbon::now()->subSeconds(15);
            }

            Article::create([
                'generation_batch_id' => $todayBatch->id,
                'row_index' => $i,
                'input_data' => ['sujet' => $sujet, 'mot_cle' => 'DeepSeek'],
                'title' => $sujet,
                'content' => $content,
                'status' => $status,
                'error_message' => $errorMsg,
                'execution_time_ms' => $execTime,
                'tokens_used' => $status === ArticleStatus::Completed ? rand(900, 2100) : null,
                'started_at' => $startedAt,
                'completed_at' => $completedAt,
                'created_at' => Carbon::now()->subMinutes(45),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
