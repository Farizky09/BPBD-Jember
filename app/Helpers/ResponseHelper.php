<?php
namespace App\Helpers;
class ResponseHelper
{
    public static function success($message, $data = null, $statusCode = 200, $isPaginated = false)
    {
        if ($isPaginated) {

            return response()->json(
                [
                    'status' => 'success',
                    'message' => $message,
                    'data' => $data->items(),
                    'pagination' => [
                        'total' => $data->total(),
                        'current_page' => $data->currentPage(),
                        'last_page' => $data->lastPage(),
                        'per_page' => $data->perPage(),
                        'next_page_url' => $data->nextPageUrl(),
                        'prev_page_url' => $data->previousPageUrl(),
                        'from' => $data->firstItem(),
                        'to' => $data->lastItem()
                    ]
                ],
                $statusCode
            );
        }
        return response()->json(
            [
                'status' => 'success',
                'message' => $message,
                'data' => $data
            ],
            $statusCode
        );
    }
    public static function error($message, $reason = null, $statusCode = 500)
    {
        return response()->json(
            [
                'status' => 'error',
                'message' => $message,
                'reason' => $reason
            ],
            $statusCode
        );
    }
}
