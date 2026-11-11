<?php

namespace App\Http\Controllers\Api;

use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends BaseController
{
    protected MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(): JsonResponse
    {
        $res = $this->messageService->all();
        return $this->sendResponse($res['message'], $res['data']);
    }

    public function show(string $id): JsonResponse
    {
        $res = $this->messageService->find($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function store(Request $request): JsonResponse
    {
        // ponytail: no validation on create
        $res = $this->messageService->create($request->all());
        return $this->sendResponse($res['message'], $res['data'], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        // ponytail: no validation on update
        $res = $this->messageService->update($id, $request->all());

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message'], $res['data']);
    }

    public function destroy(string $id): JsonResponse
    {
        $res = $this->messageService->delete($id);

        if (!$res['success']) {
            return $this->sendError($res['message'], [], 404);
        }

        return $this->sendResponse($res['message']);
    }
}