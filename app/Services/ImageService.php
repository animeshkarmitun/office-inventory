<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception;
use Log;

class ImageService
{
    protected $manager;
    protected $quality = 80;
    protected $maxWidth = 1920;
    protected $maxHeight = 1080;
    protected $thumbnailWidth = 300;
    protected $thumbnailHeight = 300;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Process and store an uploaded image
     *
     * @param UploadedFile $image
     * @param string $directory
     * @param array $options
     * @return array
     */
    public function processAndStore(UploadedFile $image, string $directory = 'items', array $options = [])
    {
        try {
            // Validate image
            $this->validateImage($image);

            // Generate unique filename
            $filename = $this->generateFilename($image);
            
            // Set up paths
            $originalPath = $directory . '/original/' . $filename;
            $processedPath = $directory . '/processed/' . $filename . '.webp';
            $thumbnailPath = $directory . '/thumbnails/' . $filename . '_thumb.webp';

            // Store original image
            $originalStored = Storage::disk('public')->put($originalPath, file_get_contents($image));

            if (!$originalStored) {
                throw new Exception('Failed to store original image');
            }

            // Process image for WebP
            $processedImage = $this->processImage($image, $options);
            
            // Store processed WebP image
            $processedStored = Storage::disk('public')->put($processedPath, $processedImage);

            if (!$processedStored) {
                throw new Exception('Failed to store processed image');
            }

            // Generate and store thumbnail
            $thumbnailImage = $this->createThumbnail($image);
            $thumbnailStored = Storage::disk('public')->put($thumbnailPath, $thumbnailImage);

            if (!$thumbnailStored) {
                throw new Exception('Failed to store thumbnail');
            }

            return [
                'success' => true,
                'original_path' => $originalPath,
                'processed_path' => $processedPath,
                'thumbnail_path' => $thumbnailPath,
                'filename' => $filename,
                'size' => $image->getSize(),
                'mime_type' => $image->getMimeType()
            ];

        } catch (Exception $e) {
            Log::error('Image processing failed: ' . $e->getMessage(), [
                'file' => $image->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Process image for WebP format
     *
     * @param UploadedFile $image
     * @param array $options
     * @return string
     */
    protected function processImage(UploadedFile $image, array $options = [])
    {
        $quality = $options['quality'] ?? $this->quality;
        $maxWidth = $options['max_width'] ?? $this->maxWidth;
        $maxHeight = $options['max_height'] ?? $this->maxHeight;

        $img = $this->manager->read($image->getPathname());

        // Resize if image is too large
        if ($img->width() > $maxWidth || $img->height() > $maxHeight) {
            $img = $img->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Convert to WebP
        return $img->toWebp($quality);
    }

    /**
     * Create thumbnail from image
     *
     * @param UploadedFile $image
     * @return string
     */
    protected function createThumbnail(UploadedFile $image)
    {
        $img = $this->manager->read($image->getPathname());

        // Resize to thumbnail size
        $img = $img->resize($this->thumbnailWidth, $this->thumbnailHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Convert to WebP with lower quality for thumbnails
        return $img->toWebp(70);
    }

    /**
     * Update existing image
     *
     * @param UploadedFile $newImage
     * @param string $oldProcessedPath
     * @param string $directory
     * @return array
     */
    public function updateImage(UploadedFile $newImage, string $oldProcessedPath, string $directory = 'items')
    {
        try {
            // Delete old images
            $this->deleteImage($oldProcessedPath);

            // Process and store new image
            return $this->processAndStore($newImage, $directory);

        } catch (Exception $e) {
            Log::error('Image update failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete image and related files
     *
     * @param string $processedPath
     * @return bool
     */
    public function deleteImage(string $processedPath)
    {
        try {
            // Extract filename from processed path
            $filename = basename($processedPath, '.webp');
            
            // Delete original
            $originalPath = str_replace('/processed/', '/original/', $processedPath);
            $originalPath = str_replace('.webp', '', $originalPath);
            Storage::disk('public')->delete($originalPath);

            // Delete processed
            Storage::disk('public')->delete($processedPath);

            // Delete thumbnail
            $thumbnailPath = str_replace('/processed/', '/thumbnails/', $processedPath);
            $thumbnailPath = str_replace('.webp', '_thumb.webp', $thumbnailPath);
            Storage::disk('public')->delete($thumbnailPath);

            return true;

        } catch (Exception $e) {
            Log::error('Image deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate unique filename
     *
     * @param UploadedFile $image
     * @return string
     */
    protected function generateFilename(UploadedFile $image)
    {
        $extension = $image->getClientOriginalExtension();
        $baseName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitizedName = Str::slug($baseName);
        
        return $sanitizedName . '_' . uniqid() . '.' . $extension;
    }

    /**
     * Validate uploaded image
     *
     * @param UploadedFile $image
     * @throws Exception
     */
    protected function validateImage(UploadedFile $image)
    {
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($image->getMimeType(), $allowedMimes)) {
            throw new Exception('Invalid image format. Allowed formats: JPEG, PNG, GIF, WebP');
        }

        if ($image->getSize() > $maxSize) {
            throw new Exception('Image size too large. Maximum size: 2MB');
        }
    }

    /**
     * Get image URL for display
     *
     * @param string $path
     * @param string $type
     * @return string
     */
    public function getImageUrl(string $path, string $type = 'processed')
    {
        if (empty($path)) {
            return null;
        }

        // If path already contains the type, return as is
        if (strpos($path, '/processed/') !== false || strpos($path, '/thumbnails/') !== false) {
            return asset('storage/' . $path);
        }

        // Convert to appropriate type
        switch ($type) {
            case 'thumbnail':
                $path = str_replace('/processed/', '/thumbnails/', $path);
                $path = str_replace('.webp', '_thumb.webp', $path);
                break;
            case 'original':
                $path = str_replace('/processed/', '/original/', $path);
                $path = str_replace('.webp', '', $path);
                break;
            default:
                // processed (default)
                break;
        }

        return asset('storage/' . $path);
    }

    /**
     * Check if image exists
     *
     * @param string $path
     * @return bool
     */
    public function imageExists(string $path)
    {
        return Storage::disk('public')->exists($path);
    }

    /**
     * Get image dimensions
     *
     * @param string $path
     * @return array|null
     */
    public function getImageDimensions(string $path)
    {
        try {
            $fullPath = Storage::disk('public')->path($path);
            
            if (!file_exists($fullPath)) {
                return null;
            }

            $img = $this->manager->read($fullPath);
            
            return [
                'width' => $img->width(),
                'height' => $img->height()
            ];

        } catch (Exception $e) {
            Log::error('Failed to get image dimensions: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Batch process existing images to WebP
     *
     * @param string $directory
     * @return array
     */
    public function batchProcessToWebP(string $directory = 'items')
    {
        $results = [
            'processed' => 0,
            'failed' => 0,
            'errors' => []
        ];

        try {
            $files = Storage::disk('public')->files($directory . '/original');

            foreach ($files as $file) {
                $filename = basename($file);
                $extension = pathinfo($filename, PATHINFO_EXTENSION);

                // Skip if already WebP
                if ($extension === 'webp') {
                    continue;
                }

                // Create temporary file for processing
                $tempPath = Storage::disk('public')->path($file);
                
                if (!file_exists($tempPath)) {
                    $results['failed']++;
                    $results['errors'][] = "File not found: $file";
                    continue;
                }

                try {
                    $img = $this->manager->read($tempPath);
                    
                    // Process and store as WebP
                    $webpContent = $img->toWebp($this->quality);
                    $webpPath = $directory . '/processed/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                    
                    Storage::disk('public')->put($webpPath, $webpContent);
                    
                    $results['processed']++;

                } catch (Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Failed to process $file: " . $e->getMessage();
                }
            }

        } catch (Exception $e) {
            $results['errors'][] = "Batch processing failed: " . $e->getMessage();
        }

        return $results;
    }
}
