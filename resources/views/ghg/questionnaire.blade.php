<div id="dashBoarAccord">
    <div class="accordion-item">
        <!-- <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#dashBoarAccord1" aria-expanded="true"
                aria-controls="dashBoarAccord1">
                Questionnaire2
            </button>
        </h2> -->
        <div id="dashBoarAccord1">
            <div class="accordion-body ">
                <div class="stationaryBox cmnBx show" id="stationaryCombustionDiv">
                    @include('ghg.stationaryCombustion')
                </div>

            </div>
        </div>
        {{-- end scope 1 --}}
    </div>
</div>
<script type="text/javascript">
    var cntSubmit = 0;
    $(document).ready(function() {

        var stationaryParticularVal = $('#stationaryParticular').val();
        if (stationaryParticularVal != '' && typeof stationaryParticularVal !== 'undefined') {
            $('.floatLeft').show();
            $('.selectedFuelCategoryText').html(stationaryParticularVal);
        } else {
            $('.floatLeft').hide();
            $('.selectedFuelCategoryText').html('');
        }

        var activeBtn = 'coneStationary';
        setTimeout(() => {
            $('#' + activeBtn).trigger('click');
        }, 500);
        //console.log("scope one loaded");
        $('.scopeOne').on('click', function() {
            $(this).toggleClass('active');
            console.log($(this).attr('id'));
            if ($(this).attr('id') == 'coneStationary') {
                $('.stationaryBox').toggleClass('show');
            } else if ($(this).attr('id') == 'coneMobile') {
                $('.mobileBox').toggleClass('show');
            }
            if ($(this).attr('id') == 'coneRefrigerants') {
                $('.regrets').toggleClass('show');
            }
        });


    });

    function duplicateDivContent(original, sno) {
        if ($('.formDivClass').length > 1) {
            return false;
        }
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
        $('.randomId').val(randNum);
        $('#' + formDivId).find('.startDate').attr('id', 'startDatetimepicker_' + randNum);
        $('#' + formDivId).find('.endDate').attr('id', 'endDatetimepicker_' + randNum);
        $('#' + formDivId).find('.randomNum').text(randNum);
        $('#' + formDivId).find('.removeForm').show();
        $('#' + formDivId).show();
    }


    function checkForAddBtn() {
        console.log("inside add btn");
        var randomId = $(".randomId").val();
        var stationaryParticularVal = $('#stationaryParticular').val();
        console.log(stationaryParticularVal);
        var stationaryParticularValArray = stationaryParticularVal.split("+");
        uomList(stationaryParticularValArray[2], randomId);

        if (stationaryParticularValArray[0] != '' && typeof stationaryParticularValArray[0] !== 'undefined') {
            $('.floatLeft').show();
            $('#particularId').val(stationaryParticularValArray[0]);

            $('.selectedFuelCategoryText').html(stationaryParticularValArray[1]);
            var fuelTypeId = $('.fuelUOM').attr('id');
            // fueltype_uom(stationaryParticularValArray[2]);
        } else {
            $('.floatLeft').hide();
            $('.selectedFuelCategoryText').html('');
        }
    }

    function saveFormCombustionForm(randomNum) {
        $('.displayMsg').html('');
        var particularId = $('#particularId').val();
        // var selectedFuel = $('#stationaryParticular').val();
        var selectedFuel = $("#stationaryParticular option:selected").text();
        // var fuelType = $('#fuelType_'+randomNum).val();
        // var fuelType = $("#fuelType_"+randomNum+" option:selected").text();
        // console.log("fuelType => "+fuelType);
        var region = $('#region_' + randomNum).val();
        var unitOfMesurement = $('#unitOfMesurement_' + randomNum).val();
        var unitOfMesurementText = $("#unitOfMesurement_" + randomNum + " option:selected").text();
        var quantityActual = $('#quantityActual_' + randomNum).val();
        // var startDate = $('#startDate_'+randomNum).val();
        // var endDate = $('#endDate_'+randomNum).val();
        console.log(" saveFormCombustionForm -> ", {
            'randomNum': randomNum,
            'particularId': particularId,
            'selectedFuel': selectedFuel,
            'unitOfMesurement': unitOfMesurement,
            'cntSubmit': cntSubmit
        });

        if (selectedFuel != '' && region != '' && unitOfMesurement != '' && quantityActual != '') {
            cntSubmit++;
            console.log('cntSubmit', cntSubmit);
            $.ajax({
                url: "{{ url('/saveStationaryCombution') }}",
                method: 'post',
                dataType: "JSON",
                async: false,
                data: {
                    "randomNum": randomNum,
                    "particularId": particularId,
                    "selectedFuel": selectedFuel,
                    //"fuelType": fuelType,
                    "region": region,
                    "unitOfMesurement": unitOfMesurement,
                    "unitOfMesurementText": unitOfMesurementText,
                    "quantityActual": quantityActual,
                    // "startDate": startDate,
                    // "endDate": endDate,
                    "_token": "{{ csrf_token() }}",

                },
                success: function(data) {
                    console.log(data, ' ajax saveStationaryCombution');
                    if (data.status == 'success') {
                        // bootbox.alert(data.message);
                        $('.displayMsg').html('');
                        $('.displayMsg').show();
                        $('.displayMsg').html('<span class="success">' + data.message + '</span>');
                        setTimeout(() => {
                            $('.displayMsg').html('');
                            $('.displayMsg').hide();
                            $('#stationaryCombustionDiv').load(
                                "{{ url('/getRefreshStationaryCombution') }}");
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
                    } else {

                        setTimeout(() => {
                            $('.displayMsg').html('');
                            $('.displayMsg').hide();
                            $('.displayMsg').html(
                                '<span class="error">Something went wrong, please try again later.</span>'
                                );
                            $('#stationaryCombustionDiv').load(
                                "{{ url('/getRefreshStationaryCombution') }}");
                        }, 2000);
                    }
                }
            });
        } else {
            $('.displayMsg').html('');
            $('.displayMsg').show();
            $('.displayMsg').html('<span class="error">Please fill all inputs</span>');
        }
    }


    function deleteStationaryCombustion(selectedFuelId) {
        // console.log(selectedFuelId);
        $.ajax({
            url: "{{ url('/deleteStationaryCombution') }}",
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
                        //     "{{ url('/getRefreshStationaryCombution') }}");
                        location.reload();
                    }, 1000);
                } else if (data.status == 'error') {
                    // bootbox.alert(data.message);
                    $('.displayMsg').html('');
                    $('.displayMsg').show();
                    $('.displayMsg').html('<span class="error">' + data.message + '</span>');
                }
            }
        });

    }



    function updateFormCombustionForm(randomNum) {
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
                url: "{{ url('/updateCombustionInfo') }}",
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
                        setTimeout(() => {
                            $('#updateMsg_' + randomNum).html('');
                            $('#updateMsg_' + randomNum).hide();
                            $('#stationaryCombustionDiv').load(
                                "{{ url('/getRefreshStationaryCombution') }}");
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
