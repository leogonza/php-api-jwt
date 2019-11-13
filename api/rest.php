<?php
    require_once("../common/constants.php");
    class Rest {

        protected $request;
        protected $serviceName;
        protected $params;
        protected $userId;

        public function __construct(){
            if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
                $this->throwError(REQUEST_METHOD_NOT_VALID, "Invalid Request method");
                return;
            }
            $handler = fopen('php://input', 'r');
            $this->request = stream_get_contents($handler);
            $this->validateRequest(); 

           if( 'generatetoken' != strtolower( $this->serviceName) ) {
				$this->validateToken();
			}

        }

        public function validateRequest(){
            if ($_SERVER['CONTENT_TYPE'] !== 'application/json'){
                $this->throwError(REQUEST_CONTENTTYPE_NOT_VALID, "Invalid Content-type");
            }
            $data = json_decode($this->request, true);
            if (!isset($data['name']) || $data['name'] == ''){
                $this->throwError(API_NAME_REQUIRED, "Api Name is required.");
            }
            $this->serviceName = $data['name'];

            if (!is_array($data['params'])){
                $this->throwError(API_NAME_REQUIRED, "Api Params are required.");
            }
            $this->params = $data['params'];
        }

		public function validateToken() {
			try {
                $token = $this->getBearerToken();
                $user = new User;
                $user->setId($this->params['userId']);

				if(!$user->get()) {
					$this->returnResponse(INVALID_USER_PASS, "This user is not found in our database.");
				}
				if(!$user->isActive()) {
					$this->returnResponse(USER_NOT_ACTIVE, "This user may be decactived. Please contact to admin.");
                }
                $this->userId=$user->getId();
                $payload = JWT::decode($token, $user->getTokenSecret(), ['HS256']);
			} catch (Exception $e) {
				$this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
			}
		}

        public function processApi(){
            $api = new API;
            $rMethod = new reflectionMethod($api, $this->serviceName);
            if (!method_exists($api, $this->serviceName)){
                $this->throwError(API_METHOD_NOT_VALID, "Api method is invalid");
            }
            $rMethod->invoke($api);
        }

        public function validateParameter($fieldName, $value, $dataType, $required = true){
            if ($required && empty($value)){
                $this->throwError(PARAMETER_REQUIRED, "Missing required param.");
            }
            switch($dataType){
                case BOOLEAN: 
                    if (!is_bool($value)){
                        $this->throwError(PARAMETER_DATATYPE, "Incorrect data type for " . $fieldName);
                    }
                    break;
                case INTEGER:
                    if (!is_numeric($value)){
                        $this->throwError(PARAMETER_DATATYPE, "Incorrect data type for " . $fieldName);
                    }
                    break;
                case STRING: 
                    if (!is_string($value)){
                        $this->throwError(PARAMETER_DATATYPE, "Incorrect data type for " . $fieldName);
                    }
                    break;
            }
            return $value;
        }

        public function throwError ($code, $message){
            header("conten-type: application/json");
            $errorMsg = json_encode(["error" => ['status'=>$code, 'message'=>$message]]);
            echo $errorMsg;
            exit;
        }

        public function returnResponse ($code, $responseData) {
            header("conten-type: application/json");
            $response = json_encode(['status'=>$code, 'result'=>$responseData]);
            echo $response;
            exit;
        }

        /**
	    * Get hearder Authorization
	    * */
	    public function getAuthorizationHeader(){
	        $headers = null;
	        if (isset($_SERVER['Authorization'])) {
	            $headers = trim($_SERVER["Authorization"]);
	        }
	        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
	            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	        } elseif (function_exists('apache_request_headers')) {
	            $requestHeaders = apache_request_headers();
	            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
	            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
	            if (isset($requestHeaders['Authorization'])) {
	                $headers = trim($requestHeaders['Authorization']);
	            }
	        }
	        return $headers;
	    }
	    /**
	     * get access token from header
	     * */
	    public function getBearerToken() {
	        $headers = $this->getAuthorizationHeader();
	        // HEADER: Get the access token from the header
	        if (!empty($headers)) {
	            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
	                return $matches[1];
	            }
	        }
	        $this->throwError( ATHORIZATION_HEADER_NOT_FOUND, 'Access Token Not found');
	    }


    }

?>