<div class="accordion " id="dashBoarAccord">

    <div id="dashBoarAccord2" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#dashBoarAccord1">
        <div class="accordion-body ">

            <div class="stationaryBox cmnBx" id="stationaryCombustionDiv">
                <div class="mainTitle">
                    1.Electricity of Evs
                </div>
                <div>
                    <div class="addformdiv cmnBx mt-4" id="formDiv" style="display:none;">

                        <input type="hidden" name="particularId" id="particularId" value='1' />
                        <input type="hidden" name="stationaryParticular" id="stationaryParticular" value='Electricity of Evs' />
                        <div class="row cntr">
                            <div class="col-lg-4">
                                <div class="txtT" id="selectedFuelCategory">Selected Fuel: <span id="selectedFuelCategoryText" class="selectedFuelCategoryText"> Biodiesel</span>
                                </div>
                                <span class="error" id="selectedFuelError"> </span>
                            </div>
                            <input name="fuelType" id="fuelType" class="form-control fuelType" hidden="hidden" value="Energy">


                            <div class="col-lg-4">
                                <label for="">Region*</label>
                                <select name="region" id="region" class="form-control">
                                    <option value="">Select Region</option>
                                    <option value="India">India</option>
                                    <option value="US">USA</option>
                                    <option value="Europe">Europe</option>
                                </select>
                                <span class="error" id="regionError"> </span>
                            </div>
                            <div class="col-lg-4">
                                <label for="">Unit of measurement*</label>

                                <select name="unitOfMesurement" id="unitOfMesurement" class="form-control">
                                    <option value="">Select Unit of measurement</option>
                                    @if (isset($fuelTypes) && count($fuelTypes) > 0)
                                    @foreach ($fuelTypes as $id => $fuelType)
                                    @if (isset($fuelType) && $fuelType != '')
                                    <option value="{{ $id }}">{{ $fuelType }}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                                <span class="error" id="unitOfMesurementError"> </span>
                            </div>
                            <div class="col-lg-4">
                                <label for="">Quantity *</label>
                                <input type="text" name="quantityActual" id="quantityActual" class="form-control">
                                <span class="error" id="quantityActualError"> </span>
                            </div>
                            <div class="col-lg-12">
                                <div class="col-lg-8"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="rtsertBxc">
                                    <div class="item">
                                        <a href="javascript:void(0)" class="cmnbtn hoveranim removeForm" id="removeForm"><span>Cancel</span> <span class="randomNum" style="display:none"></span></a>
                                    </div>
                                    <div class="item">
                                        <a href="javascript:void(0)" class="cmnbtn hoveranim stationarySave" id="stationarySave"><span>Save</span><span class="randomNum" style="display:none"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <br />
                <div class="">
                    <div class="col-lg-12 displayMsg" id="displayMsg"></div>
                    <div class="item-list" id="refrigerantsduplicateContent">
                        @if (isset($existingStationaryParticulars) && count($existingStationaryParticulars) > 0)
                        @foreach ($existingStationaryParticulars as $key => $existingStationaryParticular)
                        <?php $randId = rand(100, 100000); ?>
                        <div class="item">
                            <div class="itemBx">
                                <div class="left">
                                    <div class="txtT"><b>Fuel:</b> <span id="editStationaryParticularText_{{ $randId }}">Electricity of Evs</span>
                                        <span id="">
                                            <label style="margin-left:15px;"><b>Total Emission:</b></label>
                                            {{ $existingStationaryParticular->total_emission }}
                                            {{ $existingStationaryParticular->standard }}</span>

                                    </div>

                                </div>
                                <div class="rtsertBxc" id="selectedView_{{ $randId }}">
                                    <div class="item">
                                        <button onclick="editView('{{ $randId }}', '{{ Crypt::encrypt($existingStationaryParticular->id) }}');" class="cmnbtn hoveranim"><span>Edit</span></button>
                                    </div>
                                    <div class="item">
                                        <button onclick="deletepurchaseCombustion('{{ Crypt::encrypt($existingStationaryParticular->id) }}');" class="cmnbtn hoveranim"><span>Remove</span></button>
                                    </div>
                                </div>
                                <div id="selectedViewEdit_{{ $randId }}" style="display:none">

                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </div>
    {{-- end scope 1 --}}
