<?php

namespace App\Services;

use App\Exceptions\ReadFileException;

class FileManagerService implements InputReaderInterface
{
    private string $fileContent;

    public function __construct(private string $filePath)
    {
    }

    /**
     * @throws ReadFileException
     */
    public function read(): string
    {
        $content = file_get_contents($this->filePath);

        if (!$content) {
            throw new ReadFileException('Cannot read file');
        }



        return $this->fileContent = $content;
    }
}