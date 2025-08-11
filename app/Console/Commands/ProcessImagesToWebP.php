<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageService;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ProcessImagesToWebP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:process-to-webp {--force : Force processing even if WebP already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process existing images to WebP format using ImageService';

    protected $imageService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ImageService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting image processing to WebP format...');

        // Check if directories exist
        $directories = ['items/original', 'items/processed', 'items/thumbnails'];
        foreach ($directories as $dir) {
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
                $this->info("Created directory: $dir");
            }
        }

        // Get all items with images
        $items = Item::whereNotNull('image')->where('image', '!=', '')->get();
        
        if ($items->isEmpty()) {
            $this->warn('No items with images found.');
            return 0;
        }

        $this->info("Found {$items->count()} items with images to process.");

        $bar = $this->output->createProgressBar($items->count());
        $bar->start();

        $processed = 0;
        $skipped = 0;
        $failed = 0;
        $errors = [];

        foreach ($items as $item) {
            try {
                $imagePath = $item->image;
                
                // Check if image already exists in processed format
                $processedPath = str_replace('/items/', '/items/processed/', $imagePath);
                $processedPath = pathinfo($processedPath, PATHINFO_FILENAME) . '.webp';
                
                if (!$this->option('force') && Storage::disk('public')->exists($processedPath)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Check if original image exists
                $originalPath = str_replace('/items/', '/items/original/', $imagePath);
                if (!Storage::disk('public')->exists($originalPath)) {
                    // Move existing image to original directory
                    if (Storage::disk('public')->exists($imagePath)) {
                        $content = Storage::disk('public')->get($imagePath);
                        Storage::disk('public')->put($originalPath, $content);
                    } else {
                        $failed++;
                        $errors[] = "Original image not found for item {$item->id}: {$imagePath}";
                        $bar->advance();
                        continue;
                    }
                }

                // Process image to WebP
                $result = $this->imageService->batchProcessToWebP('items');
                
                if ($result['processed'] > 0) {
                    $processed++;
                } else {
                    $failed++;
                    $errors = array_merge($errors, $result['errors']);
                }

            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Error processing item {$item->id}: " . $e->getMessage();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Display results
        $this->info("Processing completed!");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Processed', $processed],
                ['Skipped', $skipped],
                ['Failed', $failed],
            ]
        );

        if (!empty($errors)) {
            $this->error('Errors encountered:');
            foreach (array_slice($errors, 0, 10) as $error) {
                $this->line("  - $error");
            }
            if (count($errors) > 10) {
                $this->line("  ... and " . (count($errors) - 10) . " more errors.");
            }
        }

        if ($processed > 0) {
            $this->info("Successfully processed $processed images to WebP format!");
        }

        return $failed === 0 ? 0 : 1;
    }
}
