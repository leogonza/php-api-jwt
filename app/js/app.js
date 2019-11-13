var app = new function () {
    this.userId = false;
    this.jwtToken = null;
    this.customerIdForAction = null;

    this.setCustomerIdForAction = function (id) {
        this.customerIdForAction = id;
    };

    this.init = function () {
        if (!localStorage.jwtToken || !localStorage.userId)
            showLoginModal();
        else {
            this.userId = localStorage.userId;
            this.jwtToken = localStorage.jwtToken;
            this.getAllCustomers();
        }

    };

    this.Login = function (email, pass) {
        data = {
            name: "generateToken",
            params: {
                "email": email,
                "pass": pass
            }
        };
        var self = this;
        ajaxCall(data, function (response) {
            if (response.status != 200)
                alert(response.result)
            self.jwtToken = response.result.token;
            self.userId = response.result.userId;
            localStorage.jwtToken = self.jwtToken;
            localStorage.userId = self.userId;
            closeModal('loginModal');
            self.getAllCustomers();
        });
    };

    this.getAllCustomers = function () {
        data = {
            name: "getAllCustomers",
            params: {
                "userId": this.userId
            }
        };
        ajaxCall(data, function (response) {
            addCustomersToTable(response.result);
        });

    };

    this.addCustomer = function (name, email, address, mobile) {
        data = {
            name: "addCustomer",
            params: {
                "userId": this.userId,
                "name": name,
                "email": email,
                "addr": address,
                "mobile": mobile
            }
        };
        var self = this;
        ajaxCall(data, function () {
            closeModal('addCustomerModal');
            clearAddCustomerModalFields();
            self.getAllCustomers();
        });
    }

    this.deleteCustomers = function (customers) {
        if (customers && customers.length > 0) {
            $.each(customers, function (i, customer) {
                deleteCustomer(customer);
            });
        } else if (this.customerIdForAction && !isNaN(this.customerIdForAction)) {
            deleteCustomer(this.customerIdForAction);
        }
        closeModal('deleteCustomerModal');
        this.getAllCustomers();
    };

    var deleteCustomer = function (customerId, onSuccess) {
        if (!customerId || isNaN(customerId)) customerId = this.customerIdForAction;
        data = {
            name: "deleteCustomer",
            params: {
                "userId": this.userId,
                "customerId": customerId
            }
        };
        ajaxCall(data, onSuccess);
        this.customerIdForAction = null;
    }.bind(this);

    var ajaxCall = function (postData, onSuccess, onError) {
        postData = JSON.stringify(data);
        var token = this.jwtToken;
        $.ajax({
            type: 'POST',
            url: '/api/',
            dataType: "json",
            contentType: "application/json",
            data: postData,
            beforeSend: function (xhr) {
                if (token) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + token);
                }
            },
            success: function (response) {
                if (!response || !response.status || response.status != 200) {
                    showLoginModal();
                    if (onError) onError(response);
                    return false;
                }
                if (onSuccess) onSuccess(response);
            },
            error: function (e) {
                if (onError) onError(e);
                showLoginModal();
            }
        });
    }.bind(this);

};