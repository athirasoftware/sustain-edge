<style>
    #addUserBtn {
        position: absolute;
        left: 45%;
    }
</style>
<?php

use Illuminate\Support\Facades\Crypt;
?>
<div class="cmnBx mt-4 newUser">
    <div class="mainTitle">Add a new user to your organisation</div>
    {{ Form::open(array('id' =>'editNewUserForm', 'name' => 'editNewUserForm')) }}
    {{ Form::hidden('userId', (isset($userDetails->id) && $userDetails->id != '')?Crypt::encrypt($userDetails->id):'', array('id' => 'userId')) }}
    <div class="row  ">
        <div class="col-lg-6">
            <label for="">Name</label>
            <input type="text" id="name" name="name" value="{{ $userDetails->full_name }}" class="form-control" placeholder="Enter Name">
            <span class="error" id="nameError"> </span>
        </div>
        <div class="col-lg-6">
            <label for="">Role</label>
            {{Form::select('role', $roles, $userDetails->role, array('id'=>'role', "class" => 'form-control'))}}
            <span class="error" id="selectedRoleError"> </span>
        </div>
        <div class="col-lg-6">
            <label for="">Email</label>
            <input type="text" name="email" id="email" value="{{ $userDetails->email }}" class="form-control" placeholder="Email">
            <span class="error" id="emailError"> </span>
        </div>
        <div class="col-lg-6">
            <label for="">Department</label>
            <input type="text" id="dept" name="dept" value="{{ $userDetails->department }}" class="form-control" placeholder="Enter Department">
            <span class="error" id="deptError"> </span>
        </div>
        <div class="col-lg-6">
            <label for="">Password</label>
            <input type="password" name="password" id="password" value="" class="form-control" placeholder="Enter Password">
            <span class="error" id="passwordError"> </span>
        </div>
        <div class="col-lg-6">
            <label for="">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" value="" class="form-control" placeholder="Re Enter Password">
            <span class="error" id="confirmPasswordError"> </span>
        </div>
        <div id="displayMsg" style="display:none"><span></span></div>
        <div class="col-lg-12">
            <button type="submit" class="hoveranim cmnbtn" id="addUserBtn">
                <span>Update User</span>
            </button>
            <br>
        </div>
        {{ Form::close() }}
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        console.log("Page loaded");
        var message = $('.success__msg');
        $('#editNewUserForm').submit(function(event) {
            event.preventDefault();
            $('.error').html('');
            //function addNewUser(formId) {
            var userId = $.trim($("#userId").val());
            var name = $.trim($("#name").val());
            var role = $.trim($('#role').val());
            var email = $.trim($('#email').val());
            var dept = $.trim($("#dept").val());
            var password = $.trim($('#password').val());
            var confirmPassword = $.trim($('#password_confirmation').val());
            var isValid = true;
            if (userId == '' || typeof userId === 'undefined') {
                bootbox.alert('Invalid User Selection');
            }
            if (name == '' || typeof name === 'undefined') {
                $('#nameError').html('Name Should Not Be Empty');
                isValid = false;
            }
            if (role == '' || typeof role === 'undefined') {
                $('#roleError').html('User Role Should Not Be Empty');
                isValid = false;
            }
            if (email != '') {
                if (!isEmail(email)) {
                    $('#emailError').html('Please Provide Valid Email');
                    isValid = false;
                }
            } else {
                $('#emailError').html('Email should Not Be Empty');
                isValid = false;
            }
            if (password != confirmPassword) {
                $("#confirmPasswordError").html("Password Does Not Match!");
                isValid = false;
            }
            if (dept == '' || typeof dept === 'undefined') {
                $('#deptError').html("Department Should Not Be Empty");
                isValid = false;
            }
            // console.log(" dept Error isValid => "+isValid);
            if (isValid) {
                // console.log("trying to hit ajax call");
                $.ajax({
                    url: "{{ url('/saveNewUser') }}",
                    method: 'post',
                    dataType: "JSON",
                    data: {
                        "id": userId,
                        "name": name,
                        "role": role,
                        "email": email,
                        "dept": dept,
                        "password": password,
                        "password_confirmation": confirmPassword,
                        "_token": "{{ csrf_token() }}",

                    },
                    success: function(data) {
                        // console.log(data);
                        if (data.status == 'success') {
                            // bootbox.alert(data.message);
                            $('#displayMsg').html('');
                            $('#displayMsg').show();
                            $('#displayMsg').html('<span class="success">' + data.message + '</span>');
                            setTimeout(() => {
                                $('#displayMsg').html('');
                                $('#displayMsg').hide();
                                if (data.isOwner == 'yes') {
                                    location.reload();
                                } else {
                                    var url = "{{URL::TO('ghgUserList')}}";
                                    $("#ghgDiv").html('');
                                    $("#ghgDiv").load(url);
                                }
                            }, 2000);
                        } else if (data.status == 'error') {
                            bootbox.alert(data.message);
                        } else if (data.status == 'validation') {
                            // console.log("Todo");
                            $.each(data.messages, function(i, v) {
                                // console.log(i);
                                $("#" + i + "Error").html(v);
                            });
                        }
                    }
                });
            } else {
                console.log("something went wrong with ajax");
            }
        });
    });
</script>