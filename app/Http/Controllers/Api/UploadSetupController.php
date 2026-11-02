<?php

namespace App\Http\Controllers\Api;

use App\Services\UploadSetupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UploadSetupController extends BaseController
{
    protected UploadSetupService $uploadSetupService;

    public function __construct(UploadSetupService $uploadSetupService)
    {
        $this->uploadSetupService = $uploadSetupService;
    }

    public function index(): JsonResponse
    {
        $res = $this->uploadSetupService->all();
        return $this->sendResponse($res['message'], $res['data']);
    }

    public function show(string $id): JsonResponse
    {
        $res = $this->uploadSetupService->find($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    /**
     * Get upload configuration by module ID/code and field name.
     */
    public function getConfiguration(Request $request): JsonResponse
    {
        $moduleId = $request->input('m_module_id');
        $name = $request->input('name');

        if (empty($name)) {
            return $this->sendError('Field name parameter is required.', [], 400);
        }

        $res = $this->uploadSetupService->findByModuleIdByName($moduleId, $name);
        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function store(Request $request): JsonResponse
    {
        // ponytail: no validation on create
        $res = $this->uploadSetupService->create($request->all());
        return $this->sendResponse($res['message'], $res['data'], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        // ponytail: no validation on update
        $res = $this->uploadSetupService->update($id, $request->all());

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function destroy(string $id): JsonResponse
    {
        $res = $this->uploadSetupService->delete($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message']);
    }
}