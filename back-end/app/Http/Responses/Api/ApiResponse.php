<?php

namespace App\Http\Responses\Api;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The API Response object to be used as the API controller request responses.
 */
class ApiResponse implements Responsable
{
    /**
     * @param  mixed  $data  The data object or list being returned
     * @param  int  $statusCode  The HTTP status code. Defaults to 200 OK.
     * @param  array  $headers  Additional optional response headers;
     */
    private function __construct(
        public mixed $data,
        public ?string $message = null,
        public int $statusCode = Response::HTTP_OK,
        private bool $successful = true,
        private readonly array $headers = [],
    ) {}

    /**
     * Creates a new instance of API Response for successfull cases.
     *
     * @param  mixed|null  $data  The data to display. Otherwise, null. Defaults to null.
     * @param  int  $statusCode  The 2xx HTTP success response status code. Defaults to 200 OK.
     * @param  array  $headers  Optional list of headers to apply to the response. Defaults to none.
     * @param static The new instance of ApiResponse.
     */
    public static function success(
        mixed $data = null,
        int $statusCode = Response::HTTP_OK,
        array $headers = [],
    ): static {
        return new static($data, null, $statusCode, true, $headers);
    }

    /**
     * Creates a new instance of API Response for error cases.
     *
     * @param  int  $statusCode  The 4xx client error or 5xx server error response status code.
     * @param  string|null  $message  An optional message containing a brief reason. Defaults to null.
     * @param  mixed|null  $data  Supporting error data if any. Defaults to null.
     * @return static The new instance of an error ApiResponse.
     */
    public static function error(
        int $statusCode,
        ?string $message = null,
        mixed $data = null,
        array $headers = [],
    ): static {
        return new static($data, $message, $statusCode, false, $headers);
    }

    /**
     * @param  Request  $request  request
     */
    public function toResponse($request): JsonResponse
    {
        $response = [
            'success' => $this->successful,
            'data' => $this->data,
        ];

        // Include the optional message field on error responses.
        if (! $this->successful) {
            $response['message'] = $this->message;
        }

        return response()->json(
            $response,
            $this->statusCode,
            $this->headers
        );
    }
}
