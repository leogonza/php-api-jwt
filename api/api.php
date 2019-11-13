<?php    
    class Api extends Rest {

        public function __construct(){
            parent::__construct();
        }

        public function generateToken(){
            $email = $this->validateParameter('email', $this->params['email'], STRING);
            $pass = $this->validateParameter('pass', $this->params['pass'], STRING);
            try {

				$user = new User;
				$user->setEmail($email);
				$user->setPass($pass);

				if (!$user->isValid()){
                    $this->returnResponse(INVALID_USER_PASS, "Email or Password is incorrect");
                }
                if (!$user->isActive()){
                    $this->returnResponse(USER_NOT_ACTIVE, "User is not active");
                }

                $payload = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() + TOKEN_MINUTES_TO_EXPIRE * 60,
                    'userId' => $user->getId()
				];
				
				$tokenSecret = guidv4();
				$user->updateUserTokenSecret($tokenSecret);

                $token = JWT::encode($payload, $tokenSecret);
                $data = ['token' => $token, 'userId' => $user->getId()];
                $this->returnResponse(SUCCESS_RESPONSE, $data);
            } catch(Exception $e){
                $this->returnResponse(JWT_PROCESSING_ERROR, $e->getMessage());
            }

        }

        public function addCustomer() {
			$name = $this->validateParameter('name', $this->params['name'], STRING, false);
			$email = $this->validateParameter('email', $this->params['email'], STRING, false);
			$addr = $this->validateParameter('addr', $this->params['addr'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->params['mobile'], INTEGER, false);
			$cust = new Customer;
			$cust->setName($name);
			$cust->setEmail($email);
			$cust->setAddress($addr);
			$cust->setMobile($mobile);
			$cust->setCreatedBy($this->userId);
			$cust->setCreatedOn(date('Y-m-d'));
			if(!$cust->insert()) {
				$message = 'Failed to insert.';
			} else {
				$message = "Inserted successfully.";
			}
			$this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
		public function getCustomerDetails() {
			$customerId = $this->validateParameter('customerId', $this->params['customerId'], INTEGER);
			$cust = new Customer;
			$cust->setId($customerId);
			$customer = $cust->getCustomerDetailsById();
			if(!is_array($customer)) {
				$this->returnResponse(SUCCESS_RESPONSE, ['message' => 'Customer details not found.']);
			}
			$response['customerId'] 	= $customer['id'];
			$response['cutomerName'] 	= $customer['name'];
			$response['email'] 			= $customer['email'];
			$response['mobile'] 		= $customer['mobile'];
			$response['address'] 		= $customer['address'];
			$response['createdBy'] 		= $customer['created_user'];
			$response['lastUpdatedBy'] 	= $customer['updated_user'];
			$this->returnResponse(SUCCESS_RESPONSE, $response);
		}
		
		public function getAllCustomers() {
			$cust = new Customer;
			$customers = $cust->getAllCustomers();
			if(!is_array($customers)) {
				$this->returnResponse(SUCCESS_RESPONSE, ['message' => 'Customer details not found.']);
			}
			$this->returnResponse(SUCCESS_RESPONSE, $customers);
        }
        
		public function updateCustomer() {
			$customerId = $this->validateParameter('customerId', $this->params['customerId'], INTEGER);
			$name = $this->validateParameter('name', $this->params['name'], STRING, false);
			$addr = $this->validateParameter('addr', $this->params['addr'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->params['mobile'], STRING, false);
			$cust = new Customer;
			$cust->setId($customerId);
			$cust->setName($name);
			$cust->setAddress($addr);
			$cust->setMobile($mobile);
			$cust->setUpdatedBy($this->userId);
			$cust->setUpdatedOn(date('Y-m-d'));
			if(!$cust->update()) {
				$message = 'Failed to update.';
			} else {
				$message = "Updated successfully.";
			}
			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}
		public function deleteCustomer() {
			$customerId = $this->validateParameter('customerId', $this->params['customerId'], INTEGER);
			$cust = new Customer;
			$cust->setId($customerId);
			if(!$cust->delete()) {
				$message = 'Failed to delete.';
			} else {
				$message = "deleted successfully.";
			}
			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

    }
