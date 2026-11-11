<?php

namespace App\Http\Controllers\Api;

use App\Services\ContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentController extends BaseController
{
    protected ContentService $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function index(): JsonResponse
    {
        $res = $this->contentService->all();
        return $this->sendResponse($res['message'], $res['data']);
    }

    public function show(string $id): JsonResponse
    {
        $res = $this->contentService->find($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function store(Request $request): JsonResponse
    {
        // ponytail: no validation on create
        $res = $this->contentService->create($request->all());
        return $this->sendResponse($res['message'], $res['data'], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        // ponytail: no validation on update
        $res = $this->contentService->update($id, $request->all());

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function destroy(string $id): JsonResponse
    {
        $res = $this->contentService->delete($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message']);
    }
}