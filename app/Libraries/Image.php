<?php

namespace App\Libraries;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;

class Image
{
    /**
     * The image instance.
     *
     * @var \Intervention\Image\Image
     */
    protected $image;

    /**
     * The encoded image.
     *
     * @var string
     */
    protected $encodedImage;

    /**
     * Create a new image instance.
     *
     * @param  mixed  $image
     */
    public function __construct($image)
    {
        $this->image = $this->getImage($image);
    }

    /**
     * Encode the image.
     *
     * @param  string|null  $type
     * @param  integer  $quality
     * @return string
     */
    public function encode($type = 'jpg', $quality = 90)
    {
        return $this->encodedImage = (string) $this->image
            ->interlace()
            ->encode($type, $quality);
    }

    /**
     * Write the image to the filesystem.
     *
     * @param  string  $path
     * @param  mixed  $options
     * @return bool
     */
    public function put(string $path, $options = [])
    {
        $encodedImage = $this->encodedImage ?? $this->encode();

        return Storage::put($path, $encodedImage, $options);
    }

     /**
     * Get the image instance.
     *
     * @param  mixed  $image
     * @return \Intervention\Image\Image
     */
    public function getImage($image)
    {
        $imagePath = $image instanceof UploadedFile
            ? $image->getRealPath()
            : $image;

        return ImageManagerStatic::make($imagePath);
    }
}
