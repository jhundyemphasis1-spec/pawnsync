<?php

namespace Database\Seeders;

use App\Models\ScrapboardRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ScrapboardRecordDocSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/scrapboard_records_from_docx.json');

        if (! File::exists($path)) {
            $this->command?->warn('Skipped scrapboard record seeding: JSON file not found.');
            return;
        }

        $raw = File::get($path);
        $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw ?? '');

        $rows = json_decode($raw, true);

        if (! is_array($rows)) {
            $this->command?->warn('Skipped scrapboard record seeding: invalid JSON structure.');
            return;
        }

        $now = now();
        $records = collect($rows)
            ->filter(function ($row): bool {
                return is_array($row)
                    && isset($row['code'], $row['classification'])
                    && in_array($row['classification'], ['A1', 'A2', 'A3', 'A4', 'A5'], true);
            })
            ->map(function ($row) use ($now): array {
                return [
                    'code' => trim((string) $row['code']),
                    'classification' => (string) $row['classification'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })
            ->filter(fn ($row): bool => $row['code'] !== '')
            ->values()
            ->all();

        if ($records === []) {
            $this->command?->warn('Skipped scrapboard record seeding: no valid rows.');
            return;
        }

        ScrapboardRecord::upsert(
            $records,
            ['code'],
            ['classification', 'updated_at']
        );

        $this->command?->info('Scrapboard records seeded from DOCX dataset: '.count($records).' rows.');
    }
}
