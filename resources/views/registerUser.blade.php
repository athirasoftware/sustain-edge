@include('includes.header')
<div id="pageWrapper" class="loginPage">

    <section id="register">
        <div class="container">
            <div class="titleBx">
                <div class="icon">
                    <img src="assets/images/logo.png" alt="">
                </div>
                <h2>Register User</h2>
            </div>
            <form autocomplete="off" method="post" id="saveUserRegister" name="saveUserRegister">
            {{ csrf_field() }}
            <div class="registerBx">
                <div class="title">Fill the <span>user</span> registration form </div>
                <div class="row">
                    <div class="col-lg-4">
                        <input type="text" name="firstName" id="firstName" class="form-control"  placeholder="First Name">
                        <span class="error" id="firstNameError"> </span>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" name="lastName" id="lastName" class="form-control"  placeholder="Last Name">
                        <span class="error" id="lasttNameError"> </span>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" name="userName" id="userName" class="form-control" placeholder="User Name">
                        <span class="error" id="userNameError"> </span>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile">
                        <span class="error" id="mobileError"> </span>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                        <span class="error" id="emailError"> </span>
                    </div>
                    <div class="col-lg-4">
                        {{ Form::select('role', (isset($roles) && count($roles) > 0)?$roles:[], '', ['class' => 'form-control', 'id' => 'role', 'name' => 'role']) }}
                        <span class="error" id="roleError"> </span>
                    </div>
                    <div class="col-lg-4">
                        <input type="password"  name="password" id="password"  class="form-control" placeholder="Enter Password">
                        <span class="error" id="passwordError"> </span>
                    </div> 
                    <div class="col-lg-4">
                        <input type="password"  name="password_confirmation" id="password_confirmation"  class="form-control" placeholder="Re Enter Password">
                        <span class="error" id="confirmPasswordError"> </span>
                    </div> 
                    <div class="col-lg-12" id="msg" style="display:none;" > </div>
                    <div class="col-lg-12">
                        <button type="submit" class="hoveranim cmnbtn" id="registerButton">
                            <span>Register</span>
                        </button>
                    </div>
                    
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </section>

</div>
@include('includes.footer')

<script type="text/javascript">
    $(document).ready(function () {
        
        $('#saveUserRegister').submit(function(event){
            event.preventDefault();
            $('.error').html('');

            // var formData = $('#saveUserRegister').serialize();
            // var formData = $('#saveUserRegister').serialize();
            var formData = new FormData(this);
            if(validateRegistrationForm()) {
                // console.log("VAlid");
                $.ajax({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{route('store')}}",
                    type:"POST",
                    data:formData,
                    dataType:"JSON",
                    cache:false,
                    contentType:false,
                    processData:false,
                    success:function(data) {
                        $('.error').html('');
                        // console.log(data);
                        // console.log(data.status);
                        if(data.status == 'success') {
                            // console.log(data.message);
                            $("#msg").show();
                            $("#msg").addClass("successDiv");
                            $("#msg").html(data.messages);
                            setTimeout(() => {
                                $("#msg").html('');
                                $("#msg").hide();
                                $('#saveUserRegister')[0].reset();
                                window.location = "{{URL::To('/')}}";
                            }, 2000);
                        }  else if(data.status == 'error') {
                            $("#msg").show();
                            $("#msg").addClass("errorDiv");
                            $("#msg").html(data.messages);
                            setTimeout(() => {
                                $("#msg").html('');
                                $("#msg").hide();
                            }, 3000);
                            // {"status":"validation","messages":{"password":["The password field must be at least 8 characters."]}}
                        } else if(data.status == 'validation') {
                            // console.log("Todo");
                            $.each(data.messages, function(i, v) {
                                // console.log(i);
                                $("#"+i+"Error").html(v);
                            });
                        }
                    }
                });
            } else {
                // console.log("Invalid");
                return false;
            }
        });
    });
</script>