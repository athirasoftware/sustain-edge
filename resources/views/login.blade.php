@include('includes.header') 
<div id="pageWrapper" class="loginPage">

    <section id="login">
        <div class="container">
            <h2 class="text-center sedge">Welcome to SustainEDGE Sustainability Tool</h2>
            <div class="loginBox">
                <div class="logoBx">
                    <img src="{{URL::To('assets/images/logo.png')}}" alt="">
                </div>
                <div class="title">Login</div>
                <form autocomplete="off" method="post" id="getUserLogin" name="getUserLogin">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="email">Business Email<span class="important">*</span></label>
                            <input type="text" name="email" id="email" value="" class="form-control" placeholder="Enter Business Email">
                            <span class="error" id="emailError"> </span>
                        </div>
                        <div class="col-lg-12">
                        <label for="password">Password<span class="important">*</span></label>
                            <input type="password" name="password" id="password" value="" class="form-control" placeholder="Enter Password">
                            <span class="error" id="passwordError"> </span>
                            <div class="label">
                                <a href="javscript:void(0)" class="link">Forgot Password</a>
                            </div>
                        </div>
                        <div class="col-lg-12" id="msg" style="display:none;" > 
                            @if($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-12">
                            <div class="flx">
                                <div class="item">
                                    <a id="loginVerify" class="cmnbtn hoveranim">
                                        <span>Login</span>
                                    </a>
                                </div>
                                
                                <div class="item">
                                    <a href="{{ route('orgRegForm') }}" class="cmnbtn hoveranim">
                                        <span>Register</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>       
        
        </div>
    </section>

</div>

<?php //include './includes/footer.php'?>
@include('includes.footer')



<script type="text/javascript">
    $(document).ready(function () {
        
        $('#loginVerify').click(function(event){
            event.preventDefault();
            $('.error').html('');

            // var formData = $('#saveUserRegister').serialize();
            // var formData = $('#getUserLogin').serialize();
            
            var form = $('form')[0]; // You need to use standard javascript object here
            var formData = new FormData(form);

            // var formData = new FormData(this);
            if(validateLoginForm()) {
                // console.log("VAlid");
                $.ajax({
                    url:"{{route('authenticate')}}",
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
                            /* 
                            $("#msg").show();
                            $("#msg").addClass("successDiv");
                            $("#msg").html(data.messages);
                            setTimeout(() => {
                                $("#msg").html('');
                                $("#msg").hide();
                                $('#getUserLogin')[0].reset();
                                window.location = "{{URL::To('/admin')}}";
                            }, 2000);
                             */
                            window.location.replace( '{{route("checkLogin")}}');
                            // console.log("checkRole");
                        }  else if(data.status == "error") {
                            console.log(data.messages);
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
