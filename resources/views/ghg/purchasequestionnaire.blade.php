<div class="accordion" id="dashBoarAccord1">



    <div id="dashBoarAccord2" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#dashBoarAccord1">

        <div class="accordion-body ">

            <div class="stationaryBox cmnBx" id="stationaryCombustionDiv">

                @include('ghg.purchasestationaryCombustion')

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

        var selectedFuel = $("#stationaryParticular option:selected").text();

        $('.selectedFuelCategoryText').html(selectedFuel);



        // console.log("fuelType => "+fuelType);

        var region = $('#region_' + randomNum).val();

        var unitOfMesurement = $('#unitOfMesurement_' + randomNum).val();

        var unitOfMesurementText = $("#unitOfMesurement_" + randomNum + " option:selected").text();

        var quantityActual = $('#quantityActual_' + randomNum).val();



        if (region != '' && unitOfMesurement != '' && quantityActual != '') {



            $.ajax({

                url: "{{ url('/savePurchaseofElectricity') }}",

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

                        setTimeout(() => {

                            $('.displayMsg').html('');

                            $('.displayMsg').hide();

                            $('#stationaryCombustionDiv').load("{{ url('/getRefreshPurchaseofElectricity')}}");

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

            url: "{{ url('/deletePurchaseofElectricity') }}",

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

                        $('#stationaryCombustionDiv').load("{{ url('/getRefreshPurchaseofElectricity')}}");

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

                url: "{{ url('/updatePurchaseofElectricity') }}",

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

                        $('#updateMsg_' + randomNum).html('<span class="success">' + data.message + '</span>');

                        setTimeout(() => {

                            $('#updateMsg_' + randomNum).html('');

                            $('#updateMsg_' + randomNum).hide();

                            $('#stationaryCombustionDiv').load("{{ url('/getRefreshPurchaseofElectricity')}}");

                        }, 2000);





                    } else if (data.status == 'error') {

                        // bootbox.alert(data.message);

                        $('#updateMsg_' + randomNum).html('');

                        $('#updateMsg_' + randomNum).show();

                        $('#updateMsg_' + randomNum).html('<span class="error">' + data.message + '</span>');

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