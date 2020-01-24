<?php
namespace App\Exceptions;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;



class Handler extends ExceptionHandler{

	/**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
	{
	    // This will replace our 404 response with
	    // a JSON response.
	    if ($exception instanceof ModelNotFoundException) {
	        return response()->json([
	            'error' => 'Resource not found'
	        ], 404);
	    }

	    return parent::render($request, $exception);
	}
}
