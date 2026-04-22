<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    public function upload(UploadedFile $file, array $options = []): string
    {
        $result = $this->client()->uploadApi()->upload(
            $file->getRealPath(),
            $options
        );

        return $result['secure_url'];
    }

    protected function client(): Cloudinary
    {
        $cloudinaryUrl = config('services.cloudinary.url');

        if (!empty($cloudinaryUrl)) {
            return new Cloudinary($cloudinaryUrl);
        }

        return new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key' => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }
}
