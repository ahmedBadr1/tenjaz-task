<?php

if (!function_exists('successResponse')) {
    function successResponse( $data = [],$message = 'Success', $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}

if (!function_exists('errorResponse')) {
    function errorResponse($message = 'An error occurred', $statusCode = 400, $errors = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($file, $directory)
    {
        if ($file && $file->isValid()) {
            // generating file name using timestamp and original name
            $fileName = time() . '_' . $file->getClientOriginalName();
            return $file->storeAs($directory, $fileName, 'public'); // 'public' disk is being used
            // This will return something like "avatars/filename.jpg"
        }
        return false;
    }
}

