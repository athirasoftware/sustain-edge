@include('includes.header')

<?php

use App\Models\Company;

use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Auth;

$userCompany = Company::find(Auth::user()->company_id);

$loginUserId = Crypt::encrypt(Auth::user()->id);

?>

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/scopethreestyle.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/scopethree.js') }}"></script>
    
</head>

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



                                            <div class="cmnBx">

                                                <div class="mainTitle">12.Downstream Leased Assets</div>

                                                <form action="javascript:void(0)">

                                                    <div class="row">

                                                        <div class="col-sm-4">

                                                            <label for="">Activity *</label>

                                                            <input type="text" class="form-control">



                                                        </div>


                                                        <div class="col-sm-4">
                                                            <label for="ef-data">EF Data *</label>
                                                            <input type="text" id="ef-data" class="form-control">
                                                        </div>



                                                        <div class="col-sm-4">

                                                            <label for="">Region *</label>

                                                            <select name="" id="" class="form-control">

                                                                <option value="">select</option>

                                                            </select>


                                                        </div>


                                                        
                                                        <div class="container">
                                                            <h4><b>Scope 1</b></h4>
                                                            <div class="panel panel-default">

                                                                <div class="panel-heading" id="panel-heading-1">Add Scope 1 Information
                                                                     <div class="item">
                                                                 
                                                                        
                                                       <button id="showItemsButton1" type="addmore" class="hoveranim add_more"><span>Add  More</span></button>
                                                        </div>
                                                         </div>
                                                                <div class="panel-body" id="itemList1"
                                                                    style="display: none">
                                                                    <form action="javascript:void(0)">

                                                                        <div class="row">

                                                                            <div class="col-sm-4">

                                                                                <label for="">Particulars
                                                                                    *</label>

                                                                                <select name="" id=""
                                                                                    class="form-control">

                                                                                    <option value="">Select Particulars</option>

                                                                                </select>



                                                                            </div>


                                                                            <div class="col-sm-4">
                                                                                <label for="ef-data">Unit Of
                                                                                    Measurement *</label>
                                                                                <select name="" id=""
                                                                                    class="form-control">

                                                                                    <option value="">select
                                                                                    </option>

                                                                                </select>
                                                                            </div>



                                                                            <div class="col-sm-4">

                                                                                <label for="">Quantity(Actual)
                                                                                    *</label>

                                                                                <select name="" id=""
                                                                                    class="form-control">

                                                                                    <option value="">select
                                                                                    </option>

                                                                                </select>



                                                                            </div>
                                                                        </div> 
                                                                        

                                                                        <script>
                                                                             $(document).ready(function() {
                                                                            // Initially hide the itemList1
                                                                            $('#itemList1').hide();
                                                                        
                                                                            // Show itemList1 when the panel-heading-1 is clicked
                                                                            $('#panel-heading-1').click(function() {
                                                                                $('#itemList1').show();
                                                                            });
                                                                            });
                                                                            const showItemsButton1 = document.getElementById('showItemsButton1');
                                                                            const itemList1 = document.getElementById('itemList1');
                                                                            
                                                                            showItemsButton1.addEventListener('click', () => {
                                                                                // Create a new row
                                                                                let newRow = document.createElement('div');
                                                                                newRow.innerHTML = `
                                                                                <hr />
                                                                                <div class="row">
                                                                                    <div class="col-sm-4">
                                                                                        
                                                                                        <label for="">Particulars*</label>
                                                                                        <select name="" id="" class="form-control">
                                                                                            <option value="">Select Particulars</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <label for="ef-data">Unit Of Measurement *</label>
                                                                                        <select name="" id="" class="form-control">
                                                                                            <option value="">select</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <label for="">Quantity(Actual)*</label>
                                                                                        <select name="" id="" class="form-control">
                                                                                            <option value="">select</option>
                                                                                        </select>
                                                                                    </div>
                                                                                   
                                                                                </div>
                                                                                 `;
                                                                        
                                                                                // Append the new row to the list
                                                                                itemList1.appendChild(newRow);
                                                                            
                                                                                // Show the list
                                                                                itemList1.style.display = 'block';
                                                                            });
                                                                            </script>
                                                                            
                                                                            

                                                                </div>

                                                            </div>


                                                        </div>
                                                    </div>
                                                    
