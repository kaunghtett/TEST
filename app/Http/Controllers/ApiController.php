<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

abstract class ApiController extends BaseController
{
    protected $statusCode = 200;

    public function getStatusCode()
    {
    	return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
    	$this->statusCode = $statusCode;
    	return $this;
    }
    public function respondNotFound($message = 'Not Found!')
    {
    	return $this->setStatusCode(404)->respondWithError($message);
    }

    public function respondForbidden($message = 'Forbidden!')
    {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    public function respondUnauthorized($message = 'Unauthorized!')
    {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    public function respondInternalError($message = 'Internal Error!')
    {
    	return $this->setStatusCode(500)->respondWithError($message);
    }

    public function respond($data, $headers = [])
    {
    	return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithError($message)
    {
    	return $this->respond([
    		'error' => [
    			'message' => $message,
    			'status_code' => $this->getStatusCode()
    		]
    	]);
    }
    public function error($message,$code) {
        return [
            'message' => $message,
            'code' => $code
        ];
    }


    public function putData($key,$val) {
        return [
            $key => $val
        ];
    }

    public function respondSuccess($message)
    {
        return $this->respond([
                'success' => [
                    'message' => $message,
                    'status_code' => $this->getStatusCode()
                ]
            ]);
    }

    /**
     * New repond for api v7*
     */
    public function respondValidationError($errors) {
        return $this->respond([
            'status_code' => $this->getStatusCode(),
            'message' => 'The given data was invalid.',
            'success' => false,
            'errors' => $errors
        ]);
    }

    //4** errors
    public function respondMessage($message, $success = true) {
        return $this->respond([
            'status_code' => $this->getStatusCode(),
            'message' => $message,
            'success' => (boolean) $success
    	]);
    }

    public function respondListCollection(int $message, $data, $error, $duration,  $limit,  $offset)
    {
       
        return response()->json([
            'success' => $message,
            'code' => Response::HTTP_OK,
            'meta' => [
                'method' => request()->method(),
                'endpoint' => \Request::getRequestUri(),
                'limit' => $limit,
                'offset' => $offset,
                'total' => $data->count(),
            ],
            'data' => $data,
            'errors' => $error,
            'duration' => $duration
        ], Response::HTTP_OK);
    }

    public function respondDetail(int $message, $data, $error, $duration)
    {
       
        return response()->json([
            'success' => $message,
            'code' => Response::HTTP_OK,
            'meta' => [
                'method' => request()->method(),
                'endpoint' => \Request::getRequestUri(),
                'total' => 1,
            ],
            'data' => $data,
            'errors' => $error,
            'duration' => $duration
        ], Response::HTTP_OK);
    }

    public function respondErrorListCollection(int $message, $data, $error, $duration)
    {
        return response()->json([
            'success' => $message,
            'code' => Response::HTTP_NOT_FOUND,
            'meta' => [
                'method' => request()->method(),
                'endpoint' => \Request::getRequestUri(),
              
            ],
            'data' => $data,
            'errors' => $error,
            'duration' => $duration
        ], Response::HTTP_NOT_FOUND);
    }

    public function respondErrorValidation(int $message, $data, $error, $duration) {
        return response()->json([
            'success' => $message,
            'code' => 400,
            'meta' => [
                'method' => request()->method(),
                'endpoint' => \Request::getRequestUri(),
              
            ],
            'data' => $data,
            'errors' => $error,
            'duration' => $duration
        ], Response::HTTP_BAD_REQUEST);
    }

    public function respondSuccessCollection(int $message,$reponse,$data,$error,$duration) {
        return response()->json([
            'success' => $message,
            'code' => $reponse,
            'meta' => [
                'method' => request()->method(),
                'endpoint' => \Request::getRequestUri(),
              
            ],
            'data' => $data,
            'errors' => $error,
            'duration' => $duration
        ], Response::HTTP_OK);
        
    }
    
    public function respondExistedCollection(int $message,$reponse,$data,$error,$duration) {
        return response()->json([
            'success' => $message,
            'code' => $reponse,
            'meta' => [
                'method' => request()->method(),
                'endpoint' => \Request::getRequestUri(),
              
            ],
            'data' => $data,
            'errors' => $error,
            'duration' => $duration
        ], Response::HTTP_CONFLICT);
        
    }
  
}
