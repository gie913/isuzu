<?php

namespace App\Http\Controllers\Api;

use App\Services\ModuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModuleController extends BaseController
{
    protected ModuleService $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function index(): JsonResponse
    {
        $res = $this->moduleService->all();
        return $this->sendResponse($res['message'], $res['data']);
    }

    public function show(string $id): JsonResponse
    {
        $res = $this->moduleService->find($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:225',
            'label'          => 'required|string|max:225',
            'resource_class' => 'nullable|string',
            'path'           => 'nullable|string',
            'icon'           => 'nullable|string',
            'sort_number'    => 'nullable|numeric',
            'parent_id'      => 'nullable|string|exists:m_module,id',
            'is_menubar'     => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors()->toArray(), 422);
        }

        $validated = $validator->validated();
        if ($request->has('is_menubar')) {
            $validated['is_menubar'] = $request->boolean('is_menubar');
        }

        $res = $this->moduleService->create($validated);
        return $this->sendResponse($res['message'], $res['data'], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'sometimes|string|max:225',
            'label'          => 'sometimes|string|max:225',
            'resource_class' => 'nullable|string',
            'path'           => 'nullable|string',
            'icon'           => 'nullable|string',
            'sort_number'    => 'nullable|numeric',
            'parent_id'      => 'nullable|string|exists:m_module,id',
            'is_menubar'     => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors()->toArray(), 422);
        }

        $validated = $validator->validated();
        if ($request->has('is_menubar')) {
            $validated['is_menubar'] = $request->boolean('is_menubar');
        }

        $res = $this->moduleService->update($id, $validated);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function destroy(string $id): JsonResponse
    {
        $res = $this->moduleService->delete($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message']);
    }
}