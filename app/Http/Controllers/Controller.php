<?php

namespace App\Http\Controllers;

use App\Exceptions\ClientException;
use Exception;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    public function successWithData(mixed $data, string $message = 'success', int $status = 200): JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'message' => $message,
                'data' => $data,
            ],
            $status
        );
    }

    /**
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function success(string $message = 'success', int $status = 200): JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'message' => $message,
            ],
            $status
        );
    }

    /**
     * @param Exception $errors
     * @param int $status
     * @return JsonResponse
     */
    public function error(Exception $errors): JsonResponse
    {
        $status = $errors instanceof ClientException ? $errors->getStatus() : 500;
        $errorName = $errors instanceof ClientException ? $errors->getName() : 'ServerException';

        $message = ($errorName !== 'ServerException' || config('app.env') === 'local')
            ? $errors->getMessage()
            : 'There was an error on the server, please try again later or contact the administrator';

        return response()->json(
            [
                'success' => false,
                'message' => $message,
                'error' => [
                    'name' => $errorName,
                    'code' => $errors->getCode(),
                ],
            ],
            $status
        );
    }

    /**
     * @param array $response
     * @return JsonResponse
     */
    public function customResponse(array $response, int $status): JsonResponse
    {
        return response()->json($response, $status);
    }
}
