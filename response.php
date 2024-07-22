<?php
    class Response {
        function ok($message, $data = null) {
			http_response_code(200);
			if($data == null){
				return [
					'code'		  => 200,
					'success'     => TRUE,
					'message'     => $message,
					'data'	      => array()
				];
			}
            else{
				return [
					'code'		  => 200,
					'success'     => TRUE,
					'message'     => $message,
					'data'	      => $data
				];
			}
        }
		
		function error($error) {
			http_response_code(400);
            return [
                'code'		  => 400,
                'success'     => FALSE,
                'message'     => $error,
				'data'		  => array()
            ];
        }
		
		function internalServerError($error) {
			http_response_code(500);
            return [
                'code'		  => 500,
                'success'     => FALSE,
                'message'     => "Connection failed",
				'note'		  => $error
            ];
        }
    }
?>