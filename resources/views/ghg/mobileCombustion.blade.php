{{-- start scope 1 refrigerants --}}
<div class="mainTitle">
    2.Mobile Combustion
</div>
<form action="javascript:void(0)">
    <div class="row">
        <div class="col-lg-6">
            <label for="">Particulars *</label>
            <select name="mobileParticular" id="mobileParticular" class="form-control">
                <option value="">Select Particulars</option>
                @if(isset($mobileParticulars) && count($mobileParticulars) > 0)
                @foreach($mobileParticulars as $key => $mobileParticular)
                @if(isset($mobileParticular) && $mobileParticular != '')
                <option value="{{$mobileParticular->id}}+{{$mobileParticular['particulars']}}+{{$mobileParticular['quantity_type']}}">{{ $mobileParticular['particulars'] }}</option>

                @endif
                @endforeach
                @endif
            </select>
            <div class="label">select the fuel to add them below</div>
        </div>
        <input type="hidden" name="particularId" id="particularId" value='' />
        <div class="col-lg-6 mt-4 mobilefloatLeft">
            <a href="javascript:void(0);" class="mobileaddNewForm" title="Add Form" style="text-decoration:none">ADD NEW</i></a>
        </div>
    </div>
</form>
<div>
    <div class="col-lg-12 displayMsg" id="displayMsg"></div>
    <div class="cmnBx mt-4" id="mobileformDiv" style="display:none">
        <div class="row cntr">
            <div class="col-lg-4">
                <div class="txtT" id="selectedFuelCategory">Selected Fuel: <span id="selectedFuelCategoryText" class="selectedFuelCategoryText"> Biodiesel</span>
                </div>
                <span class="error" id="selectedFuelError"> </span>
            </div>

            <!-- <div class="col-lg-4">
                <label for="">Fuel type*</label>
                <select name="fuelType" id="fuelType" class="form-control fuelType">
                    <option value="">Select Fuel</option>
                    @if(isset($fuelTypes) && count($fuelTypes) > 0) 
                        @foreach($fuelTypes as $id => $fuelType)
                            @if(isset($fuelType) && $fuelType != '')
                                <option value="{{$id}}">{{$fuelType}}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
                <span class="error" id="fuelTypeError"> </span>
            </div> -->
            <div class="col-lg-4">
                <input type="hidden" name="mobilefuelUOM" id="mobilefuelUOM" value="" />
                <label for="">Region*</label>
                <select name="region" id="region" class="form-control ">
                    <option value="">Select Region</option>
                    <option value="India">India</option>
                    <option value="US">US</option>
                    <option value="Europe">Europe</option>
                </select>
                <span class="error" id="regionError"> </span>
            </div>
            <div class="col-lg-4">
                <label for="">Unit of measurement*</label>
                <select name="unitOfMesurement" id="unitOfMesurement" class="form-control mobileRegion">
                    <option value="">Select Unit of measurement</option>
                    <!-- <option value="17">Litre</option>
                    <option value="22">Gallon</option> -->

                </select>
                <span class="error" id="unitOfMesurementError"> </span>
            </div>
            <div class="col-lg-4">
                <label for="">Quantity (Actual)*</label>
                <input type="text" name="quantityActual" id="quantityActual" class="form-control">
                <span class="error" id="quantityActualError"> </span>
            </div>
            <!-- <div class="col-lg-4">
                <label for="">Start Date*</label>
                <div class='input-group date startDate' id="startDatetimepicker">
                    <input type='text' name="startDate" id="startDate" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <span class="error" id="startDateError"> </span>
            </div> -->
            <div class="col-lg-12">
                <!-- <div class="col-lg-4">
                    <label for="">End Date*</label>
                    <div class='input-group date endDate' id="endDatetimepicker">
                        <input type='text' name="endDate" id="endDate" class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <span class="error" id="endDateError"> </span>
                </div> -->
                <div class="col-lg-8"></div>
            </div>
            <div class="col-lg-6">
                <div class="rtsertBxc">
                    <div class="item">
                        <a href="javascript:void(0)" class="cmnbtn hoveranim removeForm" id="removeForm"><span>Cancel</span> <span class="randomNum" style="display:none"></span></a>
                    </div>
                    <div class="item">
                        <a href="javascript:void(0)" class="cmnbtn hoveranim mobilestationarySave" id="mobilestationarySave"><span>Save</span><span class="randomNum" style="display:none"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="item-list" id="mobileduplicateContent">
    @if(isset($existingMobileParticulars) && count($existingMobileParticulars) > 0)
    @foreach($existingMobileParticulars as $key => $existingMobileParticular)

    <?php $randId = rand(100, 100000); ?>
    <div class="item">
        <div class="itemBx">
            <div class="left">
                <div class="txtT"><b> Fuel:</b> <span id="editStationaryParticularText_{{$randId}}">{{$existingMobileParticular->fuel_particular}}</span>

                    <label style="margin-left:15px;"> Total Emission:</label> {{$existingMobileParticular->total_emission}} <span> {{$existingMobileParticular->standard}}</span>

                </div>

            </div>
            <div class="rtsertBxc" id="selectedView_{{$randId}}">
                <div class="item">
                    <button onclick="editmobileView('{{$randId}}', '{{Crypt::encrypt($existingMobileParticular->id)}}');" class="cmnbtn hoveranim"><span>Edit</span></button>
                </div>
                <div class="item">
                    <button onclick="deleteMobileCombustion('{{Crypt::encrypt($existingMobileParticular->id)}}');" class="cmnbtn hoveranim"><span>Remove</span></button>
                </div>
            </div>
            <div id="selectedViewEdit_{{$randId}}" style="display:none">

            </div>
        </div>
    </div>
    @endforeach
    @endif


