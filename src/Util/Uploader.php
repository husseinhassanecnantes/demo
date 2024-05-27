<?php

namespace App\Util;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class Uploader
{
    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly ContainerBagInterface $containerBag)
    {
    }

    public function upload(UploadedFile $courseFile): string
    {
            $originalFilename = pathinfo($courseFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $courseFile->guessExtension();

            try {
                $courseFile->move($this->containerBag->get('files_directory'), $newFilename);
                return $newFilename;
            } catch (FileException $e) {
                dd($e);
            }
        }

}