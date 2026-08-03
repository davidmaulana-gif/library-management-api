<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function Success($message, $data = null, $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
