<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddHasFactoryTrait extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'models:add-has-factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add HasFactory trait to all models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Adding HasFactory trait to all models...');
        $this->newLine();

        $modelsPath = app_path('Models');
        $modelFiles = File::files($modelsPath);

        $updatedCount = 0;
        $skippedCount = 0;

        foreach ($modelFiles as $file) {
            $fileName = $file->getFilename();
            $filePath = $file->getPathname();

            // Skip if it's not a PHP file
            if (! str_ends_with($fileName, '.php')) {
                continue;
            }

            $content = File::get($filePath);

            // Skip if already has HasFactory
            if (str_contains($content, 'use HasFactory;') || str_contains($content, 'HasFactory')) {
                $this->line("⏭️  Skipped {$fileName} (already has HasFactory)");
                $skippedCount++;

                continue;
            }

            // Skip if it's not a Model class
            if (! str_contains($content, 'extends Model')) {
                $this->line("⏭️  Skipped {$fileName} (not a Model class)");
                $skippedCount++;

                continue;
            }

            $updatedContent = $this->addHasFactoryTrait($content);

            if ($updatedContent !== $content) {
                File::put($filePath, $updatedContent);
                $this->line("✅ Updated {$fileName}");
                $updatedCount++;
            } else {
                $this->line("⏭️  Skipped {$fileName} (no changes needed)");
                $skippedCount++;
            }
        }

        $this->newLine();
        $this->info('🎉 Completed!');
        $this->info("✅ Updated: {$updatedCount} models");
        $this->info("⏭️  Skipped: {$skippedCount} models");
    }

    /**
     * Add HasFactory trait to model content
     */
    private function addHasFactoryTrait(string $content): string
    {
        // Add use statement for HasFactory
        if (! str_contains($content, 'use Illuminate\Database\Eloquent\Factories\HasFactory;')) {
            // Find the last use statement
            $usePattern = '/use [^;]+;/';
            preg_match_all($usePattern, $content, $matches);

            if (! empty($matches[0])) {
                $lastUse = end($matches[0]);
                $content = str_replace($lastUse, $lastUse."\nuse Illuminate\Database\Eloquent\Factories\HasFactory;", $content);
            }
        }

        // Add trait to class
        if (! str_contains($content, 'use HasFactory;')) {
            // Find the class declaration
            $classPattern = '/(class \w+ extends Model\s*\{)/';
            $content = preg_replace($classPattern, '$1'."\n\tuse HasFactory;\n", $content);
        }

        return $content;
    }
}
