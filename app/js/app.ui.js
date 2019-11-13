$(document).ready(function () {
    initCheckBoxes();
    bindEvents();
    app.init();
});


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

function closeModal(modalId) {
    $('#' + modalId).modal('hide');
}

function addCustomersToTable(customers) {
    $(function () {
        $('#customersTable').find('tbody').empty();
        $.each(customers, function (i, customer) {
            var markup = "<tr><td>" + getCheckboxMarkup(customer) +
                "</td><td>" + customer.name +
                "</td><td>" + customer.email +
                "</td><td>" + customer.address +
                "</td><td>" + customer.mobile +
                "</td><td>" + getActionsMarkup(customer) + "</td></tr>";

            $('#customersTable').find('tbody').append(markup);

        });
    });
}

function getCheckboxMarkup(customer) {
    return '<span class="custom-checkbox"><input type="checkbox"' +
        '" name="customerGroup[]" value="' + customer.id +
        '"><label for="checkbox' + customer.id + '"></label></span>';
}

function getActionsMarkup(customer) {
    var editMarkup = '<a href="#editCustomerModal" onclick="app.setCustomerIdForAction(' + customer.id + ')" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>';
    var deleteMarkup = '<a href="#deleteCustomerModal" onclick="app.setCustomerIdForAction(' + customer.id + ')" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>';

    return editMarkup + deleteMarkup;
}

function validateCheckboxes(e) {
    if ($("input[name='customerGroup[]']:checked").length == 0) {
        alert('Select at least one customer!');
        if (e) e.stopPropagation();
        return false;
    }
    return true;
}

function deleteCustomers(e) {
    var customers = [];
    $.each($("input[name='customerGroup[]']:checked"), function () {
        customers.push($(this).val());
    });
    app.deleteCustomers(customers);
}

function setNameFieldFocus() {
    setTimeout(function () {
        $("#txtName").focus();
    }, 300);
}

function addCustomer() {
    var name = $("#txtName").val();
    var email = $("#txtEmail").val();
    var address = $("#txtAddress").val();
    var mobile = $("#txtMobile").val();
    app.addCustomer(name, email, address, mobile);
}

function clearAddCustomerModalFields() {
    $("#txtName").val('');
    $("#txtEmail").val('');
    $("#txtAddress").val('');
    $("#txtMobile").val('');
}