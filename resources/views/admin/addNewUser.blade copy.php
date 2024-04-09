<style>
    #pageWrapper {
        overflow-x: visible !important;
        background: url();
    }
</style>
<div id="pageWrapper" class="loginPage">
    <section id="register">
        <div class="container loginPage">
            <div class="registerBx">
                <div class="title">Help us know more about you and <span>your</span> company</div>
                <form id="registerOrgForm" action="" method="POST" enctype="multipart/form-data">
                    {{ Form::hidden('addUser', 'yes') }}
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="text" name="fullName" id="fullName" class="form-control"  placeholder="Full Name">
                            <span class="error" id="fullNameError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <input type="text" name="email" id="email" class="form-control" placeholder="Business Email">
                            <span class="error" id="emailError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <input type="text" name="nameofOrg" id="nameofOrg" class="form-control" placeholder="Name of your Organization">
                            <span class="error" id="nameofOrgError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <input type="text" name="sizeofOrg" id="sizeofOrg" class="form-control" placeholder="Size of the Organization">
                            <span class="error" id="sizeofOrgError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <select class="form-control" name="industry" id="industry">
                                <option value="" >Please Select Industry</option>
                                <option value="Banking" >Banking</option>
                                <option value="Business Services" >Business Services</option>
                                <option value="Construction" >Construction</option>
                                <option value="Education" >Education</option>
                                <option value="Healthcare" >Healthcare</option>
                                <option value="Information Technology" >Information Technology</option>
                                <option value="Infrastructure" >Infrastructure</option>
                                <option value="Insurance" >Insurance</option>
                                <option value="Media" >Media</option>
                                <option value="Restaurants" >Restaurants</option>
                                <option value="Science & Technology" >Science & Technology</option>
                                <option value="Telecom" >Telecom</option>
                                <option value="Transportation" >Transportation</option>
                            </select>
                            <span class="error" id="industryError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <select class="form-control" name="subIndustry" id="subIndustry">
                            <option value="" >Please Select Industry</option>
                                <option value="Banking" >Banking</option>
                                <option value="Business Services" >Business Services</option>
                                <option value="Construction" >Construction</option>
                                <option value="Education" >Education</option>
                                <option value="Healthcare" >Healthcare</option>
                                <option value="Information Technology" >Information Technology</option>
                                <option value="Infrastructure" >Infrastructure</option>
                                <option value="Insurance" >Insurance</option>
                                <option value="Media" >Media</option>
                                <option value="Restaurants" >Restaurants</option>
                                <option value="Science & Technology" >Science & Technology</option>
                                <option value="Telecom" >Telecom</option>
                                <option value="Transportation" >Transportation</option>
                            </select>
                            <span class="error" id="subIndustryError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <input type="text" name="headQuarters" id="headQuarters" class="form-control" placeholder="Headquarters location">
                            <span class="error" id="headQuartersError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <input type="text" name="country" id="country" class="form-control" placeholder="Country/Region">
                            <span class="error" id="countryError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <input type="text" name="organizationURL" id="organizationURL" class="form-control" placeholder="Organisation’s URL">
                            <span class="error" id="organizationURLError"> </span>
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
                        <div class="col-lg-12">
                        <input type="file" name="file" id="file">
                            <label for="file" class="file-upload">
                                <div class="upload">
                                    <div class="icon">
                                        <img src="assets/images/upload.png" alt="">
                                    </div>
                                    <div class="label">Upload Logo</div>
                                </div>
                            </label>
                            <span class="error" id="logoError"> </span>
                        </div>
                        <div class="col-lg-12" id="msg" style="display:none;" > </div>
                        <div class="col-lg-12">
                            <button type="submit" class="hoveranim cmnbtn" id="saveOrgRegBtn">
                                <span>Register</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        var message = $('.success__msg');
        // console.log("Token => "+$('meta[name="csrf-token"]').attr('content'))
        $('#registerOrgForm').submit(function(event){
            event.preventDefault();
            $('.error').html('');

            // var formData = $('#saveUserRegister').serialize();
            // var formData = $('#saveUserRegister').serialize();
            var formData = new FormData(this);
            if(validateOrgRegistrationForm()) {
                // console.log("VAlid");
                $.ajax({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ route('saveAdminOrgInfo') }}",
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
                            $("#success_status_msg").html();
                            $("#success_status_msg").show();
                            // $("#msg").addClass("successDiv");
                            $("#success_status_msg").html(data.messages);
                            setTimeout(() => {
                                $("#success_status_msg").html('');
                                $("#success_status_msg").hide();
                                if(data.addUser == 'yes') {
                                    $("#adminDiv").html();
                                    $("#adminDiv").load("{{URL::To('/getUsersView')}}");
                                } else {
                                    window.location = "{{URL::To('/')}}";
                                }
                            }, 2000);
                        }  else if(data.status == 'error') {
                            $("#error_status_msg").html();
                            $("#error_status_msg").show();
                            // $("#error_status_msg").addClass("errorDiv");
                            $("#error_status_msg").html(data.messages);
                            setTimeout(() => {
                                $("#error_status_msg").html('');
                                $("#error_status_msg").hide();
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
                return false;
            }
        });
    });
</script>