function validateRegistrationForm() {
    var firstName = $.trim($('#firstName').val());
    var lastName = $.trim($('#lastName').val());
    var userName = $.trim($('#userName').val());
    var mobile = $.trim($('#mobile').val());
    // console.log("mobile=>"+mobile);
    var email = $.trim($('#email').val());
    var role = $.trim($('#role').val());
    var password = $.trim($('#password').val());
    var confirmPassword = $.trim($('#password_confirmation').val());
    var isValid = true;
    if(userName == '') {
        $('#userNameError').html('User name should not be empty');
        isValid = false;
    }
    if(email != '') {
        if(!isEmail) {
            $('#emailError').html('Please provide valid email');
            isValid = false;
        }
    } else {
        $('#emailError').html('Email should not be empty'); isValid = false;
    }
    if(mobile != '') {
        if(mobile.length == 10){
            var filter = /^\d*(?:\.\d{1,2})?$/;
            if (!filter.test(mobile)) {
                $('#mobileError').html('Mobile number invalid'); isValid = false;
            }
        } else {
            $('#mobileError').html('Mobile number should be 10 digits'); isValid = false;
        }
    } else {
        $('#mobileError').html('Mobile number should be empty'); isValid = false;
    }
    if(role == '') {
        $('#roleError').html('User role should not be empty');
        isValid = false;
    }
    if (password != confirmPassword) {
        $("#confirmPasswordError").html("Password does not match !"); isValid = false;
    }
    
    return isValid;
    
}

function validateOrgRegistrationForm() {
    var fullName = $.trim($('#fullName').val());
    console.log("fullName=>"+fullName);
    var businessEmail = $.trim($('#email').val());
    var nameofOrg = $.trim($('#nameofOrg').val());
    var sizeofOrg = $.trim($('#sizeofOrg').val());
    var industry = $.trim($('#industry').val());
    var subIndustry = $.trim($('#subIndustry').val());
    var headQuarters = $.trim($('#headQuarters').val());
    var country = $.trim($('#country').val());
    var organizationURL = $.trim($('#organizationURL').val());
    var role = $.trim($('#role').val());
    var dept = $("#dept").val().trim();
    var password = $.trim($('#password').val());
    var confirmPassword = $.trim($('#password_confirmation').val());
    var isValid = true;
    if(fullName == '') {
        $('#fullNameError').html('Full name should not be empty');
        isValid = false;
    }

    if(nameofOrg == '') {
        $('#nameofOrgError').html('Name of organisation should not be empty');
    }

    if(sizeofOrg == '') {
        $('#sizeofOrgError').html('Size of organisation should not be empty');
    }
    if(country == '') {
        $('#countryError').html('Country should not be empty');
    }

    if(businessEmail != '') {
        if(!isEmail(businessEmail)) {
            $('#emailError').html('Please provide valid business email');
            isValid = false;
        }
    } else {
        $('#emailError').html('Business email should not be empty'); isValid = false;
    }
    if(role == '') {
        $('#roleError').html('User role should not be empty');
        isValid = false;
    }
    if(dept == '' || typeof dept === 'undefined') {
        $('#deptError').html("Department Should Not Be Empty"); isValid = false;
    }
    if(password == ''){
        $("#passwordError").html("Password Should Not Be Empty"); isValid = false;
    }
    if(confirmPassword == '') {
        $("#confirmPasswordError").html("Confirm Password Should Not Be Empty"); isValid = false;
    }
    if (password != confirmPassword) {
        $("#confirmPasswordError").html("Password does not match !"); isValid = false;
    }
    return isValid;
    
}

function validateLoginForm() {
    var email = $.trim($('#email').val());
    var password = $.trim($('#password').val());
    var isValid = true;
    if(email != '') {
        if(!isEmail) {
            $('#emailError').html('Please provide valid email');
            isValid = false;
        }
    } else {
        $('#emailError').html('Email should not be empty'); isValid = false;
    }
    if(password != '') {
        
    } else {
        $('#passwordError').html('Password should not be empty'); isValid = false;
    }
    return isValid;
}

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}