{{-- scope 2 --}}

 <h4><b>Scope 2</b></h4>
                                                            <div class="panel panel-default">

                                                                <div id="panel-heading-2" class="panel-heading">Add Scope 2 Information
                                                                     <div class="item">
                                                                 
                                                                        
<button id="showItemsButton2" type="addmore" class="hoveranim add_more"><span>Add  More</span></button>
                                                        </div>
</div>
                                                                <div class="panel-body" id="itemList2"
                                                                    style="display: none">
                                                                    <form action="javascript:void(0)">

                                                                        <div class="row">

                                                                            <div class="col-sm-4">

                                                                                <label for="">Particulars
                                                                                    *</label>

                                                                                <select name="" id=""
                                                                                    class="form-control">

                                                                                    <option value="">Select Particulars</option>

                                                                                </select>



                                                                            </div>


                                                                            <div class="col-sm-4">
                                                                                <label for="ef-data">Unit Of
                                                                                    Measurement *</label>
                                                                                <select name="" id=""
                                                                                    class="form-control">

                                                                                    <option value="">select
                                                                                    </option>

                                                                                </select>
                                                                            </div>



                                                                            <div class="col-sm-4">

                                                                                <label for="">Quantity(Actual)
                                                                                    *</label>

                                                                                <select name="" id=""
                                                                                    class="form-control">

                                                                                    <option value="">select
                                                                                    </option>

                                                                                </select>



                                                                            </div>
                                                                        </div>
                                                                       



                                                                        <script>
                                                                            $(document).ready(function() {
                                                                           // Initially hide the itemList2
                                                                           $('#itemList2').hide();
                                                                       
                                                                           // Show itemList2 when the panel-heading-2 is clicked
                                                                           $('#panel-heading-2').click(function() {
                                                                               $('#itemList2').show();
                                                                           });
                                                                           });
                                                                           const showItemsButton2 = document.getElementById('showItemsButton2');
                                                                           const itemList2 = document.getElementById('itemList2');
                                                                           
                                                                           showItemsButton2.addEventListener('click', () => {
                                                                               // Create a new row
                                                                               let newRow = document.createElement('div');
                                                                               newRow.innerHTML = `
                                                                               <hr />
                                                                               <div class="row">
                                                                                   <div class="col-sm-4">
                                                                                       
                                                                                       <label for="">Particulars*</label>
                                                                                       <select name="" id="" class="form-control">
                                                                                           <option value="">Select Particulars</option>
                                                                                       </select>
                                                                                   </div>
                                                                                   <div class="col-sm-4">
                                                                                       <label for="ef-data">Unit Of Measurement *</label>
                                                                                       <select name="" id="" class="form-control">
                                                                                           <option value="">select</option>
                                                                                       </select>
                                                                                   </div>
                                                                                   <div class="col-sm-4">
                                                                                       <label for="">Quantity(Actual)*</label>
                                                                                       <select name="" id="" class="form-control">
                                                                                           <option value="">select</option>
                                                                                       </select>
                                                                                   </div>
                                                                                  
                                                                               </div>
                                                                                `;
                                                                       
                                                                               // Append the new row to the list
                                                                               itemList2.appendChild(newRow);
                                                                           
                                                                               // Show the list
                                                                               itemList2.style.display = 'block';
                                                                           });
                                                                           </script>

                                                                </div>

                                                            </div>

                                                            <h4><b>Scope 3</b></h4>
                                                            <div class="panel panel-default">

                                                                <div id="panel-heading-3" class="panel-heading">Add Scope 3 Information
                                                                     <div class="item">
                                                                 
                                                                        
