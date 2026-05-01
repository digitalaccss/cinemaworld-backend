<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Scout\EngineManager;

class SearchSetup extends Command
{
    protected $signature = 'search:setup';
    protected $description = 'Configure MeiliSearch index settings (run once after deployment)';

    public function handle(EngineManager $engineManager): void
    {
        $engine = $engineManager->engine();

        // shows_index
        $task = $engine->index('shows_index')->updateSearchableAttributes(['title', 'foreign_title', 'genre_names', 'tag_names']);
        $engine->index('shows_index')->waitForTask($this->getTaskUid($task));
        $task = $engine->index('shows_index')->updateFilterableAttributes(['is_publish']);
        $engine->index('shows_index')->waitForTask($this->getTaskUid($task));
        $this->info('shows_index configured.');

        // instalments_index
        $task = $engine->index('instalments_index')->updateSearchableAttributes(['title', 'foreign_title']);
        $engine->index('instalments_index')->waitForTask($this->getTaskUid($task));
        $task = $engine->index('instalments_index')->updateFilterableAttributes(['is_publish']);
        $engine->index('instalments_index')->waitForTask($this->getTaskUid($task));
        $this->info('instalments_index configured.');

        // cast_index
        $task = $engine->index('cast_index')->updateSearchableAttributes(['name']);
        $engine->index('cast_index')->waitForTask($this->getTaskUid($task));
        $engine->index('cast_index')->updateSettings(['typoTolerance' => ['minWordSizeForTypos' => ['oneTypo' => 5, 'twoTypos' => 9]]]);
        $this->info('cast_index configured.');

        // directors_index
        $task = $engine->index('directors_index')->updateSearchableAttributes(['name']);
        $engine->index('directors_index')->waitForTask($this->getTaskUid($task));
        $engine->index('directors_index')->updateSettings(['typoTolerance' => ['minWordSizeForTypos' => ['oneTypo' => 5, 'twoTypos' => 9]]]);
        $this->info('directors_index configured.');

        $this->info('Done. You can now safely run scout:import for all models.');
    }

    private function getTaskUid(array $task): int
    {
        // Meilisearch v0.x uses 'uid', v1.x uses 'taskUid'
        return $task['taskUid'] ?? $task['uid'];
    }
}
