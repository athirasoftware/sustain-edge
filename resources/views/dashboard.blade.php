@include('includes.header') 
<div id="pageWrapper" class="loginPage">

    <section id="login">
        <div class="container">
            <div class="text-center "> <h2 class="sedge"> Welcome to SustainEDGE Sustainability Tool </h2> </div>
            <div class="loginBox">
                <div class="logoBx">
                    <img src="{{URL::To('assets/images/logo.png')}}" alt="">
                </div>
                <div class="title">Login</div>
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                            <span class="error" id="emailError"> </span>
                        </div>
                    </div>
                </div>
            </div>       
        
        </div>
    </section>

</div>

@include('includes.footer')



<script type="text/javascript">
    $(document).ready(function () {
        
        $('#loginVerify').click(function(event){
            event.preventDefault();
            $('.error').html('');
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
                return false;
            }
        });
    });
</script>
