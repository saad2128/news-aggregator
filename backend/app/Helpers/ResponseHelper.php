<?php

namespace App\Helpers;

class ResponseHelper
{
	// predefine error codes => messages
	private static $errorMessages = [
		200		=>	'success',
        204     =>  'No content',
		401		=>	'Unauthenticated!',
		403		=>	'Not allowed!',
		404		=>	'Resource Not Found!',
        422     =>  'Validation Errors',
		500		=>	'Internal Server Error!',
	];

	/**
	 * Global function for api response formatting.
	 *
	 * @return json response
	*/

	public static function sendResponse($data, $code, $message = null, $error = false , $validation_errors = []) {
		// use predefined error message if no message exists.
		$message = $message ?? self::$errorMessages[$code];
	    return response()->json([
	    	'status'=>[
	    		'code'				=> $code,
	    		'message'			=> $message,
	    		'error'				=> $error,
	    		'validation_errors'	=> $validation_errors,
	    	],
	    	'data'	=> $data
    	]
    	, $code, [], JSON_UNESCAPED_UNICODE);
	}

}