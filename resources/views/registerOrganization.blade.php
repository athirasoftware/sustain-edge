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
                <form id="registerOrgForm" action="" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="fullName">Full Name<span class="important">*</span></label>
                            <input type="text" name="fullName" id="fullName" value="" class="form-control"  placeholder="Enter Full Name">
                            <span class="error" id="fullNameError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="email">Business Email<span class="important">*</span></label>
                            <input type="text" name="email" id="email" value="" class="form-control" placeholder="Enter Business Email">
                            <span class="error" id="emailError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="nameofOrg">Organization Name<span class="important">*</span></label>
                            <input type="text" name="nameofOrg" id="nameofOrg" value="" class="form-control" placeholder="Enter Name of your Organization">
                            <span class="error" id="nameofOrgError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="sizeofOrg">Size of the Organization<span class="important">*</span></label>
                            <input type="text" name="sizeofOrg" id="sizeofOrg" value="" class="form-control" placeholder="Enter Size of the Organization">
                            <span class="error" id="sizeofOrgError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="industry">Industry</label>
                            <select class="form-control" name="industry" id="industry">
                                <option value="" selected>Please Select Industry</option>
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
                            <label for="subIndustry">Sub Industry</label>
                            <select class="form-control" name="subIndustry" id="subIndustry">
                            <option value="" >Please Select Sub Industry</option>
                                <option value="Banking"> Banking</option>
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
                            <label for="headQuarters">Head Quaters Location</label>
                            <input type="text" name="headQuarters" id="headQuarters" value="" class="form-control" placeholder="Enter Head quarters location">
                            <span class="error" id="headQuartersError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="country">Region<span class="important">*</span></label>
                            <input type="text" name="country" id="country" value="" class="form-control" placeholder="Country/Region">
                            <span class="error" id="countryError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="organizationURL">Organisation’s URL </label>
                            <input type="text" name="organizationURL" id="organizationURL" value="" class="form-control" placeholder="Organisation’s URL">
                            <span class="error" id="organizationURLError"> </span>
                        </div>
                        <div class="col-lg-4" style="display:none">
                            <label for="role">Role </label>
                            {{ Form::select('role', (isset($roles) && count($roles) > 0)?$roles:[], 1, ['class' => 'form-control', 'id' => 'role', 'name' => 'role']) }}
                            <span class="error" id="roleError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="dept">Department<span class="important">*</span> </label>
                            <input type="text" id="dept" name="dept" value="" class="form-control" placeholder="Enter Department">
                            <span class="error" id="deptError"> </span>
                        </div>
                    <div class="col-lg-4">
                        <label for="password">Password<span class="important">*</span> </label>
                        <input type="password"  name="password" id="password"  value="" class="form-control" placeholder="Enter Password">
                        <span class="error" id="passwordError"> </span>
                    </div> 
                    <div class="col-lg-4">
                        <label for="password">Confirm Password<span class="important">*</span> </label>
                        <input type="password"  name="password_confirmation" id="password_confirmation" value=""  class="form-control" placeholder="Re Enter Password">
                        <span class="error" id="confirmPasswordError"> </span>
                    </div> 
                        <div class="col-lg-12">
                        <input type="file" name="file" id="file" class="upload-file-view">
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
@include('includes.footer')

<script type="text/javascript">
    $(document).ready(function () {
        var message = $('.success__msg');
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
                    url:"{{ route('saveOrgInfo') }}",
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
                                $('#registerOrgForm')[0].reset();
                                if(data.addUser == 'yes') {
                                    $("#adminDiv").html();
                                    $("#adminDiv").load("{{URL::To('/getUsersView')}}");
                                } else {
                                    window.location = "{{URL::To('/')}}";
                                }
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
                console.log("Invalid");
                return false;
            }
        });
    });
</script>