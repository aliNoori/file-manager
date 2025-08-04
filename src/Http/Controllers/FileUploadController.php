<?php

namespace FileManager\Http\Controllers;

use FileManager\Contracts\FileManagerInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FileUploadController extends Controller
{
    public function __construct(protected FileManagerInterface $fileManager) {}

    /**
     * Handle file upload and associate files with a polymorphic model.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate incoming request
            $validated = $request->validate([
                'model_type' => 'required|string',
                'model_id' => 'required|integer',
                'files' => 'required|array',
                'files.*' => 'file',
            ]);

            // Resolve model class from alias
            $modelClass = $this->resolveModelClass($validated['model_type']);

            // Retrieve model instance by ID
            $model = $this->findModelInstance($modelClass, $validated['model_id']);

            // Delegate file handling to the FileManager service
            $storedFiles = $this->fileManager->handleRequestFiles($request, $validated['model_type']);

            // Persist file metadata to the database
            $saved = collect($storedFiles)->map(function ($fileData) use ($model) {
                return $model->files()->create([
                    'path' => $fileData['path'],
                    'original_name' => $fileData['original_name'],
                    'mime_type' => $fileData['mime_type'],
                    'size' => $fileData['size'],
                    'field' => $fileData['field'],
                ]);
            });

            // Return success response
            return response()->json([
                'success' => true,
                'message' => __('filemanager.upload_success'),
                'data' => $saved,
            ]);

        } catch (ValidationException $e) {
            // Return validation error response
            return response()->json([
                'success' => false,
                'message' => __('filemanager.validation_failed'),
                'errors' => $e->errors(),
            ], 422);

        } catch (ModelNotFoundException $e) {
            // Return model not found response
            return response()->json([
                'success' => false,
                'message' => __('filemanager.model_not_found'),
            ], 404);

        } catch (\Throwable $e) {
            // Return generic error response
            return response()->json([
                'success' => false,
                'message' => __('filemanager.upload_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resolve the fully qualified model class name from the alias.
     *
     * @param string $alias
     * @return string
     * @throws ValidationException
     */
    protected function resolveModelClass(string $alias): string
    {
        $modelMap = config('filemanager.model_map');

        if (!array_key_exists($alias, $modelMap)) {
            throw ValidationException::withMessages([
                'model_type' => [__('filemanager.invalid_model_type')],
            ]);
        }

        return $modelMap[$alias];
    }

    /**
     * Retrieve the model instance by ID or throw a ModelNotFoundException.
     *
     * @param string $modelClass
     * @param int $id
     * @return Model
     */
    protected function findModelInstance(string $modelClass, int $id): Model
    {
        return $modelClass::findOrFail($id);
    }
}
