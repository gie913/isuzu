<?php

namespace App\Http\Controllers\Api;

use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends BaseController
{
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index(): JsonResponse
    {
        $res = $this->menuService->all();
        return $this->sendResponse($res['message'], $res['data']);
    }

    public function show(string $id): JsonResponse
    {
        $res = $this->menuService->find($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function store(Request $request): JsonResponse
    {
        // ponytail: no validation on create
        $res = $this->menuService->create($request->all());
        return $this->sendResponse($res['message'], $res['data'], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        // ponytail: no validation on update
        $res = $this->menuService->update($id, $request->all());

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function destroy(string $id): JsonResponse
    {
        $res = $this->menuService->delete($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message']);
    }
}