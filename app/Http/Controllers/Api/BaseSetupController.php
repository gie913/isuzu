<?php

namespace App\Http\Controllers\Api;

use App\Services\BaseSetupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BaseSetupController extends BaseController
{
    protected BaseSetupService $baseSetupService;

    public function __construct(BaseSetupService $baseSetupService)
    {
        $this->baseSetupService = $baseSetupService;
    }

    public function index(): JsonResponse
    {
        if (!$this->baseSetupService->can('can_read')) {
            return $this->sendError('Unauthorized access.', [], 403);
        }

        $res = $this->baseSetupService->all();
        return $this->sendResponse($res['message'], $res['data']);
    }

    public function show(string $id): JsonResponse
    {
        if (!$this->baseSetupService->can('can_read')) {
            return $this->sendError('Unauthorized access.', [], 403);
        }

        $res = $this->baseSetupService->find($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function store(Request $request): JsonResponse
    {
        if (!$this->baseSetupService->can('can_create')) {
            return $this->sendError('Unauthorized access.', [], 403);
        }

        // ponytail: no validation on create
        $res = $this->baseSetupService->create($request->all());
        return $this->sendResponse($res['message'], $res['data'], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        if (!$this->baseSetupService->can('can_update')) {
            return $this->sendError('Unauthorized access.', [], 403);
        }

        // ponytail: no validation on update
        $res = $this->baseSetupService->update($id, $request->all());

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function destroy(string $id): JsonResponse
    {
        if (!$this->baseSetupService->can('can_delete')) {
            return $this->sendError('Unauthorized access.', [], 403);
        }

        $res = $this->baseSetupService->delete($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message']);
    }
}