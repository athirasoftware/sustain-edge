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

                                            @if(isset($businessTravelList) && count($businessTravelList) > 0)
                                            <div class="cmnBx">
                                                <div class="item-list" id="duplicateContent">
                                                    @foreach($businessTravelList as $key => $buList)
                                                    <?php
                                                    $key_id = $buList['id'];
                                                    $randId = rand(100, 100000);
                                                    ?>
                                                    <div class="item">
                                                        <div class="itemBx">
                                                            <div class="left">
                                                                <div class="txtT">
                                                                    <b>Purchased Item:</b> <span id="editStationaryParticularText_{{$randId}}">{{ $buList['ds_particulars'] }}</span>
                                                                    <span class="px-2" id="editStationaryParticularText_{{$randId}}">({{ $buList['ds_quantity'] }} {{$buList['ds_uom']}})</span>
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
                                                <div class="mainTitle">8. Downstream T&D</div>
                                                <form action="javascript:void(0)" id="purchase_good_service_form">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="">Particulars *</label>
                                                            <input type="text" name="ds_particulars" id="ds_particulars" class="form-control ds_particulars">
                                                            <span class="ds_particularsError"></span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">Region *</label>
                                                            <select name="ds_region" id="ds_region" class="form-control ds_region">
                                                                <option value="">select</option>
                                                                <option value="India">India</option>
                                                                <option value="US">US</option>
                                                                <option value="UK">UK</option>
                                                            </select>
                                                            <span class="ds_regionError"></span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">EF Data*</label>
                                                            <input name="ds_emission_factor_data" id="ds_emission_factor_data" class="ds_emission_factor_data form-control">
                                                                <!-- <option value="">select</option>
                                                                <option value="US EPA">US EPA</option>
                                                                <option value="UK DEFRA">UK DEFRA</option>
                                                            </select> -->
                                                            <!-- <input type="text" name="ds_emission_factor_data" id="ds_emission_factor_data" class="ds_emission_factor_data form-control"> -->
                                                            <span class="ds_emission_factor_dataError"></span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">Mode of *</label>
                                                            <select name="ds_mode_of_transportation" id="ds_mode_of_transportation" class="ds_mode_of_transportation form-control">
                                                                <option value="">Select Transport Mode</option>
                                                                @if(isset($transport_mode) && count($transport_mode) > 0)
                                                                @foreach($transport_mode as $ind => $modes)
                                                                <option value="{{$modes['id']}}">{{$modes['motm_transport_mode']}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <span class="ds_mode_of_transportationError"></span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">Type of Transportation*</label>
                                                            <select name="ds_type_of_transportation" id="ds_type_of_transportation" class="ds_type_of_transportation form-control">
                                                                <option value="">Select Transport Type</option>
                                                            </select>
                                                            <span class="ds_type_of_transportationError"></span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">Activity*</label>
                                                            <select name="ds_activity" id="ds_activity" class="ds_activity form-control">
                                                                <option value="">select</option>
                                                                <option value="Distance">Distance</option>
                                                                <option value="Passenger distance">Passenger distance</option>
                                                                <option value="Vehicle distance">Vehicle distance</option>
                                                                <option value="Weight distance">Weight distance</option>
                                                            </select>
                                                            <span class="ds_activityError"></span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">Unit of Measurement *</label>
                                                            <input type="text" name="ds_uom" id="ds_uom" class="ds_uom form-control">
                                                            <span class="ds_uomError"></span>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <label for="">Quantity *</label>
                                                            <input type="text" class="ds_quantity form-control" id="ds_quantity" name="ds_quantity">
                                                            <input type="hidden" class="ds_item_id form-control" id="ds_item_id" name="ds_item_id">
                                                            <span class="ds_quantityError"></span>
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
        let activityArr = {
            'Distance': 'Km',
            'Passenger distance': 'Passenger.Km',
            'Vehicle distance': 'Vehicle.Km',
            'Weight distance': 'Tonne.Km'
        };
        $(".purchase_good_service_cancel").on('click', function() {
            $("#purchase_good_service_form")[0].reset();
        })
        $(".purchase_good_service_update").on('click', function() {
            saveOrUpdate('updateDownStream');
        });
        $(".purchase_good_service_save").on('click', function() {
            saveOrUpdate('saveDownStream');
        });
        $(".ds_mode_of_transportation").on('change', function() {
            if ($(this).val() != '' && $(this).val() > 0) {
                typeOfTransport($(this).val(), 'transport_mode_id');
            }
        });
        $(".ds_activity").on('change', function() {
            let ds_activity = $(this).val();
            if (ds_activity != '') {
                //console.log(wa_activity, activityArr[wa_activity])
                $(".ds_uom").val(activityArr[ds_activity])
            }
        });

        function saveOrUpdate(pathName) {
            event.preventDefault();
            $('.error').html('');
            //function addNewUser(formId) {
            var ds_particulars = $.trim($(".ds_particulars").val());
            var ds_region = $.trim($(".ds_region").val());
            var ds_emission_factor_data = $.trim($('.ds_emission_factor_data').val());
            var ds_mode_of_transportation = $.trim($('.ds_mode_of_transportation').val());
            var ds_type_of_transportation = $.trim($(".ds_type_of_transportation").val());
            var ds_one_way_return = $.trim($(".ds_one_way_return").val());
            var ds_from = $.trim($(".ds_from").val());
            var ds_to = $.trim($(".ds_to").val());
            var ds_activity = $.trim($(".ds_activity").val());
            var ds_uom = $.trim($(".ds_uom").val());
            var ds_quantity = $.trim($(".ds_quantity").val());
            var isValid = true;
            if (ds_particulars == '' || typeof ds_particulars === 'undefined') {
                $('.ds_particularsError').html('Particulars Should Not Be Empty');
            }
            if (ds_region == '' || typeof ds_region === 'undefined') {
                $('.ds_regionError').html('Region Id Should Not Be Empty');
                isValid = false;
            }
            if (ds_emission_factor_data == '' || typeof ds_emission_factor_data === 'undefined') {
                $('.ds_emission_factor_dataError').html('EF Data Should Not Be Empty');
                isValid = false;
            }
            if (ds_mode_of_transportation == '') {
                $('.wa_treatment_typeError').html('Mode of Transportation Should Not Be Empty');
                isValid = false;
            }
            if (ds_type_of_transportation == '') {
                $('.ds_type_of_transportationError').html('Type of Transportation should Not Be Empty');
                isValid = false;
            }
            if (ds_activity == '') {
                $('.ds_activityError').html('Activity should Not Be Empty');
                isValid = false;
            }
            if (ds_uom == '') {
                $('.ds_uomError').html('UoM should Not Be Empty');
                isValid = false;
            }
            if (ds_quantity == '') {
                $('.ds_quantityError').html('Quantity should Not Be Empty');
                isValid = false;
            }
            if (pathName == 'saveDownStream') {
                var urlPath = "{{ url('/saveDownStream') }}";
                var ds_item_id = null;
            } else {
                var urlPath = "{{ url('/updateDownStream') }}";
                //$('.purchase_good_service_item_Id').val()
                var ds_item_id = $('.ds_item_id').val();
            }
            // console.log(" dept Error isValid => "+isValid);
            if (isValid) {
                // console.log("trying to hit ajax call");
                var jsonData = {
                    "ds_particulars": ds_particulars,
                    "ds_region": ds_region,
                    "ds_emission_factor_data": ds_emission_factor_data,
                    "ds_mode_of_transportation": ds_mode_of_transportation,
                    "ds_type_of_transportation": ds_type_of_transportation,
                    "ds_one_way_return": ds_one_way_return,
                    "ds_from": ds_from,
                    "ds_to": ds_to,
                    "ds_activity": ds_activity,
                    "ds_uom": ds_uom,
                    "ds_quantity": ds_quantity,
                    "ds_item_id": ds_item_id,
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
            url: "{{ url('/editDownStream') }}",
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
                    $(".mainTitle").text('8. Downstream T&D');
                    $(".ds_particulars").val(data.result.ds_particulars);
                    $(".ds_region").val(data.result.ds_region);
                    $(".ds_emission_factor_data").val(data.result.ds_emission_factor_data);
                    $(".ds_mode_of_transportation").val(data.result.ds_mode_of_transportation);
                    typeOfTransport(data.result.ds_type_of_transportation, 'id');
                    //var options = '<option value="'+data.result.ds_type_of_transportation+'">'+data.result.ds_type_of_transportation+'</option>';
                    $(".ds_type_of_transportation").val(data.result.ds_type_of_transportation);
                    $(".ds_one_way_return").val(data.result.ds_one_way_return);
                    $(".ds_from").val(data.result.ds_from);
                    $(".ds_to").val(data.result.ds_to);
                    $(".ds_activity").val(data.result.ds_activity);
                    $(".ds_quantity").val(data.result.ds_quantity);
                    $(".ds_uom").val(data.result.ds_uom);
                    $(".ds_item_id").val(data.result.encrptid);
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
            url: "{{ url('/deleteBusinessTravel') }}",
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

    function typeOfTransport(id, colm_type) {
        var jsonData = {
            "trasport_id": id,
            "colm_type": colm_type,
            "_token": "{{ csrf_token() }}",
        };
        $.ajax({
            url: "{{ url('/typeOfTransport') }}",
            method: 'post',
            dataType: "JSON",
            async: false,
            data: jsonData,
            success: function(data) {
                console.log(data);
                if (data.length > 0) {
                    var options = '<option value="">Select Transport Type</option>';
                    data.forEach(function(list) {
                        //console.log(list);
                        options += '<option value="' + list.id + '">' + list.transport_type + '</option>';
                    });
                    $("#ds_type_of_transportation").html(options);
                } else if (data.length == 0) {
                    $.each(data.messages, function(i, v) {
                        // console.log(i);
                        $("#" + i + "Error").html(v);
                    });
                }
            }
        });
    }
</script>