<?php

namespace App\Helpers;

use App\Services\ImageService;

class ImageHelper
{
    protected static $imageService;

    public static function getImageService()
    {
        if (!self::$imageService) {
            self::$imageService = app(ImageService::class);
        }
        return self::$imageService;
    }

    /**
     * Get image URL for display
     *
     * @param string $path
     * @param string $type
     * @return string|null
     */
    public static function getImageUrl($path, $type = 'processed')
    {
        if (empty($path)) {
            return null;
        }

        return self::getImageService()->getImageUrl($path, $type);
    }

    /**
     * Get thumbnail URL
     *
     * @param string $path
     * @return string|null
     */
    public static function getThumbnailUrl($path)
    {
        return self::getImageUrl($path, 'thumbnail');
    }

    /**
     * Get original image URL
     *
     * @param string $path
     * @return string|null
     */
    public static function getOriginalUrl($path)
    {
        return self::getImageUrl($path, 'original');
    }

    /**
     * Check if image exists
     *
     * @param string $path
     * @return bool
     */
    public static function imageExists($path)
    {
        if (empty($path)) {
            return false;
        }

        return self::getImageService()->imageExists($path);
    }

    /**
     * Get image dimensions
     *
     * @param string $path
     * @return array|null
     */
    public static function getImageDimensions($path)
    {
        if (empty($path)) {
            return null;
        }

        return self::getImageService()->getImageDimensions($path);
    }

    /**
     * Generate responsive image HTML with fallback
     *
     * @param string $path
     * @param string $alt
     * @param array $attributes
     * @return string
     */
    public static function responsiveImage($path, $alt = '', $attributes = [])
    {
        if (empty($path)) {
            return self::getPlaceholderHtml($alt, $attributes);
        }

        $processedUrl = self::getImageUrl($path, 'processed');
        $thumbnailUrl = self::getImageUrl($path, 'thumbnail');
        $originalUrl = self::getImageUrl($path, 'original');

        $defaultAttributes = [
            'class' => 'img-fluid',
            'alt' => $alt,
            'loading' => 'lazy'
        ];

        $attributes = array_merge($defaultAttributes, $attributes);
        $attributeString = self::buildAttributeString($attributes);

        $html = '<picture>';
        
        // WebP version
        $html .= '<source srcset="' . $processedUrl . '" type="image/webp">';
        
        // Fallback to original format
        if ($originalUrl) {
            $html .= '<source srcset="' . $originalUrl . '" type="image/jpeg">';
        }
        
        // Default img tag
        $html .= '<img src="' . $processedUrl . '" ' . $attributeString . '>';
        
        $html .= '</picture>';

        return $html;
    }

    /**
     * Generate thumbnail HTML
     *
     * @param string $path
     * @param string $alt
     * @param array $attributes
     * @return string
     */
    public static function thumbnail($path, $alt = '', $attributes = [])
    {
        if (empty($path)) {
            return self::getPlaceholderHtml($alt, $attributes, 'thumbnail');
        }

        $thumbnailUrl = self::getImageUrl($path, 'thumbnail');
        $processedUrl = self::getImageUrl($path, 'processed');

        $defaultAttributes = [
            'class' => 'img-thumbnail',
            'alt' => $alt,
            'loading' => 'lazy'
        ];

        $attributes = array_merge($defaultAttributes, $attributes);
        $attributeString = self::buildAttributeString($attributes);

        $html = '<picture>';
        $html .= '<source srcset="' . $thumbnailUrl . '" type="image/webp">';
        $html .= '<img src="' . $processedUrl . '" ' . $attributeString . '>';
        $html .= '</picture>';

        return $html;
    }

    /**
     * Get placeholder HTML for missing images
     *
     * @param string $alt
     * @param array $attributes
     * @param string $type
     * @return string
     */
    protected static function getPlaceholderHtml($alt = '', $attributes = [], $type = 'default')
    {
        $defaultAttributes = [
            'class' => 'img-fluid',
            'alt' => $alt ?: 'No image available'
        ];

        $attributes = array_merge($defaultAttributes, $attributes);
        $attributeString = self::buildAttributeString($attributes);

        $sizes = [
            'thumbnail' => 'width="150" height="150"',
            'default' => 'width="300" height="200"'
        ];

        $sizeAttr = $sizes[$type] ?? $sizes['default'];

        return '<div class="bg-light d-flex align-items-center justify-content-center" ' . $sizeAttr . ' style="border: 2px dashed #dee2e6;">' .
               '<div class="text-center">' .
               '<i class="fas fa-image fa-3x text-muted mb-2"></i>' .
               '<p class="text-muted mb-0 small">No image</p>' .
               '</div>' .
               '</div>';
    }

    /**
     * Build HTML attribute string
     *
     * @param array $attributes
     * @return string
     */
    protected static function buildAttributeString($attributes)
    {
        $parts = [];
        foreach ($attributes as $key => $value) {
            $parts[] = $key . '="' . htmlspecialchars($value) . '"';
        }
        return implode(' ', $parts);
    }

    /**
     * Get file size in human readable format
     *
     * @param int $bytes
     * @return string
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes === 0) {
            return '0 Bytes';
        }

        $units = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes, 1024));
        
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }

    /**
     * Check if browser supports WebP
     *
     * @return bool
     */
    public static function supportsWebP()
    {
        if (!isset($_SERVER['HTTP_ACCEPT'])) {
            return false;
        }

        return strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
    }
}
