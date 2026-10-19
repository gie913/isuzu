<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Send success JSON response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse(string $message, mixed $data = [], int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, $code);
    }

    /**
     * Send error JSON response.
     *
     * @param string $error
     * @param mixed $messages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(string $error, mixed $messages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($messages)) {
            $response['errors'] = $messages;
        }

        return response()->json($response, $code);
    }
}
