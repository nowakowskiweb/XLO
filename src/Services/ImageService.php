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

        $fileName = md5(uniqid(rand(), true));
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $image->setName($fileName);
        $image->setOriginalName($originalFileName);


        $path = $this->params->get('images_directory') . $folder;
        $file->move($path . '/', $fileName . $this->getExtensionFromMime($file->getMimeType()));

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
        switch ($mime) {
            case 'image/png':
                return '.png';
            case 'image/jpeg':
                return '.jpg';
            case 'image/webp':
                return '.webp';
            default:
                throw new Exception('Nieobsługiwany format obrazu: ' . $mime);
        }
    }
}