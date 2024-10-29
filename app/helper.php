<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;


/*--------------- custom api response messages starts-------------------------------*/
if (!function_exists('api_success')) {
    /**
     * Generate a success response
     *
     * @param string $message
     * @param mixed|null $data
     * @param array $additional
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function api_success($message, $data = null, $additional = [], $code = 200)
    {
        $response = [
            'status' => true,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json(array_merge($response, $additional), $code);
    }
}

if (!function_exists('api_error')) {
    /**
     * Generate an error response
     *
     * @param string $message
     * @param mixed|null $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function api_error($message, $data = null, $code = 500)
    {
        $response = [
            'status' => false,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['error'] = $data;
        }

        return response()->json($response, $code);
    }
}

if (!function_exists('api_not_found')) {
    /**
     * Generate a not found response
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function api_not_found($message = 'Resource not found')
    {
        return api_error($message, null, 404);
    }
}

if (!function_exists('api_unauthorized')) {
    /**
     * Generate an unauthorized response
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function api_unauthorized($message = 'Unauthorized')
    {
        return api_error($message, null, 401);
    }
}

if (!function_exists('api_exception')) {
    /**
     * Handle exception and return appropriate response
     *
     * @param \Exception $e
     * @param string $defaultMessage
     * @return \Illuminate\Http\JsonResponse
     */
    function api_exception(\Exception $e, $defaultMessage = 'An error occurred')
    {
        $message = config('app.debug') ? $e->getMessage() : $defaultMessage;
        return api_error($message, null, 500);
    }
}

/*-------------- custom api response messages  ends-------------------*/


if (!function_exists('store_image')) {
    /**
     * Store image and return filename
     * @param $image
     * @param string $path
     * @return string|null
     */
    function store_image($image, $path = 'uploads')
    {
        if (!$image) return null;

        // Create directory if it doesn't exist
        $storage_path = storage_path("app/public/{$path}");
        if (!file_exists($storage_path)) {
            mkdir($storage_path, 0755, true);
        }

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        // Move image to storage
        $image->move($storage_path, $filename);

        return $filename;
    }
}

if (!function_exists('delete_image')) {
    /**
     * Delete image from storage
     * @param string|null $filename
     * @param string $path
     * @return bool
     */
    function delete_image($filename, $path = 'uploads')
    {
        if (!$filename) return false;

        $filepath = storage_path("app/public/{$path}/{$filename}");
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
}


function generateStrongPassword($length = 12)
{
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $numbers = '0123456789';
    $specialChars = '!@#$%^&*()-_=+';

    // Ensure at least one character from each category
    $password = [
        $uppercase[random_int(0, strlen($uppercase) - 1)], // one uppercase
        $lowercase[random_int(0, strlen($lowercase) - 1)], // one lowercase
        $numbers[random_int(0, strlen($numbers) - 1)],     // one number
        $specialChars[random_int(0, strlen($specialChars) - 1)], // one special char
    ];

    // Calculate remaining length
    $remainingLength = $length - count($password);

    // All possible characters for the rest of the password
    $allChars = $uppercase . $lowercase . $numbers . $specialChars;

    // Fill the remaining length with random characters
    for ($i = 0; $i < $remainingLength; $i++) {
        $password[] = $allChars[random_int(0, strlen($allChars) - 1)];
    }

    // Shuffle the password array to make it random
    shuffle($password);

    return implode('', $password);
}




?>
