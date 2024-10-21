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
    function uploadFile($file, $directory = 'uploads', $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'])
    {
        if ($file && $file->isValid()) {
            $extension = $file->getClientOriginalExtension();

            // Validate the file extension
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return false;
            }

            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs($directory, $fileName, 'public');
            return $path;
        }
        return false;
    }
}

