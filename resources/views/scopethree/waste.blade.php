@include('includes.header')
<?php

use App\Models\Company;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

$userCompany =  Company::find(Auth::user()->company_id);
$loginUserId =  Crypt::encrypt(Auth::user()->id);
?>
<div id="pageWrapper" class="cmnPage sidebarPage">

    <section id="scope">
        <div class="container">
            <div class="titleBx">
                <div class="icon">
                    <img src="assets/images/logo.png" alt="">
                </div>
                <h2>GHG Emissions Calculator</h2>
            </div>
            <div class="cmnBx">
                <div class="flx">
                    <div class="lefBx">
                        <div class="name">Hello @if(isset(Auth::user()->full_name) && Auth::user()->full_name != '' ) {{ Auth::user()->full_name }} @else 'Name N/A' @endif
                            @if(Auth::User()->hasRole('Administrator'))
                            (Admin),
                            @elseif(Auth::User()->hasRole('Team Lead'))
                            (Team Lead),
                            @elseif(Auth::User()->hasRole('Employee'))
                            (Employee),
                            @else
                            ,
                            @endif
                        </div>
                        <div class="cmpny">@if(isset($userCompany->name_of_org) && $userCompany->name_of_org != '' ) {{ $userCompany->name_of_org }} @else 'Company N/A'@endif </div>
                        <a href="{{ route('logout') }}" class="links logoutBtn link-text">Logout</a>
                        {{ Form::hidden('encryptedUserId', (isset(Auth::user()->id) && Auth::user()->id != '')?Crypt::encrypt(Auth::user()->id):'', [ 'id' => 'encryptedUserId']) }}
                        <div id="questionariesDiv">
                            <div class="sideBox">
                                @include('includes.sideBar')
                            </div>
                        </div>
                    </div>
                    <div class="rtsec">
                        <div class="topBox">
                            @include('includes.ghgSubHead')

                            <div id="ghgDiv"></div>
                            <div class="accordion" id="dashBoarAccord">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#dashBoarAccord1" aria-expanded="true" aria-controls="dashBoarAccord1">
                                            Qustionnaire
                                        </button>
                                    </h2>
                                    <div id="dashBoarAccord1" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#dashBoarAccord">
                                        <div class="accordion-body ">

                                            @if(isset($savedPurchasedItems) && count($savedPurchasedItems) > 0)
                                            <div class="cmnBx">
                                                <div class="item-list" id="duplicateContent">
                                                    @foreach($savedPurchasedItems as $key => $savedPurchasedItem)
                                                    <?php
                                                    $key_id = $savedPurchasedItem['id'];
                                                    $randId = rand(100, 100000);
                                                    ?>
                                                    <div class="item">
                                                        <div class="itemBx">
                                                            <div class="left">
                                                                <div class="txtT">
                                                                    <b>Purchased Item:</b> <span id="editStationaryParticularText_{{$randId}}">{{ $savedPurchasedItem['wa_waste_type'] }}</span>
                                                                    <span class="px-2" id="editStationaryParticularText_{{$randId}}">({{ $savedPurchasedItem['wa_quantity'] }} {{$savedPurchasedItem['wa_uom']}})</span>
                                                                </div>
                                                            </div>
                                                            <div class="rtsertBxc" id="selectedView_{{$randId}}">
                                                                <div class="item">
                                                                    <button onclick="editView('{{$randId}}', '{{Crypt::encrypt($key_id)}}');" class="cmnbtn hoveranim"><span>Edit</span></button>
                                                                </div>
                                                                <div class="item">
                                                                    <button onclick="deleteStationaryCombustion('{{Crypt::encrypt($key_id)}}');" class="cmnbtn hoveranim"><span>Remove</span></button>
                                                                </div>
                                                            </div>
                                                            <div id="selectedViewEdit_{{$randId}}" style="display:none">

                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-lg-12 displayMsg" id="displayMsg"></div>

                                            <div class="cmnBx">
                                                <div class="mainTitle">4. Waste</div>
                                                <form action="javascript:void(0)" id="purchase_good_service_form">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="">Waste Type *</label>
                                                            <input type="text" name="waste_type" id="waste_type" class="form-control waste_type">
                                                            <!-- <select name="waste_type" id="waste_type" class="form-control waste_type">
                                                                <option value="">select</option>
                                                                <option value="Construction - Asphalt">Construction - Asphalt</option>
                                                                <option value="Construction - Asbestos">Construction - Asbestos</option>
                                                                <option value="Plastic">Plastic</option>
                                                            </select> -->
                                                            <span class="error waste_typeError"> </span>
                                                        </div>
                                                        <div class="col-lg-6">  
                                                            <label for="">Region *</label>
                                                            <select name="wa_region_id" id="wa_region_id" class="form-control wa_region_id">
                                                                <option value="">select</option>
                                                                <option value="India">India</option>
                                                                <option value="US">US</option>
                                                                <option value="UK">UK</option>
                                                            </select>
                                                            <span class="error wa_region_idError"> </span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">EF Data*</label>
                                                            <select name="wa_ef_data" id="wa_ef_data" class="form-control wa_ef_data">
                                                                <option value="">select</option>
                                                                <option value="US EPA">US EPA</option>
                                                                <option value="UK DEFRA">UK DEFRA</option>
                                                                <option value="Custom Emission Factor">Custom Emission Factor</option>
                                                            </select>
                                                            <span class="error wa_ef_dataError"> </span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">Water treatment type*</label>
                                                            <select name="wa_treatment_type" id="wa_treatment_type" class="form-control wa_treatment_type">
                                                                <option value="">select</option>
                                                                <option value="Recycled">Recycled</option>
                                                                <option value="Landfill">Landfill</option>
                                                                <option value="Combustion">Combustion</option>
                                                                <option value="Composted">Composted</option>
                                                            </select>
                                                            <span class="error wa_treatment_typeError"> </span>
                                                        </div>
                                                        <!-- <div class="col-lg-6">
                                                            <label for="">Water treatment type*</label>
                                                            <select name="waste_treatment_type" id="waste_treatment_type" class="form-control waste_treatment_type">
                                                                <option value="">select</option>
                                                                <option value="Distance">Distance</option>
                                                                <option value="Passenger distance">Passenger distance</option>
                                                                <option value="vehicle distance">Vehicle distance</option>
                                                                <option value="Weight distance">Weight distance</option>
                                                            </select>
                                                            <span class="error waste_treatment_typeError"> </span>
                                                        </div> -->
                                                        <div class="col-lg-6">
                                                            <label for="">Activity *</label>
                                                            <select name="wa_activity" id="wa_activity" class="form-control wa_activity">
                                                                <option value="">select</option>
                                                                <option value="Distance">Distance</option>
                                                                <option value="Passenger distance">Passenger distance</option>
                                                                <option value="Vehicle distance">Vehicle distance</option>
                                                                <option value="Weight distance">Weight distance</option>
                                                            </select>
                                                            <span class="error wa_activityError"> </span>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <label for="">Unit of Measurement *</label>
                                                            <select name="wa_uom" id="wa_uom" class="form-control wa_uom">
                                                                <option value="">select</option>
                                                                <option value="Km">Km</option>
                                                                <option value="Passenger Km">Passenger Km</option>
                                                                <option value="Vehicle Km">Vehicle Km</option>
                                                                <option value="Tonne Km">Tonne Km</option>
                                                            </select>
                                                            <span class="error wa_uomError"> </span>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <label for="">Quantity *</label>
                                                            <input type="text" name="wa_quantity" class="form-control wa_quantity" id="wa_quantity">
                                                            <input type="hidden" name="wa_item_id" class="form-control wa_item_id" id="wa_item_id">
                                                            <span class="error wa_quantityError"> </span>
                                                        </div>
                                                    </div>
                                                    <div class="row cntr">
                                                        <div class="col-lg-6">
                                                            <div class="rtsertBxc">
                                                                <div class="item">
                                                                    <button type="cancel" class="cmnbtn hoveranim purchase_good_service_cancel"><span>Cancel</span></button>
                                                                </div>
                                                                <div class="item">
                                                                    <button type="button" class="cmnbtn hoveranim purchase_good_service_save"><span>Save</span></button>
                                                                    <button type="button" class="cmnbtn hoveranim hide purchase_good_service_update"><span>Update</span></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </section>
    <script>

    </script>
