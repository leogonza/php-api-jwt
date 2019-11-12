##DEMO:
###Api:
http://leogonza.asuscomm.com:81/api/
###DB: 
http://leogonza.asuscomm.com/phpmyadmin/db_structure.php?server=1&db=php-jwt

## Json Packages:
generateToken
{
	"name":"generateToken",
	"params": {
		"email": "buckeye@gmail.com",
		"pass": "by123"
	}
}
addCustomer
{
	"name":"addCustomer",
	"params": {
		"userId": 3,
		"name": "buckeye Customer",
		"email": "customer@gmail.com",
		"addr": "TEST ADDR",
		"mobile": "77777"
	}
}
getCustomerDetails
{
	"name":"getCustomerDetails",
	"params": {
		"userId": 3,
		"customerId": 5
	}
}	
updateCustomer
{
	"name":"updateCustomer",
	"params": {
		"userId": 2,
		"customerId": 5,
		"name":"lgr",
		"addr":"test adr",
		"mobile": "8888"
	}
}	
updateCustomer
{
	"name":"updateCustomer",
	"params": {
		"userId": 2,
		"customerId": 5,
		"name":"lgr",
		"addr":"test adr",
		"mobile": "8888"
	}
}
