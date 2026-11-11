<?php

namespace App\Http\Controllers\Api;

use App\Services\PageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends BaseController
{
    protected PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function index(): JsonResponse
    {
        $res = $this->pageService->all();
        return $this->sendResponse($res['message'], $res['data']);
    }

    public function show(string $id): JsonResponse
    {
        $res = $this->pageService->find($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function store(Request $request): JsonResponse
    {
        // ponytail: no validation on create
        $res = $this->pageService->create($request->all());
        return $this->sendResponse($res['message'], $res['data'], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        // ponytail: no validation on update
        $res = $this->pageService->update($id, $request->all());

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function destroy(string $id): JsonResponse
    {
        $res = $this->pageService->delete($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message']);
    }
}