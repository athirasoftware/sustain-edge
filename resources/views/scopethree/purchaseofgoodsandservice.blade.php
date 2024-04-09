@include('includes.header')
<?php

use App\Models\Company;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

$userCompany =  Company::find(Auth::user()->company_id);
$loginpurchase_good_service_item =  Crypt::encrypt(Auth::user()->id);
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
                        {{ Form::hidden('encryptedpurchase_good_service_item', (isset(Auth::user()->id) && Auth::user()->id != '')?Crypt::encrypt(Auth::user()->id):'', [ 'id' => 'encryptedpurchase_good_service_item']) }}
                        <div id="questionariesDiv">
                            <div class="sideBox">
                                @include('includes.sideBar')
                            </div>
                        </div>
                    </div>
                    <div class="rtsec">
                        <div class="topBox">
                            @include('includes.ghgSubHead')
                            <div id="ghgDiv">
                            </div>
                            <div class="accordion" id="dashBoarAccord">
                                <div class="accordion-item">
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
                                                                    <b>Purchased Item:</b> <span id="editStationaryParticularText_{{$randId}}">{{ $savedPurchasedItem['purchase_item'] }}</span>
                                                                    <span class="px-2" id="editStationaryParticularText_{{$randId}}">({{ $savedPurchasedItem['pur_quantity'] }} {{$savedPurchasedItem['pur_uom']}})</span>
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
                                                <div class="mainTitle">1. Purchased Goods & Services</div>
                                                <form action="javascript:void(0)" id="purchase_good_service_form">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="">Purchased Goods & Services *</label>
                                                            <input type="text" class="form-control purchase_good_service_item" name="purchase_good_service_item">
                                                            <span class="error purchase_good_service_itemError"> </span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">Supplier/Vendor *</label>
                                                            <input type="text" class="form-control supplier_vendor" name="supplier_vendor">
                                                            <span class="error supplier_vendorError"> </span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">Specific Identity</label>
                                                            <input type="text" class="form-control supplier_vendor_info" name="supplier_vendor_info">
                                                            <span class="error supplier_vendor_infoError"> </span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">Quantity *</label>
                                                            <input type="text" class="form-control quantity" name="quantity">
                                                            <input type="hidden" class="form-control purchase_good_service_item_Id" name="purchase_good_service_item_Id">
                                                            <span class="error quantityError"> </span>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">Unit of Measurement *</label>
                                                            <select name="uom" id="uom" class="form-control uom">
                                                                <option value="">select</option>
                                                                <option value="Kg">Kg</option>
                                                                <option value="Tonnes">Tonnes</option>
                                                            </select>
                                                            <span class="error uomError"> </span>
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
        </div>
    </section>

</div>
@include('includes.footer')
<script>
    $(document).ready(function() {
        $(".purchase_good_service_cancel").on('click', function() {
            $("#purchase_good_service_form")[0].reset();
        })
        $(".purchase_good_service_update").on('click', function() {
            saveOrUpdate('updatePurchaseGoods');
        });
        $(".purchase_good_service_save").on('click', function() {
            saveOrUpdate('savePurchaseGoods');
        });

        function saveOrUpdate(pathName) {
            event.preventDefault();
            $('.error').html('');
            //function addNewUser(formId) {
            var purchase_good_service_item = $.trim($(".purchase_good_service_item").val());
            var supplier_vendor = $.trim($(".supplier_vendor").val());
            var supplier_vendor_info = $.trim($('.supplier_vendor_info').val());
            var quantity = $.trim($('.quantity').val());
            var uom = $.trim($(".uom").val());
            var isValid = true;
            if (purchase_good_service_item == '' || typeof purchase_good_service_item === 'undefined') {
                $('.purchase_good_service_itemError').html('Purchased Goods or Service Item Should Not Be Empty');
            }
            if (supplier_vendor == '' || typeof supplier_vendor === 'undefined') {
                $('.supplier_vendorError').html('Supplier/Vendor Should Not Be Empty');
                isValid = false;
            }
            // if (supplier_vendor_info == '' || typeof supplier_vendor_info === 'undefined') {
            //     $('.supplier_vendor_infoError').html('Supplier/Vendor GST Should Not Be Empty');
            //     isValid = false;
            // }
            if (quantity == '') {
                $('.quantityError').html('Please Enter Valid Quantity');
                isValid = false;
            }
            if (uom == '') {
                $('.uomError').html('UoM should Not Be Empty');
                isValid = false;
            }
            if (pathName == 'savePurchaseGoods') {
                var urlPath = "{{ url('/savePurchaseGoods') }}";
                var purchase_good_service_item_Id = null;
            } else {
                var urlPath = "{{ url('/updatePurchaseGoods') }}";
                var purchase_good_service_item_Id = $('.purchase_good_service_item_Id').val();
            }
            // console.log(" dept Error isValid => "+isValid);
            if (isValid) {
                // console.log("trying to hit ajax call");
                $.ajax({
                    url: urlPath,
                    method: 'post',
                    dataType: "JSON",
                    data: {
                        "purchase_good_service_item_Id": purchase_good_service_item_Id,
                        "purchase_good_service_item": purchase_good_service_item,
                        "supplier_vendor": supplier_vendor,
                        "supplier_vendor_info": supplier_vendor_info,
                        "quantity": quantity,
                        "uom": uom,
                        "_token": "{{ csrf_token() }}",
                    },
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
            url: "{{ url('/editPurchaseGoods') }}",
            method: 'post',
            dataType: "JSON",
            data: {
                "purchase_id": purchase_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                if (data != '') {
                    console.log('testing007')
                    $('.displayMsg').html('');
                    $('.displayMsg').show();
                    $('.displayMsg').html('<span class="success">Please Updated Selected Data!</span>');
                    $(".mainTitle").text('1. Edit Purchased Goods & Services');
                    $(".purchase_good_service_item").val(data.result.purchase_item);
                    $(".supplier_vendor").val(data.result.pur_suplier_info);
                    $(".supplier_vendor_info").val(data.result.pur_suplier_gst);
                    $(".quantity").val(data.result.pur_quantity);
                    $(".uom").val(data.result.pur_uom);
                    $(".purchase_good_service_item_Id").val(data.result.encrptid);
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
            url: "{{ url('/deletePurchaseGoods') }}",
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