</div>
{{-- end scope 1 stationary combustion --}}
<script type="text/javascript">
    $(document).ready(function() {
        var mobileParticularVal = $('#mobileParticular').val();

        if (mobileParticularVal != '' && typeof mobileParticularVal !== 'undefined') {
            alert("jj");
            $('.mobilefloatLeft').show();
            $('.selectedFuelCategoryText').html(mobileParticularVal);
        } else {
            $('.mobilefloatLeft').hide();
            $('.selectedFuelCategoryText').html('');
        }

        $('#mobileParticular').on('change', function() {
            mobilecheckForAddBtn();
        });

        var original = document.getElementById('mobileformDiv');
        $('.mobileaddNewForm').click(function() {
            mobileduplicateDivContent(original);
        });
        var isClicked = false;
        $(".container").one('click', '.mobilestationarySave', function(e) {
            e.stopPropagation();
            var btnRandomNum = $(this).find('.randomNum').text();
            $('.mobilestationarySave').hide();
            mobilesaveFormCombustionForm(btnRandomNum);
            setTimeout(() => {
                $('.mobilestationarySave').show();
            }, 5000);
        });

        // Once remove button is clicked
        $(".container").on('click', '.removeForm', function() {
            var closeRandomNum = $(this).find('.randomNum').text();
            var parentDiv = $('#mobileformDiv_' + closeRandomNum);
            // console.log("closeRandomNum => "+closeRandomNum);
            parentDiv.remove();
        });


        $(".container").on('click', '.mobileRegion', function() {
            var stationaryParticularVal = $('#mobilefuelUOM').val();
            console.log(stationaryParticularVal);


            var fuelTypeId = $(this).attr('id');
            var fuelTypeVal = stationaryParticularVal;
            // console.log(" fuelTypeId => "+fuelTypeId+" fuelTypeVal => "+fuelTypeVal);
            var randomId = '';
            var nameIdArray = fuelTypeId.split("_");
            if (nameIdArray[1] != '' && typeof nameIdArray[1] !== 'undefined') {
                randomId = nameIdArray[1];
            }

            // console.log(" fuelTypeId => "+fuelTypeId+" fuelTypeVal => "+fuelTypeVal);
            $.ajax({
                url: "{{ url('/getunitofmeasument') }}",
                method: 'post',
                dataType: "JSON",
                data: {
                    "selectedId": fuelTypeVal,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    // alert("hi");
                    console.log(data);
                    var $el = $("#unitOfMesurement_" + randomId);
                    var items = "";

                    $.each(data, function(index, item) {
                        items += "<option value=" + item.id + " >" + item.unit_of_measurement + "</option>";
                    });
                    var length = $el.find("option").length;
                    if (length == 1) {
                        //console.log(items);
                        $el.append(items);
                    }

                }
            });

        });



        $(".container").on('click', '.fuelType', function() {
            var fuelTypeId = $(this).attr('id');
            var fuelTypeVal = $(this).find("option:selected").text();
            // console.log(" fuelTypeId => "+fuelTypeId+" fuelTypeVal => "+fuelTypeVal);
            var randomId = '';
            var nameIdArray = fuelTypeId.split("_");
            if (nameIdArray[1] != '' && typeof nameIdArray[1] !== 'undefined') {
                randomId = nameIdArray[1];
            }
            var unitOfMeasurementsObj = <?php echo json_encode($unitOfMeasurements); ?>;
            // console.log(unitOfMeasurementsObj);
            var unitOfMeasurementsSubObj = unitOfMeasurementsObj[fuelTypeVal];
            // console.log(unitOfMeasurementsSubObj);

            var $el = $("#unitOfMesurement_" + randomId);
            $("#unitOfMesurement_" + randomId + " option:gt(0)").remove();
            // $el.empty(); // remove old options
            $.each(unitOfMeasurementsSubObj, function(key, value) {
                $el.append($("<option></option>")
                    .attr("value", key).text(value));
            });
            $("#startDatetimepicker_" + randomId).datepicker();
            $("#endDatetimepicker_" + randomId).datepicker();

        });

    });

    function editmobileView(randomId, combustionId) {

        var url = "{{URL::TO('editmobileFuel')}}";
        if (url != '' && typeof url !== 'undefined') {
            $('#selectedView_' + randomId).hide();
            $('#selectedViewEdit_' + randomId).show();
            url = url + "?randomId=" + randomId + "&combustionId=" + combustionId;
            $('#selectedViewEdit_' + randomId).load(url);
        } else {
            $('#selectedView_' + randomId).show();
            $('#selectedViewEdit_' + randomId).hide();
        }
    }
</script>