</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        var stationaryParticularVal = $('#stationaryParticular').val();
        if (stationaryParticularVal != '' && typeof stationaryParticularVal !== 'undefined') {

            $('.floatLeft').show();
            $('.selectedFuelCategoryText').html(stationaryParticularVal);
            var selectedFuel = $("#stationaryParticular option:selected").text();
            $('#selectedFuelCategoryText').html(selectedFuel);
        } else {
            $('.floatLeft').hide();
            $('.selectedFuelCategoryText').html('');
        }

        var activeBtn2 = 'coneElectricity';
        setTimeout(() => {
            $('#' + activeBtn2).trigger('click');
        }, 500);
        //console.log("scope one loaded");


        $('.scopeTwo').on('click', function() {

            $(this).toggleClass('active');
            console.log($(this).attr('id'));
            if ($(this).attr('id') == 'coneElectricity') {
                $('.electricityBox').toggleClass('show');
            } else if ($(this).attr('id') == 'coneSteam') {
                $('.steamBox').toggleClass('show');
            }
        });


    });

    function duplicateDivContent(original, sno) {
        var randNum = Math.floor(Math.random() * 50000);
        var clone = original.cloneNode(true); // "deep" clone
        var formDivId = '';
        clone.id = clone.id + "_" + randNum;
        formDivId = clone.id;
        original.parentNode.appendChild(clone);
        $('#' + formDivId + ' input').each(function() {
            // results.push({ id: this.id, value: this.value  });
            // console.log(this.id+" <= id , name => "+this.name);
            $(this).attr('id', function(i, id) {
                return id + "_" + randNum
            })
            $(this).attr('name', function(i, name) {
                return name + "_" + randNum
            })
        });

        $('#' + formDivId + ' select').each(function() {
            $(this).attr('id', function(i, id) {
                return id + "_" + randNum
            })
            $(this).attr('name', function(i, name) {
                return name + "_" + randNum
            })
        });

        $('#' + formDivId + ' span').each(function() {
            $(this).attr('id', function(i, id) {
                return id + "_" + randNum
            })
        });

        $('#' + formDivId).find('.startDate').attr('id', 'startDatetimepicker_' + randNum);
        $('#' + formDivId).find('.endDate').attr('id', 'endDatetimepicker_' + randNum);
        $('#' + formDivId).find('.randomNum').text(randNum);
        $('#' + formDivId).find('.removeForm').show();
        $('#' + formDivId).show();
    }

    function checkPurchaseForAddBtn() {
        console.log("inside add purchase");
        var stationaryParticularVal = $('#stationaryParticular').val();
        console.log(stationaryParticularVal);
        var stationaryParticularValArray = stationaryParticularVal.split("+");

        if (stationaryParticularValArray[0] != '' && typeof stationaryParticularValArray[0] !== 'undefined') {
            $('.floatLeft').show();
            $('#particularId').val(stationaryParticularValArray[0]);
            $('.selectedFuelCategoryText').html(stationaryParticularValArray[1]);
        } else {
            $('.floatLeft').hide();
            $('.selectedFuelCategoryText').html('');
        }
    }

    function saveFormPurchaseForm(randomNum) {

        $('.displayMsg').html('');
        var particularId = $('#particularId').val();

        var selectedFuel = $('#stationaryParticular').val();
        var selectedFuel = "Electricity of Evs";
        $('.selectedFuelCategoryText').html(selectedFuel);

        // console.log("fuelType => "+fuelType);
        var region = $('#region_' + randomNum).val();
        var unitOfMesurement = $('#unitOfMesurement_' + randomNum).val();
        var unitOfMesurementText = $("#unitOfMesurement_" + randomNum + " option:selected").text();
        var quantityActual = $('#quantityActual_' + randomNum).val();

        if (region != '' && unitOfMesurement != '' && quantityActual != '') {

            $.ajax({
                url: "{{ url('/saveElectricityofEvs') }}",
                method: 'post',
                dataType: "JSON",
                data: {
                    "randomNum": randomNum,
                    "particularId": particularId,
                    "selectedFuel": selectedFuel,

                    "region": region,
                    "unitOfMesurement": unitOfMesurement,
                    "unitOfMesurementText": unitOfMesurementText,
                    "quantityActual": quantityActual,

                    "_token": "{{ csrf_token() }}",

                },
                success: function(data) {
                    // console.log(data);
                    if (data.status == 'success') {
                        // bootbox.alert(data.message);
                        $('.displayMsg').html('');
                        $('.displayMsg').show();
                        $('.displayMsg').html('<span class="success">' + data.message + '</span>');
                        location.reload();
                        setTimeout(() => {
                            $('.displayMsg').html('');
                            $('.displayMsg').hide();
                            // $('.addformdiv').hide();
                            // $('#stationaryCombustionDiv').load(
                            //     "{{ url('/getRefreshElectricityofEvs') }}");
                        }, 2000);


                    } else if (data.status == 'error') {
                        // bootbox.alert(data.message);
                        $('.displayMsg').html('');
                        $('.displayMsg').show();
                        $('.displayMsg').html('<span class="error">' + data.message + '</span>');
                        return false;
                    } else if (data.status == 'validation') {
                        // console.log("Error from validation");
                        // alert("Error from validation");
                        $.each(data.message, function(i, v) {
                            console.log(i);
                            $("#" + i + "Error_" + randomNum).html(v);
                        });
                        return false;
                    }
                }
            });
        } else {
            $('.displayMsg').html('');
            $('.displayMsg').show();
            $('.displayMsg').html('<span class="error"> Please fill all inputs</span>');
        }
    }

    function deletepurchaseCombustion(selectedFuelId) {
        // console.log(selectedFuelId);
        $.ajax({
            url: "{{ url('/deleteElectricityofEvs') }}",
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
                        $('.displayMsg').html('');
                        $('.displayMsg').hide();
                        // $('#stationaryCombustionDiv').load(
                        //     "{{ url('/getRefreshElectricityofEvs') }}");
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


    function updatepurchaseFormCombustionForm(randomNum) {
        $('.updateMsg').html('');
        var particularId = $('#particularId_' + randomNum).val();
        var selectedFuel = $("#editStationaryParticularText_" + randomNum).text();
        console.log("selectedFuel => " + selectedFuel);
        // var fuelType = $("#fuelType_"+randomNum+" option:selected").text();
        var combustionId = $('#combustionId_' + randomNum).val();
        var region = $('#region_' + randomNum).val();
        var unitOfMesurement = $('#unitOfMesurement_' + randomNum).val();
        var unitOfMesurementText = $("#unitOfMesurement_" + randomNum + " option:selected").text();
        var quantityActual = $('#quantityActual_' + randomNum).val();

        if (selectedFuel != '' && region != '' && unitOfMesurement != '' && quantityActual != '') {
            $.ajax({
                url: "{{ url('/updateElectricityofEvs') }}",
                method: 'post',
                dataType: "JSON",
                data: {
                    "randomNum": randomNum,
                    "combustionId": combustionId,
                    "particularId": particularId,
                    "selectedFuel": selectedFuel,

                    "region": region,
                    "unitOfMesurement": unitOfMesurement,
                    "unitOfMesurementText": unitOfMesurementText,
                    "quantityActual": quantityActual,

                    "_token": "{{ csrf_token() }}",

                },
                success: function(data) {
                    // console.log(data);
                    if (data.status == 'success') {
                        // bootbox.alert(data.message);
                        $('#updateMsg_' + randomNum).html('');
                        $('#updateMsg_' + randomNum).show();
                        $('#updateMsg_' + randomNum).html('<span class="success">' + data.message +
                            '</span>');
                        location.reload();
                        setTimeout(() => {
                            $('#updateMsg_' + randomNum).html('');
                            $('#updateMsg_' + randomNum).hide();
                            // $('#stationaryCombustionDiv').load(
                            //     "{{ url('/getRefreshElectricityofEvs') }}");
                        }, 2000);


                    } else if (data.status == 'error') {
                        // bootbox.alert(data.message);
                        $('#updateMsg_' + randomNum).html('');
                        $('#updateMsg_' + randomNum).show();
                        $('#updateMsg_' + randomNum).html('<span class="error">' + data.message +
                            '</span>');
                        return false;
                    } else if (data.status == 'validation') {
                        // console.log("Error from validation");
                        // alert("Error from validation");
                        $.each(data.message, function(i, v) {
                            console.log(i);
                            $("#" + i + "Error_" + randomNum).html(v);
                        });
                        return false;
                    }
                }
            });
        } else {
            $('.displayMsg').html('');
            $('.displayMsg').show();
            $('.displayMsg').html('<span class="error"> Please fill all inputs</span>');
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var stationaryParticularVal = $('#stationaryParticular').val();
        if (stationaryParticularVal != '' && typeof stationaryParticularVal !== 'undefined') {
            $('.floatLeft').show();
            $('.selectedFuelCategoryText').html(stationaryParticularVal);
        } else {
            $('.floatLeft').hide();
            $('.selectedFuelCategoryText').html('');
        }

        $('#stationaryParticular').on('change', function() {
            checkPurchaseForAddBtn();
        });

        var original = document.getElementById('formDiv');

        duplicateDivContent(original);

        var isClicked = false;
        $(".container").one('click', '.stationarySave', function(e) {
            e.stopPropagation();
            var btnRandomNum = $(this).find('.randomNum').text();
            $('.stationarySave').hide();
            saveFormPurchaseForm(btnRandomNum);
            setTimeout(() => {
                $('.stationarySave').show();
            }, 5000);
        });

        // Once remove button is clicked
        $(".container").on('click', '.removeForm', function() {
            var closeRandomNum = $(this).find('.randomNum').text();
            var parentDiv = $('#formDiv_' + closeRandomNum);
            // console.log("closeRandomNum => "+closeRandomNum);
            parentDiv.remove();
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

    function editView(randomId, combustionId) {
        var url = "{{ URL::TO('editElectricityofEvs') }}";
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