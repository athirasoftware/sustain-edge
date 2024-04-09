<?php  
    
    use Illuminate\Support\Facades\Crypt;
    $industries = $subIndustries = ["" => "Please Select Industry", 
                    "Banking" => "Banking", "Business Services" => "Business Services", "Construction" => "Construction", 
                    "Education" => "Education", "Healthcare" => "Healthcare", "Information Technology" => "Information Technology",
                    "Infrastructure" => "Infrastructure", "Insurance" => "Insurance", "Media" => "Media", "Restaurants" => "Restaurants",
                    "Science & Technology" => "Science & Technology", "Telecom" => "Telecom", "Transportation" => "Transportation"
                 ];
    ?>
<style>
    #pageWrapper {
        overflow-x: visible !important;
        background: url();
    }
    .registerBx input[type=file] {
        display: none; 
    }
</style>
<div id="pageWrapper" class="loginPage">
    <section id="register">
        <div class="container loginPage">
            <div class="registerBx">
                <div class="title">Help us know more about you and <span>your</span> company</div>
                <form id="registerOrgForm" action="" method="POST" enctype="multipart/form-data">
                    {{ Form::hidden('addUser', (isset($type) && $type != '')?$type:'yes') }}
                    {{ Form::hidden('userId', (isset($userDetails->id) && $userDetails->id != '')?Crypt::encrypt($userDetails->id):'') }}
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="fullName">Full Name<span class="important">*</span></label>
                            <input type="text" name="fullName" id="fullName" value="{{ $userDetails->full_name }}" class="form-control"  placeholder="Full Name">
                            <span class="error" id="fullNameError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="email">Business Email<span class="important">*</span></label>
                            <input type="text" name="email" id="email" value="{{ $userDetails->email }}" class="form-control" placeholder="Business Email">
                            <span class="error" id="emailError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="nameofOrg">Organization Name<span class="important">*</span></label>
                            <input type="text" name="nameofOrg" id="nameofOrg" value="{{ $userCompany->name_of_org }}" class="form-control" placeholder="Name of your Organization">
                            <span class="error" id="nameofOrgError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="sizeofOrg">Size of the Organization<span class="important">*</span></label>
                            <input type="text" name="sizeofOrg" id="sizeofOrg" value="{{ $userCompany->size_of_org }}" class="form-control" placeholder="Size of the Organization">
                            <span class="error" id="sizeofOrgError"> </span>
                        </div>
                        <div class="col-lg-4">
                        <label for="industry">Industry</label>
                            {{ Form::select('industry', (isset($industries) && count($industries) > 0)?$industries:[], $userCompany->industry, ['class' => 'form-control', 'id' => 'industry', 'name' => 'industry']) }}
                            <span class="error" id="industryError"> </span>
                        </div>
                        <div class="col-lg-4">
                        <label for="subIndustry">Sub Industry</label>
                            {{ Form::select('subIndustry', (isset($subIndustries) && count($subIndustries) > 0)?$subIndustries:[], $userCompany->sub_industry, ['class' => 'form-control', 'id' => 'subIndustry', 'name' => 'subIndustry']) }}
                            <span class="error" id="subIndustryError"> </span>
                        </div>
                        <div class="col-lg-4">
                        <label for="headQuarters">Head Quaters Location</label>
                            <input type="text" name="headQuarters" id="headQuarters" value="{{ $userCompany->head_quarters }}" class="form-control" placeholder="Headquarters location">
                            <span class="error" id="headQuartersError"> </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="country">Region<span class="important">*</span></label>
                            <input type="text" name="country" id="country" value="{{ $userCompany->country }}" class="form-control" placeholder="Country/Region">
                            <span class="error" id="countryError"> </span>
                        </div>
                        <div class="col-lg-4">
                        <label for="organizationURL">Organisation’s URL </label>
                            <input type="text" name="organizationURL" id="organizationURL" value="{{ $userCompany->org_url }}" class="form-control" placeholder="Organisation’s URL">
                            <span class="error" id="organizationURLError"> </span>
                        </div>
                        <div class="col-lg-4">
                        <label for="role">Role </label>
                        {{ Form::select('role', (isset($roles) && count($roles) > 0)?$roles:[], $userDetails->role, ['class' => 'form-control', 'id' => 'role', 'name' => 'role']) }}
                        <span class="error" id="roleError"> </span>
                    </div>
                    <div class="col-lg-4">
                    <label for="dept">Department<span class="important">*</span> </label>
                        <input type="text" id="dept" name="dept" value="{{ $userDetails->department }}" class="form-control" placeholder="Enter Department">
                        <span class="error" id="deptError"> </span>
                    </div>
                    <div class="col-lg-4">
                    </div> 
                    <div class="col-lg-4">
                        <label for="password">Password<span class="important">*</span> </label>
                        <input type="password"  name="password" id="password" value=""  class="form-control" placeholder="Enter Password">
                        <span class="error" id="passwordError"> </span>
                    </div> 
                    <div class="col-lg-4">
                        <label for="password_confirmation">Confirm Password<span class="important">*</span> </label>
                        <input type="password"  name="password_confirmation" id="password_confirmation" value=""  class="form-control" placeholder="Re Enter Password">
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
                        <div id="displayMsg" style="display:none"><span></span></div>
                        <div class="col-lg-12 row">
                            <div class="col-lg-6">
                                <button  class="hoveranim cmnbtn" id="cancelBtn">
                                    <span>Cancel</span>
                                </button>
                            </div>
                            <div class="col-lg-6">
                                <button type="submit" class="hoveranim cmnbtn" id="saveOrgRegBtn">
                                    <span>Update</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.locales.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        $('#cancelBtn').click((evnt)=>{
            evnt.preventDefault();
            bootbox.confirm({
                message: 'All changes will discard, Are you sure?',
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    console.log('This was logged in the callback: ' + result);
                    if(result) {
                        $("#adminDiv").html('');
                        $("#adminDiv").load("{{ route('getUsersView') }}"); 
                    }
                }
            });

        });
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
                    url:"{{ route('updateUserInfo') }}",
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
                                $('#displayMsg').html('');
                                $('#displayMsg').show();
                                $('#displayMsg').html('<span class="success">'+data.messages+'</span>');
                                setTimeout(() => {
                                    $('#displayMsg').html('');
                                    $('#displayMsg').hide();
                                    if(data.addUser == 'yes') {
                                        $("#adminDiv").html();
                                        $("#adminDiv").load("{{URL::To('/getUsersView')}}");
                                    } else {
                                        window.location = "{{URL::To('/')}}";
                                    }
                                }, 2000);
                        }  else if(data.status == 'error') {
                            $('#displayMsg').html('');
                            $('#displayMsg').show();
                            $('#displayMsg').html('<span class="error">'+data.messages+'</span>');
                                
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