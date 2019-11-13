$(document).ready(function () {
    initCheckBoxes();
    bindEvents();
    app.init();
});

var app = new function () {
    this.userId = false;
    this.jwtToken = null;

    this.init = function () {
        if (!localStorage.jwtToken || !localStorage.userId)
            showLoginModal();
        else {
            this.userId = localStorage.userId;
            this.jwtToken = localStorage.jwtToken;
            this.getAllCustomers();
        }

    }

    this.Login = function (email, pass) {
        data = {
            name: "generateToken",
            params: {
                "email": email,
                "pass": pass
            }
        };
        ajaxCall(data, function (response) {
            if (response.status != 200)
                alert(response.result)
            this.jwtToken = response.result.token;
            this.userId = response.result.userId;
            localStorage.jwtToken = this.jwtToken;
            localStorage.userId = this.userId;
            closeLoginModal();
            this.getAllCustomers();
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

    }

    function ajaxCall(postData, onSuccess, onError) {
        postData = JSON.stringify(data);

        $.ajax({
            type: 'POST',
            url: '/api/',
            dataType: "json",
            contentType: "application/json",
            data: postData,
            beforeSend: function (xhr) {
                if (localStorage.jwtToken) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.jwtToken);
                }
            },
            success: function (data) {
                if (onSuccess) onSuccess(data);
            },
            error: function (e) {
                //console.log(e);
                //if (onError) onError();
                alert("Sorry, you are not logged in.");
            }
        });
    }

};

function initCheckBoxes() {
    // Activate tooltip
    $('[data-toggle="tooltip"]').tooltip();

    // Select/Deselect checkboxes
    var checkbox = $('table tbody input[type="checkbox"]');
    $("#selectAll").click(function () {
        if (this.checked) {
            checkbox.each(function () {
                this.checked = true;
            });
        } else {
            checkbox.each(function () {
                this.checked = false;
            });
        }
    });
    checkbox.click(function () {
        if (!this.checked) {
            $("#selectAll").prop("checked", false);
        }
    });
}

function bindEvents() {
    $("#btnLogin").click(function () {
        var email = $('#txtLoginEmail').val();
        var pass = $('#txtLoginPass').val();
        console.log(email, pass);
        app.Login(email, pass);
    });
}

function showLoginModal() {
    $('#loginModal').modal({
        show: 'true',
        backdrop: 'static',
        keyboard: false
    });
    setTimeout(function () {
        $('#txtLoginEmail').focus();
    }, 500);
}

function closeLoginModal() {
    $('#loginModal').modal('hide');
}

function addCustomersToTable(customers) {
    console.log(customers);
    $(function () {
        $.each(customers, function (i, customer) {
            var tds = [
                $('td').html(createCheckboxCel(customer)),
                $('td').html(customer.name),
                $('td').html(customer.email),
                $('td').html(customer.address),
                $('td').html(customer.mobile),
                $('td').html(customer.id)
            ];

            $('<tr>')
                .html(tds.join(""))
                .appendTo('#customersTable');

        });
    });
}

function createCheckboxCel(customer) {
    var ret = '<span class="custom-checkbox">< input type="checkbox" id="checkbox' + customer.id +
        '" name="options[]" value="' + customer.id +
        '" ><label for="checkbox' + customer.id + '"></label></span >';
    console.log(ret);
    return ret;
}