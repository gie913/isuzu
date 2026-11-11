<?php

namespace App\Http\Controllers\Api;

use App\Services\ContentDtlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentDtlController extends BaseController
{
    protected ContentDtlService $contentDtlService;

    public function __construct(ContentDtlService $contentDtlService)
    {
        $this->contentDtlService = $contentDtlService;
    }

    public function index(): JsonResponse
    {
        $res = $this->contentDtlService->all();
        return $this->sendResponse($res['message'], $res['data']);
    }

    public function show(string $id): JsonResponse
    {
        $res = $this->contentDtlService->find($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function store(Request $request): JsonResponse
    {
        // ponytail: no validation on create
        $res = $this->contentDtlService->create($request->all());
        return $this->sendResponse($res['message'], $res['data'], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        // ponytail: no validation on update
        $res = $this->contentDtlService->update($id, $request->all());

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function destroy(string $id): JsonResponse
    {
        $res = $this->contentDtlService->delete($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message']);
    }
}