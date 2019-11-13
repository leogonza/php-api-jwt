# JSON Packages:

### generateToken
{
	"name":"generateToken",
	"params": {
		"email": "test@gmail.com",
		"pass": "test123"
	}
}

### addCustomer
{
	"name":"addCustomer",
	"params": {
		"userId": 3,
		"name": "test Customer",
		"email": "test@gmail.com",
		"addr": "TEST ADDR",
		"mobile": "77777"
	}
}

### getCustomerDetails
{
	"name":"getCustomerDetails",
	"params": {
		"userId": 3,
		"customerId": 5
	}
}	

### updateCustomer
{
	"name":"updateCustomer",
	"params": {
		"userId": 2,
		"customerId": 5,
		"name":"test",
		"addr":"test adr",
		"mobile": "8888"
	}
}	

## Libraries used:
•	PHP-JWT: https://github.com/firebase/php-jwt

## Source Code:
### Api
* \api\index.php 
	Entry point; creates an instance of the Api class to handle all the calls
* \api\api.php
	Public methods use in the API: generateToken, addCustomer, getCustomerDetails, updateCustomer, deleteCustomer
*\api\rest.php
	Parent Class for the Api; validates the token on every request and invokes the Api method using reflection.

### Common (Helper functions)
* \common\constants.php -> Some constants used.
	TOKEN_MINUTES_TO_EXPIRE -> Minutes to expire the token
* \common\dbConnect.php -> MySql connection class
* \common\functions.php -> Helper functions
* \common\jwt.php -> Library PHP-JWT included in the solution

### Dal (Data access layer)
* \dal\customer.php  -> Customers table CRUD
* \dal\user.php -> Users table CRUD

## Demo
  http://leogonza.asuscomm.com:81/api/

## Db
http://leogonza.asuscomm.com/phpmyadmin/db_structure.php?server=1&db=php-jwt

