<?php

namespace FileManager\Http\Controllers;

use FileManager\Contracts\FileManagerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FileUploadController extends Controller
{
    protected FileManagerInterface $fileManager;

    public function __construct(FileManagerInterface $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'files' => 'required|array',
            'files.*' => 'file',
        ]);

        $modelAlias = $request->input('model_type');
        $modelMap = config('filemanager.model_map');

        if (!array_key_exists($modelAlias, $modelMap)) {
            return response()->json(['error' => 'Invalid model type'], 422);
        }

        $modelClass = $modelMap[$modelAlias];
        $model = $modelClass::findOrFail($request->input('model_id'));

        $storedFiles = $this->fileManager->handleRequestFiles($request, $modelAlias);

        $saved = [];

        foreach ($storedFiles as $fileData) {
            $saved[] = $model->files()->create([
                'path' => $fileData['path'],
                'original_name' => $fileData['original_name'],
                'mime_type' => $fileData['mime_type'],
                'size' => $fileData['size'],
                'field' => $fileData['field'],
            ]);
        }

        return response()->json($saved);
    }
}
