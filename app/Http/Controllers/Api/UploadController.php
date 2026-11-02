<?php

namespace App\Http\Controllers\Api;

use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UploadController extends BaseController
{
    protected UploadService $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function index(): JsonResponse
    {
        $res = $this->uploadService->all();
        return $this->sendResponse($res['message'], $res['data']);
    }

    public function show(string $id): JsonResponse
    {
        $res = $this->uploadService->find($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function store(Request $request): JsonResponse
    {
        // Check if cropped image uploaded via base64 or form-data
        if ($request->filled('image') && !($request->file('image') instanceof \Illuminate\Http\UploadedFile)) {
            $moduleId = $request->input('m_module_id');
            $name = $request->input('name', 'image_src');
            $res = $this->uploadService->uploadCroppedBase64($request->input('image'), $moduleId, $name);

            if (!$res['success']) {
                return $this->sendError($res['message'], [], 422);
            }
            return $this->sendResponse($res['message'], $res['data'], 201);
        }

        $file = $request->file('file') ?? $request->file('image') ?? $request->file('image_src');
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $moduleId = $request->input('m_module_id');
            $name = $request->input('name', 'image_src');
            $res = $this->uploadService->uploadFile($file, $moduleId, $name);

            if (!$res['success']) {
                return $this->sendError($res['message'], [], 422);
            }
            return $this->sendResponse($res['message'], $res['data'], 201);
        }

        // Default crud store fallback
        $res = $this->uploadService->create($request->all());
        return $this->sendResponse($res['message'], $res['data'], 201);
    }

    /**
     * Dedicated endpoint matching sample: api/v1/upload-file
     */
    public function uploadFile(Request $request): JsonResponse
    {
        $file = $request->file('file') ?? $request->file('image') ?? $request->file('image_src');
        if (!$file) {
            return $this->sendError('No file provided for upload.', [], 400);
        }

        $moduleId = $request->input('m_module_id');
        $name = $request->input('name', 'image_src');
        $res = $this->uploadService->uploadFile($file, $moduleId, $name);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 422);
        }

        return $this->sendResponse($res['message'], $res['data'], 200);
    }

    /**
     * Dedicated endpoint matching sample: api/v1/upload-cropped-image
     */
    public function uploadCroppedImage(Request $request): JsonResponse
    {
        $base64Data = $request->input('image');
        if (!$base64Data) {
            return $this->sendError('No cropped image provided.', [], 400);
        }

        $moduleId = $request->input('m_module_id');
        $name = $request->input('name', 'image_src');
        $res = $this->uploadService->uploadCroppedBase64($base64Data, $moduleId, $name);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 422);
        }

        return $this->sendResponse($res['message'], $res['data'], 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        // ponytail: no validation on update
        $res = $this->uploadService->update($id, $request->all());

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function destroy(string $id): JsonResponse
    {
        $res = $this->uploadService->delete($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message']);
    }
}