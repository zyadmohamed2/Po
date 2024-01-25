<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait HelperApi
{


    protected function onError(int $code, string $message = '', $error = null)
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'errors' => $error
        ], $code);
    }

    protected function onSuccess(int $code, string $message = '', $data = null): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function onSuccessWithPaginate(int $code, string $message = '', $data = null, $pagination = null): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
            'pagination' => $pagination
        ], $code);
    }

    protected function onSuccessWithToken(int $code, string $message = '', $data = null, $token): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
            'token' => $token
        ], $code);
    }

    protected function pagination($data)
    {
        return [
            'current_page' => $data['current_page'],
            'per_page' => $data['per_page'],
            'from' => $data['from'],
            'to' => $data['to'],
            'prev_page' => $data['links'][0]['url'],
            'next_page' => $data['links'][1]['url'],
            'total' => $data['total']
        ];
    }
    public function onSuccessWithPagination(int $code, string $message = '', $data = null, $pagination)
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
            'pagination' => $pagination
        ], $code);
    }
}
