<?php

namespace App\Utils;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as IlluminateResponse;

trait RestResponse
{
    /**
     * The default status code
     *
     * @var int
     */
    protected $statusCode = 200;
    /**
     * Getter for the status code
     *
     * @return integer The status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    /**
     * Setter for the status code
     *
     * @param integer $statusCode The given status code
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }
    /**
     * Will return a response
     *
     * @param array $data    The given data
     * @param array $headers The given headers
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response
     */
    public function respond($data, $headers = [ ])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Will result in an array with a paginator
     *
     * @param LengthAwarePaginator $items   The paginated items
     * @param array                $data    The data
     * @param array                $headers The headers that should be send with the JSON-response
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the paginated results
     */
    protected function respondWithPagination(LengthAwarePaginator $items, $data, $headers = [ ])
    {

        $data = array_merge($data, [
            'pagination' => [
                'total_count'  => $items->total(),
                'total_pages'  => $items->lastPage(),
                'current_page' => $items->currentPage(),
                'limit'        => $items->perPage()
            ]
        ]);
        return $this->respond($data, $headers);
    }

    /**
     * Will result in an success message
     *
     * @param string $message The given message
     * @param null $data
     * @param array $headers The headers that should be send with the JSON-response
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the message
     */
    public function respondWithSuccess($message, $data = null, $headers = [ ])
    {
        return $this->respond([
            'message'     => $message,
            'status'    => true,
            'status_code' => $this->getStatusCode(),
            'data' => $data
        ], $headers);
    }

    /**
     * Will result in an error
     *
     * @param string $message The given message
     * @param null $data
     * @param array $headers The headers that should be send with the JSON-response
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the error message
     */
    public function respondWithError($message, $data = null, $headers = [ ])
    {
        return $this->respond([
            'status' => false,
            'message'     => $message,
            'errors'  => $data,
            'status_code' => $this->getStatusCode()
        ], $headers);
    }
    public function respondWithFile($filePath, $fileName, $headers = [ ])
    {
        return Response::download($filePath, $fileName, $headers);
    }
    /**
     * Will result in a 201 code
     *
     * @param string $message The given message
     * @param array  $headers The headers that should be send with the JSON-response
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the message
     */
    protected function respondCreated($message = 'Item created', $headers = [ ])
    {
        $this->setStatusCode(IlluminateResponse::HTTP_CREATED);
        return $this->respondWithSuccess($message, $headers);
    }
    /**
     * Will result in a 400 error code
     *
     * @param string $message The given message
     * @param array  $headers The headers that should be send with the JSON-response
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the error code
     */
    protected function respondBadRequest($message = 'Bad request', $data = null,$headers = [ ])
    {
        $this->setStatusCode(IlluminateResponse::HTTP_BAD_REQUEST);
        return $this->respondWithError($message, $data, $headers);
    }

    /**
     * Will result in a 401 error code
     *
     * @param string $message The given message
     * @param array  $headers The headers that should be send with the JSON-response
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the error code
     */
    protected function respondUnauthorized($message = 'Unauthorized', $headers = [ ])
    {
        $this->setStatusCode(IlluminateResponse::HTTP_UNAUTHORIZED);
        return $this->respondWithError($message, $headers);
    }

    /**
     * Will result in a 403 error code
     *
     * @param string $message The given message
     * @param array  $headers The headers that should be send with the JSON-response
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the error message
     */
    protected function respondForbidden($message = 'Forbidden', $headers = [ ])
    {
        $this->setStatusCode(IlluminateResponse::HTTP_FORBIDDEN);
        return $this->respondWithError($message, $headers);
    }

    /**
     * Will result in a 404 error code
     *
     * @param string $message The given message
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the error message
     */
    protected function respondNotFound($message = 'Not found')
    {
        $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND);
        return $this->respondWithError($message);
    }

    /**
     * Will result in a 405 error code
     *
     * @param string $message The given message
     * @param array  $headers The headers that should be send with the JSON-response
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the error message
     */
    protected function respondNotAllowed($message = 'Method not allowed', $headers = [ ])
    {
        $this->setStatusCode(IlluminateResponse::HTTP_METHOD_NOT_ALLOWED);
        return $this->respondWithError($message, $headers);
    }
    /**
     * Will result in a 422 error code
     *
     * @param string $message The given message
     * @param array  $headers The headers that should be send with the JSON-response
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the error code
     */
    protected function respondUnprocessableEntity($message = 'Unprocessable', $headers = [ ])
    {
        $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
        return $this->respondWithError($message, $headers);
    }

    /**
     * Will result in a 429 error code
     *
     * @param string $message The given message
     * @param array  $headers The headers that should be send with the JSON-response
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the error message
     */
    protected function respondTooManyRequests($message = 'Too many requests', $headers = [ ])
    {
        $this->setStatusCode(IlluminateResponse::HTTP_TOO_MANY_REQUESTS);
        return $this->respondWithError($message, $headers);
    }
    /**
     * Will result in a 500 error code
     *
     * @param string $message The given message
     * @param array  $headers The headers that should be send with the JSON-response
     *
     * @return \Illuminate\Http\JsonResponse The JSON-response with the error message
     */
    protected function respondInternalError($message = 'Internal Error', $headers = [ ])
    {
        $this->setStatusCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR);
        return $this->respondWithError($message, $headers);
    }


}