<button id="showItemsButton3" type="addmore" class="hoveranim add_more"><span>Add  More</span></button>
</div>
</div>
                                                                <div class="panel-body" id="itemList3"
                                                                    style="display: none">
                                                                    <form action="javascript:void(0)">

                                                                        <div class="row">

                                                                            <div class="col-sm-4">

                                                                                <label for="">Particulars
                                                                                    *</label>

                                                                                <select name="" id=""
                                                                                    class="form-control">

                                                                                    <option value="">Select Particulars</option>

                                                                                </select>



                                                                            </div>


                                                                            <div class="col-sm-4">
                                                                                <label for="ef-data">Unit Of
                                                                                    Measurement *</label>
                                                                                <select name="" id=""
                                                                                    class="form-control">

                                                                                    <option value="">select
                                                                                    </option>

                                                                                </select>
                                                                            </div>



                                                                            <div class="col-sm-4">

                                                                                <label for="">Quantity(Actual)
                                                                                    *</label>

                                                                                <select name="" id=""
                                                                                    class="form-control">

                                                                                    <option value="">select
                                                                                    </option>

                                                                                </select>



                                                                            </div>
                                                                        </div>
                                                                       


                                                                        <script>
                                                                            $(document).ready(function() {
                                                                           // Initially hide the itemList3
                                                                           $('#itemList3').hide();
                                                                       
                                                                           // Show itemList3 when the panel-heading-3 is clicked
                                                                           $('#panel-heading-3').click(function() {
                                                                               $('#itemList3').show();
                                                                           });
                                                                           });
                                                                           const showItemsButton3 = document.getElementById('showItemsButton3');
                                                                           const itemList3 = document.getElementById('itemList3');
                                                                           
                                                                           showItemsButton3.addEventListener('click', () => {
                                                                               // Create a new row
                                                                               let newRow = document.createElement('div');
                                                                               newRow.innerHTML = `
                                                                               <hr />
                                                                               <div class="row">
                                                                                   <div class="col-sm-4">
                                                                                       
                                                                                       <label for="">Particulars*</label>
                                                                                       <select name="" id="" class="form-control">
                                                                                           <option value="">Select Particulars</option>
                                                                                       </select>
                                                                                   </div>
                                                                                   <div class="col-sm-4">
                                                                                       <label for="ef-data">Unit Of Measurement *</label>
                                                                                       <select name="" id="" class="form-control">
                                                                                           <option value="">select</option>
                                                                                       </select>
                                                                                   </div>
                                                                                   <div class="col-sm-4">
                                                                                       <label for="">Quantity(Actual)*</label>
                                                                                       <select name="" id="" class="form-control">
                                                                                           <option value="">select</option>
                                                                                       </select>
                                                                                   </div>
                                                                                  
                                                                               </div>
                                                                                `;
                                                                       
                                                                               // Append the new row to the list
                                                                               itemList3.appendChild(newRow);
                                                                           
                                                                               // Show the list
                                                                               itemList3.style.display = 'block';
                                                                           });
                                                                           </script>

                                                                </div>

                                                            </div>


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

            var supplier_vendor_gst = $.trim($('.supplier_vendor_gst').val());

            var quantity = $.trim($('.quantity').val());

            var uom = $.trim($(".uom").val());

            var isValid = true;

            if (purchase_good_service_item == '' || typeof purchase_good_service_item === 'undefined') {

                $('.purchase_good_service_itemError').html(
                    'Purchased Goods or Service Item Should Not Be Empty');

            }

            if (supplier_vendor == '' || typeof supplier_vendor === 'undefined') {

                $('.supplier_vendorError').html('Supplier/Vendor Should Not Be Empty');

                isValid = false;

            }

            if (supplier_vendor_gst == '' || typeof supplier_vendor_gst === 'undefined') {

                $('.supplier_vendor_gstError').html('Supplier/Vendor GST Should Not Be Empty');

                isValid = false;

            }

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

                        "supplier_vendor_gst": supplier_vendor_gst,

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

            var url = "{{ URL::TO('ghgUserList') }}";

        } else if (activeBtn == 'questionnaireBtn') {

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

                    $(".mainTitle").text('Edit Purchased Goods & Services');

                    $(".purchase_good_service_item").val(data.purchase_item);

                    $(".supplier_vendor").val(data.pur_suplier_info);

                    $(".supplier_vendor_gst").val(data.pur_suplier_gst);

                    $(".quantity").val(data.pur_quantity);

                    $(".uom").val(data.pur_uom);

                    $(".purchase_good_service_item_Id").val(data.id);

                    $(".purchase_good_service_save").addClass('purchase_good_service_save_update')
                        .removeClass('purchase_good_service_save');

                    $(".purchase_good_service_save_update span").text('Update');

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
