<?php

namespace App\Libraries;

use CodeIgniter\Files\File;

class FileUploader
{
    protected $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    protected $maxSize = 2048; // Maximum file size in kilobytes

    public function upload($file, $uploadPath)
    {
        if ($file->isValid()) {
            if ($this->validateFileType($file) && $this->validateFileSize($file)) {
                $newName = $file->getRandomName();
                if ($file->move($uploadPath, $newName)) {
                    return [
                        'status' => true,
                        'fileName' => $newName,
                        'message' => 'File uploaded successfully.',
                    ];
                } else {
                    return [
                        'status' => false,
                        'message' => 'File move failed.',
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'Invalid file type or file size exceeds limit.',
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => 'File is not valid.',
            ];
        }
    }

    protected function validateFileType(File $file)
    {
        return in_array($file->getMimeType(), $this->allowedTypes);
    }

    protected function validateFileSize(File $file)
    {
        return $file->getSize() <= $this->maxSize * 1024;
    }
}
