# JSON Packages:

### generateToken
> Use to validate user credentials and returns the JWT Token required to be used in future API calls; it stores the a unique private key for the user in the "Users" table that is required for signing the JWT token
```
{
	"name":"generateToken",
	"params": {
		"email": "test@gmail.com",
		"pass": "test123"
	}
}
```

### addCustomer
> Adds a customer to the Customer Table; JWT Bearer token is required
```
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
```

### getCustomerDetails
> Returns a customer detailed information; JWT Bearer token is required
```
{
	"name":"getCustomerDetails",
	"params": {
		"userId": 3,
		"customerId": 5
	}
}
```

### updateCustomer
> Updates customer information; JWT Bearer token is required
```
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
```
### deleteCustomers
> Deletes a customer; JWT Bearer token is required
```
{
	"name":"deleteCustomer",
	"params": {
		"userId": 3,
		"customerId": 5
	}
}
```

## Libraries used:
•	PHP-JWT: https://github.com/firebase/php-jwt

## Source Code:
### Api
* \api\index.php -> Entry point. Creates an instance of the Api class to handle all the calls
* \api\api.php -> Contains the public methods use in the API: generateToken, addCustomer, getCustomerDetails, updateCustomer, deleteCustomer
* \api\rest.php -> Parent Class for the Api; validates the token on every request and invokes the corresponding Api method using reflection.

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
### API:
* http://leogonza.asuscomm.com:81/api/

### Db:
* http://leogonza.asuscomm.com/phpmyadmin/db_structure.php?server=1&db=php-jwt

