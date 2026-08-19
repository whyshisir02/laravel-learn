<?php

namespace App\Services;


use Illuminate\Http\UploadedFile;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;


class ImageOptimizerService
{
    protected ImageManager $manager;

    public function __construct()
    {
        // Initializes Intervention with the GD driver
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Optimizes, strips metadata, converts to WebP, and stores an image.
     */
    public function optimizeAndStore(
        UploadedFile $file,
        string $directory = 'avatars',
        int $maxWidth = 1200,
        int $maxHeight = 1200,
        int $quality = 80
    ): string {
        
        // 1. Read the uploaded file into memory
        $image = $this->manager->decodePath($file->getRealPath());

        // 2. Downscale only if dimensions exceed limits (preserves aspect ratio)
        $image->scaleDown(width: $maxWidth, height: $maxHeight);

        // 3. Re-encode to WebP (automatically strips EXIF/GPS metadata and compresses)
        $encoded = $image->encode(new WebpEncoder(quality: $quality, strip: true));

        // 4. Create a unique path and save to disk
        $filename = Str::random(40) . '.webp';
        $filePath = trim($directory, '/') . '/' . $filename;

        Storage::disk('public')->put($filePath, (string) $encoded);

        return $filePath;
    }
}