</div>

@include('includes.footer')
<script>
    $(document).ready(function() {
        let activityArr = {'Distance':'Km', 'Passenger distance':'Passenger.Km','Vehicle distance':'Vehicle.Km','Weight distance':'Tonne.Km'}
        $(".purchase_good_service_cancel").on('click', function() {
            $("#purchase_good_service_form")[0].reset();
        })
        $(".purchase_good_service_update, .purchase_good_service_save_update").on('click', function() {
            saveOrUpdate('updateWaste');
        });
        $(".purchase_good_service_save").on('click', function() {
            saveOrUpdate('saveWaste');
        });
        $(".wa_activity").on('change', function() {
            let wa_activity = $(this).val();
            if(wa_activity != ''){
                //console.log(wa_activity, activityArr[wa_activity])
                let options = '<option value="'+activityArr[wa_activity]+'">'+activityArr[wa_activity]+'</option>';
                $(".wa_uom").html(options)
            }
        });

        function saveOrUpdate(pathName) {
            event.preventDefault();
            $('.error').html('');
            //function addNewUser(formId) {
            var waste_type = $.trim($(".waste_type").val());
            var wa_region_id = $.trim($(".wa_region_id").val());
            var wa_ef_data = $.trim($('.wa_ef_data').val());
            var wa_treatment_type = $.trim($('.wa_treatment_type').val());
            var waste_treatment_type = $.trim($(".waste_treatment_type").val());
            var wa_activity = $.trim($(".wa_activity").val());
            var wa_uom = $.trim($(".wa_uom").val());
            var wa_quantity = $.trim($(".wa_quantity").val());
            var isValid = true;
            if (waste_type == '' || typeof waste_type === 'undefined') {
                $('.waste_typeError').html('Waste Type Should Not Be Empty');
            }
            if (wa_region_id == '' || typeof wa_region_id === 'undefined') {
                $('.wa_region_idError').html('Region Id Should Not Be Empty');
                isValid = false;
            }
            if (wa_ef_data == '' || typeof wa_ef_data === 'undefined') {
                $('.wa_ef_dataError').html('EF Data Should Not Be Empty');
                isValid = false;
            }
            if (wa_treatment_type == '') {
                $('.wa_treatment_typeError').html('Water treatment type Should Not Be Empty');
                isValid = false;
            }
            if (wa_activity == '') {
                $('.wa_activityError').html('Activity should Not Be Empty');
                isValid = false;
            }
            if (wa_uom == '') {
                $('.wa_uomError').html('UoM should Not Be Empty');
                isValid = false;
            }
            if (wa_quantity == '') {
                $('.wa_quantityError').html('Quantity should Not Be Empty');
                isValid = false;
            }
            if (pathName == 'saveWaste') {
                var urlPath = "{{ url('/saveWaste') }}";
                var wa_item_id = null;
            } else {
                var urlPath = "{{ url('/updateWaste') }}";
                //$('.purchase_good_service_item_Id').val()
                var wa_item_id = $('.wa_item_id').val();
            }
            // console.log(" dept Error isValid => "+isValid);
            if (isValid) {
                // console.log("trying to hit ajax call");
                var jsonData = {
                    "wa_waste_type": waste_type,
                    "wa_region_id": wa_region_id,
                    "wa_ef_data": wa_ef_data,
                    "wa_treatment_type": wa_treatment_type,
                    "wa_activity": wa_activity,
                    "wa_quantity": wa_quantity,
                    "wa_uom": wa_uom,
                    "wa_item_id": wa_item_id,
                    "_token": "{{ csrf_token() }}",
                };
                console.log(jsonData);
                $.ajax({
                    url: urlPath,
                    method: 'post',
                    dataType: "JSON",
                    async: false,
                    data: jsonData,
                    success: function(data) {
                        // console.log(data);
                        if (data.status == 'success') {
                            //bootbox.alert(data.message);
                            $('#displayMsg').html('');
                            $('#displayMsg').show();
                            $('#displayMsg').html('<span class="success">' + data.message + '</span>');
                            location.reload();
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
        }
    })
</script>
<script type="text/javascript">
    $(document).ready(function() {
        // var activeBtn = 'usersBtn';
        var activeBtn = 'questionnaireBtn';
        setTimeout(() => {
            $('#' + activeBtn).trigger('click');
        }, 500);
        $('#sedge-sub-head').html('');
        $('#questionnaireBtn').click(function(event) {
            $('#sedge-sub-head').html('GHG Emissions Calculator');
            activeBtn = 'questionnaireBtn';
            loadGHGViewByDivId(activeBtn);
        });

        $('#usersBtn').click(function(event) {
            $('#sedge-sub-head').html('Users');
            activeBtn = 'usersBtn';
            loadGHGViewByDivId(activeBtn);
        });

        $('#settingsBtn').click(function(event) {
            $('#sedge-sub-head').html('User Settings');
            activeBtn = 'settingsBtn';
            loadGHGViewByDivId(activeBtn);
        });
        $('#reportBtn').click(function(event) {
            $('#sedge-sub-head').html('User Settings');
            activeBtn = 'reportBtn';
            loadGHGViewByDivId(activeBtn);
        });
        $('#gobackBtn').click(function(event) {
            window.location.href = "{{ route('home') }}";
        });
    });

    function loadGHGViewByDivId(activeBtn) {
        //alert(activeBtn);
        $('.accordion-button').addClass('collapsed');
        $('#' + activeBtn).removeClass('collapsed');
        $('.usersSubList').removeClass('active');
        $("#ghgDiv").html('');
        if (activeBtn == 'usersBtn') {
            $('#allUsersBtn').addClass('active');
            $("#userSideBarDiv").show();
            $("#dashBoarAccord").hide();
            var url = "{{URL::TO('ghgUserList')}}";
        } else if (activeBtn == 'questionnaireBtn') {
            //alert('test rana');
            $("#userSideBarDiv").hide();
            $("#dashBoarAccord").show();
            //var url = "{{URL::TO('purchasedgoodsandservices')}}";
        } else if (activeBtn == 'reportBtn') {
            $("#userSideBarDiv").hide();
            $("#dashBoarAccord").hide();
            $("#reportsPage").show();
            var url = "{{URL::TO('ghgReportData')}}";
        } else if (activeBtn == 'settingsBtn') {
            $("#userSideBarDiv").hide();
            $("#dashBoarAccord").hide();
            var loginUserId = $('#loginUserId').val();
            if (loginUserId == '' || typeof loginUserId === 'undefined') {
                bootbox.alert("Invalid User Found!");
            }
            var url = "{{URL::TO('/ghgUserEditScreen')}}/" + loginUserId;
        }
        if (url != '' && typeof url !== 'undefined') {
            $("#ghgDiv").load(url);
        }
    }


    function loadGHGViewBySubDivId(event) {
        var activeSubBtn = $(event).attr("id");
        $("#ghgDiv").html('');
        $('.usersSubList').removeClass('active');
        // console.log("clicked on "+activeSubBtn);
        $('#' + activeSubBtn).addClass('active');
        if (activeSubBtn == 'allUsersBtn') {
            var url = "{{URL::TO('ghgUserList')}}";
        } else if (activeSubBtn == 'addNewUsersBtn') {
            var url = "{{URL::TO('addGHGNewUser')}}";
        }
        if (url != '' && typeof url !== 'undefined') {
            $("#ghgDiv").load(url);
        }
    }

    function editView(randomId, purchase_id) {
        $.ajax({
            url: "{{ url('/editWaste') }}",
            method: 'post',
            dataType: "JSON",
            data: {
                "purchase_id": purchase_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                console.log(data.result)
                if (data != '') {
                    console.log('testing007')
                    $('.displayMsg').html('');
                    $('.displayMsg').show();
                    $('.displayMsg').html('<span class="success">Please Updated Selected Data!</span>');
                    $(".mainTitle").text('4. Edit Waste');
                    $(".waste_type").val(data.result.wa_waste_type);
                    $(".wa_region_id").val(data.result.wa_region_id);
                    $(".wa_ef_data").val(data.result.wa_ef_data);
                    $(".wa_treatment_type").val(data.result.wa_treatment_type);
                    $(".wa_activity").val(data.result.wa_activity);
                    $(".wa_quantity").val(data.result.wa_quantity);
                    var options = '<option value="'+data.result.wa_uom+'">'+data.result.wa_uom+'</option>';
                    $(".wa_uom").html(options);
                    $(".wa_item_id").val(data.result.encrptid);
                    $(".purchase_good_service_save").addClass('hide');
                    $(".purchase_good_service_update").removeClass('hide');
                } else {
                    console.log('testing555')
                    // bootbox.alert(data.message);
                    $('.displayMsg').html('');
                    $('.displayMsg').show();
                    $('.displayMsg').html('<span class="error">Unable to Edit Selected Record. Contact Admin.</span>');
                }
            }
        });
    }

    function deleteStationaryCombustion(selectedFuelId) {
        // console.log(selectedFuelId);
        $.ajax({
            url: "{{ url('/deleteWaste') }}",
            method: 'post',
            dataType: "JSON",
            data: {
                "selectedId": selectedFuelId,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                // console.log(data);
                if (data.status == 'success') {
                    // bootbox.alert(data.message);
                    $('.displayMsg').html('');
                    $('.displayMsg').show();
                    $('.displayMsg').html('<span class="success">' + data.message + '</span>');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else if (data.status == 'error') {
                    // bootbox.alert(data.message);
                    $('.displayMsg').html('');
                    $('.displayMsg').show();
                    $('.displayMsg').html('<span class="error">' + data.message + '</span>');
                }
            }
        });
    }
</script>