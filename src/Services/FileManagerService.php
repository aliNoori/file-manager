<?php

namespace FileManager\Services;

use FileManager\Contracts\FileManagerInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileManagerService implements FileManagerInterface
{
    protected string $disk;

    public function __construct()
    {
        $this->disk = config('filemanager.disk', 'local');
    }

    public function handleRequestFiles($request, string $context = null): array
    {
        $stored = [];
        $index = 1;

        foreach ($request->file() as $key => $fileOrFiles) {
            $files = is_array($fileOrFiles) ? $fileOrFiles : [$fileOrFiles];

            foreach ($files as $file) {

                if ($file instanceof UploadedFile) {
                    $field = $key ?: 'file_' . $index++;

                    $folder = $context ? "{$context}/{$field}" : $field;

                    $path = Storage::disk($this->disk)->putFile($folder, $file);

                    $stored[] = [
                        'field' => $field,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'path' => $path,
                    ];
                }
            }
        }

        return $stored;
    }
}
