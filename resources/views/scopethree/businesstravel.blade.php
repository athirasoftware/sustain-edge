@include('includes.header')

<?php

use App\Models\Company;

use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Auth;

$userCompany = Company::find(Auth::user()->company_id);

$loginUserId = Crypt::encrypt(Auth::user()->id);

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

                        <div class="name">Hello @if (isset(Auth::user()->full_name) && Auth::user()->full_name != '')
                                {{ Auth::user()->full_name }}
                            @else
                                'Name N/A'
                            @endif

                            @if (Auth::User()->hasRole('Administrator'))
                                (Admin),
                            @elseif(Auth::User()->hasRole('Team Lead'))
                                (Team Lead),
                            @elseif(Auth::User()->hasRole('Employee'))
                                (Employee),
                            @else
                                ,
                            @endif

                        </div>

                        <div class="cmpny">
                            @if (isset($userCompany->name_of_org) && $userCompany->name_of_org != '')
                                {{ $userCompany->name_of_org }}
                            @else
                                'Company N/A'
                            @endif
                        </div>

                        <a href="{{ route('logout') }}" class="links logoutBtn link-text">Logout</a>

                        {{ Form::hidden('encryptedUserId', isset(Auth::user()->id) && Auth::user()->id != '' ? Crypt::encrypt(Auth::user()->id) : '', ['id' => 'encryptedUserId']) }}

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

                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#dashBoarAccord1" aria-expanded="true"
                                            aria-controls="dashBoarAccord1">

                                            Qustionnaire

                                        </button>

                                    </h2>

                                    <div id="dashBoarAccord1" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#dashBoarAccord">

                                        <div class="accordion-body ">



                                            @if (isset($businessTravelList) && count($businessTravelList) > 0)

                                                <div class="cmnBx">

                                                    <div class="item-list" id="duplicateContent">

                                                        @foreach ($businessTravelList as $key => $buList)
                                                            <?php
                                                            
                                                            $key_id = $buList['id'];
                                                            
                                                            $randId = rand(100, 100000);
                                                            
                                                            ?>

                                                            <div class="item">

                                                                <div class="itemBx">

                                                                    <div class="left">

                                                                        <div class="txtT">

                                                                            <b>Purchased Item:</b> <span
                                                                                id="editStationaryParticularText_{{ $randId }}">{{ $buList['bu_particulars'] }}</span>

                                                                            <span class="px-2"
                                                                                id="editStationaryParticularText_{{ $randId }}">({{ $buList['bu_quantity'] }}
                                                                                {{ $buList['bu_uom'] }})</span>

                                                                        </div>

                                                                    </div>

                                                                    <div class="rtsertBxc"
                                                                        id="selectedView_{{ $randId }}">

                                                                        <div class="item">

                                                                            <button
                                                                                onclick="editView('{{ $randId }}', '{{ Crypt::encrypt($key_id) }}');"
                                                                                class="cmnbtn hoveranim"><span>Edit</span></button>

                                                                        </div>

                                                                        <div class="item">

                                                                            <button
                                                                                onclick="deleteStationaryCombustion('{{ Crypt::encrypt($key_id) }}');"
                                                                                class="cmnbtn hoveranim"><span>Remove</span></button>

                                                                        </div>

                                                                    </div>

                                                                    <div id="selectedViewEdit_{{ $randId }}"
                                                                        style="display:none">



                                                                    </div>

                                                                </div>

                                                            </div>
                                                        @endforeach

                                                    </div>

                                                </div>

                                            @endif

                                            <div class="col-lg-12 displayMsg" id="displayMsg"></div>



                                            <div class="cmnBx">

                                                <div class="mainTitle">5. Business Travel</div>

                                                <form action="javascript:void(0)" id="purchase_good_service_form">

                                                    <div class="row">

                                                        <div class="col-lg-6">

                                                            <label for="">Particulars *</label>

                                                            <input type="text" name="bu_particulars"
                                                                id="bu_particulars" class="form-control bu_particulars">

                                                            <span class="bu_particularsError"></span>

                                                        </div>

                                                        <div class="col-lg-6">

                                                            <label for="">Region *</label>

                                                            <select name="bu_region" id="bu_region"
                                                                class="form-control bu_region">

                                                                <option value="">select</option>

                                                                <option value="India">India</option>

                                                                <option value="US">US</option>

                                                                <option value="UK">UK</option>

                                                            </select>

                                                            <span class="bu_regionError"></span>

                                                        </div>

                                                        <div class="col-lg-6">

                                                            <label for="">EF Data*</label>

                                                            <select name="bu_emission_factor_data"
                                                                id="bu_emission_factor_data"
                                                                class="bu_emission_factor_data form-control">

                                                                <option value="">select</option>

                                                                <option value="US EPA">US EPA</option>

                                                                <option value="UK DEFRA">UK DEFRA</option>

                                                            </select>

                                                            <!-- <input type="text" name="bu_emission_factor_data" id="bu_emission_factor_data" class="bu_emission_factor_data form-control"> -->

                                                            <span class="bu_emission_factor_dataError"></span>

                                                        </div>

                                                        <div class="col-lg-6">

                                                            <label for="">Mode of *</label>

                                                            <select name="bu_mode_of_transportation"
                                                                id="bu_mode_of_transportation"
                                                                class="bu_mode_of_transportation form-control">

                                                                <option value="">Select Transport Mode</option>

                                                                @if (isset($transport_mode) && count($transport_mode) > 0)

                                                                    @foreach ($transport_mode as $ind => $modes)
                                                                        <option value="{{ $modes['id'] }}">
                                                                            {{ $modes['motm_transport_mode'] }}
                                                                        </option>
                                                                    @endforeach

                                                                @endif

                                                            </select>

                                                            <span class="bu_mode_of_transportationError"></span>

                                                        </div>

                                                        <div class="col-lg-6">

                                                            <label for="">Type of Transportation*</label>

                                                            <select name="bu_type_of_transportation"
                                                                id="bu_type_of_transportation"
                                                                class="bu_type_of_transportation form-control">

                                                                <option value="">Select Transport Type</option>

                                                            </select>

                                                            <span class="bu_type_of_transportationError"></span>

                                                        </div>

                                                        <div class="col-lg-6">

                                                            <label for="">One Way/Return</label>

                                                            <select name="bu_one_way_return" id="bu_one_way_return"
                                                                class="bu_one_way_return form-control">

                                                                <option value="">Select One Option</option>

                                                                <option value="One Way">One Way</option>

                                                                <option value="Return">Return</option>

                                                            </select>

                                                            <span class="bu_one_way_returnError"></span>

                                                        </div>



                                                        <div class="col-lg-6">

                                                            <label for="">From</label>

                                                            <input type="text" class="bu_from form-control"
                                                                name="bu_from" id="bu_from">

                                                            <span class="bu_fromError"></span>

                                                        </div>

                                                        <div class="col-lg-6">

                                                            <label for="">to</label>

                                                            <input type="text" class="form-control bu_to"
                                                                name="bu_to" id="bu_to">

                                                            <span class="bu_toError"></span>

                                                        </div>



                                                        <div class="col-lg-6">

                                                            <label for="">Activity*</label>

                                                            <select name="bu_activity" id="bu_activity"
                                                                class="bu_activity form-control">

                                                                <option value="">select</option>

                                                                <option value="Distance">Distance</option>

                                                                <option value="Passenger distance">Passenger distance
                                                                </option>

                                                            </select>

                                                            <span class="bu_activityError"></span>

                                                        </div>

                                                        <div class="col-lg-6">

                                                            <label for="">Unit of Measurement *</label>

                                                            <input type="text" name="bu_uom" id="bu_uom"
                                                                class="bu_uom form-control">

                                                            <span class="bu_uomError"></span>

                                                        </div>



                                                        <div class="col-lg-6">

                                                            <label for="">Quantity *</label>

                                                            <input type="text" class="bu_quantity form-control"
                                                                id="bu_quantity" name="bu_quantity">

                                                            <input type="hidden" class="bu_item_id form-control"
                                                                id="bu_item_id" name="bu_item_id">

                                                            <span class="bu_quantityError"></span>

                                                        </div>

                                                    </div>

                                                    <div class="row cntr">

                                                        <div class="col-lg-6">

                                                            <div class="rtsertBxc">

                                                                <div class="item">

                                                                    <button type="cancel"
                                                                        class="cmnbtn hoveranim purchase_good_service_cancel"><span>Cancel</span></button>

                                                                </div>

                                                                <div class="item">

                                                                    <button type="button"
                                                                        class="cmnbtn hoveranim purchase_good_service_save"><span>Save</span></button>

                                                                    <button type="button"
                                                                        class="cmnbtn hoveranim hide purchase_good_service_update"><span>Update</span></button>

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

    <script></script>

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

            saveOrUpdate('updateBusinessTravel');

        });

        $(".purchase_good_service_save").on('click', function() {

            saveOrUpdate('saveBusinessTravel');

        });

        $(".bu_mode_of_transportation").on('change', function() {

            if ($(this).val() != '' && $(this).val() > 0) {

                typeOfTransport($(this).val(), 'transport_mode_id');

            }

        });

        $(".bu_activity").on('change', function() {

            let bu_activity = $(this).val();

            if (bu_activity != '') {

                //console.log(wa_activity, activityArr[wa_activity])

                $(".bu_uom").val(activityArr[bu_activity])

            }

        });



        function saveOrUpdate(pathName) {

            event.preventDefault();

            $('.error').html('');

            //function addNewUser(formId) {

            var bu_particulars = $.trim($(".bu_particulars").val());

            var bu_region = $.trim($(".bu_region").val());

            var bu_emission_factor_data = $.trim($('.bu_emission_factor_data').val());

            var bu_mode_of_transportation = $.trim($('.bu_mode_of_transportation').val());

            var bu_type_of_transportation = $.trim($(".bu_type_of_transportation").val());

            var bu_one_way_return = $.trim($(".bu_one_way_return").val());

            var bu_from = $.trim($(".bu_from").val());

            var bu_to = $.trim($(".bu_to").val());

            var bu_activity = $.trim($(".bu_activity").val());

            var bu_uom = $.trim($(".bu_uom").val());

            var bu_quantity = $.trim($(".bu_quantity").val());

            var isValid = true;

            if (bu_particulars == '' || typeof bu_particulars === 'undefined') {

                $('.bu_particularsError').html('Particulars Should Not Be Empty');

            }

            if (bu_region == '' || typeof bu_region === 'undefined') {

                $('.bu_regionError').html('Region Id Should Not Be Empty');

                isValid = false;

            }

            if (bu_emission_factor_data == '' || typeof bu_emission_factor_data === 'undefined') {

                $('.bu_emission_factor_dataError').html('EF Data Should Not Be Empty');

                isValid = false;

            }

            if (bu_mode_of_transportation == '') {

                $('.wa_treatment_typeError').html('Mode of Transportation Should Not Be Empty');

                isValid = false;

            }

            if (bu_type_of_transportation == '') {

                $('.bu_type_of_transportationError').html('Type of Transportation should Not Be Empty');

                isValid = false;

            }

            if (bu_activity == '') {

                $('.bu_activityError').html('Activity should Not Be Empty');

                isValid = false;

            }

            if (bu_uom == '') {

                $('.bu_uomError').html('UoM should Not Be Empty');

                isValid = false;

            }

            if (bu_quantity == '') {

                $('.bu_quantityError').html('Quantity should Not Be Empty');

                isValid = false;

            }

            if (pathName == 'saveBusinessTravel') {

                var urlPath = "{{ url('/saveBusinessTravel') }}";

                var bu_item_id = null;

            } else {

                var urlPath = "{{ url('/updateBusinessTravel') }}";

                //$('.purchase_good_service_item_Id').val()

                var bu_item_id = $('.bu_item_id').val();

            }

            // console.log(" dept Error isValid => "+isValid);

            if (isValid) {

                // console.log("trying to hit ajax call");

                var jsonData = {

                    "bu_particulars": bu_particulars,

                    "bu_region": bu_region,

                    "bu_emission_factor_data": bu_emission_factor_data,

                    "bu_mode_of_transportation": bu_mode_of_transportation,

                    "bu_type_of_transportation": bu_type_of_transportation,

                    "bu_one_way_return": bu_one_way_return,

                    "bu_from": bu_from,

                    "bu_to": bu_to,

                    "bu_activity": bu_activity,

                    "bu_uom": bu_uom,

                    "bu_quantity": bu_quantity,

                    "bu_item_id": bu_item_id,

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

                            $('#displayMsg').html('<span class="success">' + data.message +
                                '</span>');

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

            var url = "{{ URL::TO('ghgUserList') }}";

        } else if (activeBtn == 'questionnaireBtn') {

            //alert('test rana');

            $("#userSideBarDiv").hide();

            $("#dashBoarAccord").show();

            //var url = "{{ URL::TO('purchasedgoodsandservices') }}";

        } else if (activeBtn == 'reportBtn') {

            $("#userSideBarDiv").hide();

            $("#dashBoarAccord").hide();

            $("#reportsPage").show();

            var url = "{{ URL::TO('ghgReportData') }}";

        } else if (activeBtn == 'settingsBtn') {

            $("#userSideBarDiv").hide();

            $("#dashBoarAccord").hide();

            var loginUserId = $('#loginUserId').val();

            if (loginUserId == '' || typeof loginUserId === 'undefined') {

                bootbox.alert("Invalid User Found!");

            }

            var url = "{{ URL::TO('/ghgUserEditScreen') }}/" + loginUserId;

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

            var url = "{{ URL::TO('ghgUserList') }}";

        } else if (activeSubBtn == 'addNewUsersBtn') {

            var url = "{{ URL::TO('addGHGNewUser') }}";

        }

        if (url != '' && typeof url !== 'undefined') {

            $("#ghgDiv").load(url);

        }

    }



    function editView(randomId, purchase_id) {

        $.ajax({

            url: "{{ url('/editBusinessTravel') }}",

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

                    $(".mainTitle").text('5. Edit Business Travel');

                    $(".bu_particulars").val(data.result.bu_particulars);

                    $(".bu_region").val(data.result.bu_region);

                    $(".bu_emission_factor_data").val(data.result.bu_emission_factor_data);

                    $(".bu_mode_of_transportation").val(data.result.bu_mode_of_transportation);

                    typeOfTransport(data.result.bu_type_of_transportation, 'id');

                    //var options = '<option value="'+data.result.bu_type_of_transportation+'">'+data.result.bu_type_of_transportation+'</option>';

                    $(".bu_type_of_transportation").val(data.result.bu_type_of_transportation);

                    $(".bu_one_way_return").val(data.result.bu_one_way_return);

                    $(".bu_from").val(data.result.bu_from);

                    $(".bu_to").val(data.result.bu_to);

                    $(".bu_activity").val(data.result.bu_activity);

                    $(".bu_quantity").val(data.result.bu_quantity);

                    $(".bu_uom").val(data.result.bu_uom);

                    $(".bu_item_id").val(data.result.encrptid);

                    $(".purchase_good_service_save").addClass('hide');

                    $(".purchase_good_service_update").removeClass('hide');

                } else {

                    console.log('testing555')

                    // bootbox.alert(data.message);

                    $('.displayMsg').html('');

                    $('.displayMsg').show();

                    $('.displayMsg').html(
                        '<span class="error">Unable to Edit Selected Record. Contact Admin.</span>');

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

                        options += '<option value="' + list.id + '">' + list.transport_type +
                            '</option>';

                    });

                    $("#bu_type_of_transportation").html(options);

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
