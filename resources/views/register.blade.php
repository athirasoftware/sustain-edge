@include('includes.header')
<div id="pageWrapper" class="loginPage">

    <section id="register">
        <div class="container">
            <div class="titleBx">
                <div class="icon">
                    <img src="assets/images/logo.png" alt="">
                </div>
                <h2>Register Your Organisation</h2>
            </div>
            <div class="registerBx">
                <div class="title">Help us know more about you and <span>your</span> company</div>
                <div class="row">
                    <div class="col-lg-4">
                        <input type="text" class="form-control" placeholder="Full Name">
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" placeholder="Business Email">
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" placeholder="Name of your Organization">
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" placeholder="Size of the Organization">
                    </div>
                    <div class="col-lg-4">
                        <select class="form-control">
                            <option value="industry" hidden>industry</option>
                            <option value="industry">industry</option>
                            <option value="industry">industry</option>
                            <option value="industry">industry</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <select class="form-control">
                            <option value="industry" hidden>Sub-industry</option>
                            <option value="industry">Sub-industry</option>
                            <option value="industry">Sub-industry</option>
                            <option value="industry">Sub-industry</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" placeholder="Headquarters location">
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" placeholder="Country/Region">
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" placeholder="Organisation’s URL">
                    </div>
                    <div class="col-lg-12">
                        <input type="file" id="file">
                        <label for="file" class="file-upload">
                            <div class="upload">
                                <div class="icon">
                                    <img src="assets/images/upload.png" alt="">
                                </div>
                                <div class="label">Upload Logo</div>
                            </div>
                        </label>
                    </div>


                    <div class="col-lg-12">
                        <button type="submit" class="hoveranim cmnbtn">
                            <span>Register</span>
                        </button>
                    </div>
                </div>

            </div>

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