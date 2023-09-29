<?php

namespace App\Services;

use App\Entity\Image;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $file, ?string $folder = '')
    {
        $image = new Image();

        $extension = $this->getExtensionFromMime($file->getMimeType());
        $fileName = md5(uniqid(rand(), true)) . $extension;
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $path = $this->params->get('images_directory') . $folder;


        $image->setName($fileName);
        $image->setOriginalName($originalFileName);
        $image->setPath($path);


        $file->move($path . '/', $fileName);

        return $image;
    }

    public function delete(string $fileName, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if($fileName !== 'default'){
            $path = $this->params->get('images_directory') . $folder;

            $original = $path . '/' . $fileName;

            if(file_exists($original)){
                unlink($original);
                return true;
            }
            return false;
        }
        return false;
    }

    private function getExtensionFromMime(string $mime): string
    {
        return match ($mime) {
            'image/png' => '.png',
            'image/jpeg' => '.jpg',
            'image/webp' => '.webp',
            default => throw new Exception('Nieobsługiwany format obrazu: ' . $mime),
        };
    }
}