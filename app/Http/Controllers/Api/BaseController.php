<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected function success($data)
    {
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    protected function error($message, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}
