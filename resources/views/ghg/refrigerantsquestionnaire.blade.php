<div  id="dashBoarAccord">
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
               
                <div  class=" regrets cmnBx" id="refrigerantsCombustionDiv">
                    @include('ghg.refrigerants')
                </div> 
            </div>
        </div>
        {{-- end scope 1 --}}
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        var stationaryParticularVal = $('#stationaryParticular').val();
            if(stationaryParticularVal != '' && typeof stationaryParticularVal !== 'undefined') {
                $('.floatLeft').show();
                $('.selectedFuelCategoryText').html(stationaryParticularVal);
            } else {
                $('.floatLeft').hide();
                $('.selectedFuelCategoryText').html('');
            }
        
        var activeBtn = 'coneRefrigerants';
        setTimeout(() => {
            $('#'+activeBtn).trigger('click');
        }, 500);
        //console.log("scope one loaded");
        $('.scopeOne').on('click', function() {
            
            if($(this).attr('id') == 'coneRefrigerants') {
                $('.regrets').toggleClass('show');
            }
        });
        
        
    });
   
    
    function refrigerantsduplicateDivContent(original, sno) {
        var randNum = Math.floor(Math.random() * 50000);
        var clone = original.cloneNode(true); // "deep" clone
        var formDivId = '';
        clone.id = clone.id+"_" +randNum;
        formDivId = clone.id;
        original.parentNode.appendChild(clone);
        $('#'+formDivId+' input').each(function(){
            // results.push({ id: this.id, value: this.value  });
            // console.log(this.id+" <= id , name => "+this.name);
            $(this).attr('id', function(i , id) { return id + "_" +randNum })
            $(this).attr('name', function(i , name) { return name + "_" +randNum })
        });
       
        $('#'+formDivId+' select').each(function(){
            $(this).attr('id', function(i , id) { return id + "_" +randNum })
            $(this).attr('name', function(i , name) { return name + "_" +randNum })
        });

        $('#'+formDivId+' span').each(function(){
            $(this).attr('id', function(i , id) { return id + "_" +randNum })
        });

        $('#'+formDivId).find('.startDate').attr('id', 'startDatetimepicker_'+randNum);
        $('#'+formDivId).find('.endDate').attr('id', 'endDatetimepicker_'+randNum);
        $('#'+formDivId).find('.randomNum').text(randNum);
        $('#'+formDivId).find('.removeForm').show();
        $('#'+formDivId).show();
    }
    
    function refrigerantscheckForAddBtn() {
        console.log("inside add btn refrigerants");
        
        var stationaryParticularVal = $('#refrigerantsParticular').val();
        console.log(stationaryParticularVal);
        var stationaryParticularValArray = stationaryParticularVal.split("+");
        
        if(stationaryParticularValArray[0] != '' && typeof stationaryParticularValArray[0] !== 'undefined') {
            $('.refrigerantsfloatLeft').show();
            $('#particularId').val(stationaryParticularValArray[0]);
            $('.selectedFuelCategoryText').html(stationaryParticularValArray[1]);
            $('#refrigerantsfuelUOM').val(stationaryParticularValArray[2]);
           
        } else {
            $('.refrigerantsfloatLeft').hide();
            $('.selectedFuelCategoryText').html('');
        }
    }
   
   

    function refrigerantssaveFormCombustionForm(randomNum) {
        $('.displayMsg').html('');
        var particularId = $('#particularId').val();
        // var selectedFuel = $('#stationaryParticular').val();
        var selectedFuel = $( "#refrigerantsParticular option:selected" ).text();
        // var fuelType = $('#fuelType_'+randomNum).val();
       // var fuelType = $("#fuelType_"+randomNum+" option:selected").text();
        // console.log("fuelType => "+fuelType);
        var region = $('#region_'+randomNum).val();
        var unitOfMesurement = $('#unitOfMesurement_'+randomNum).val();
        var unitOfMesurementText = $("#unitOfMesurement_"+randomNum+" option:selected").text();
        var quantityActual = $('#quantityActual_'+randomNum).val();
        // var startDate = $('#startDate_'+randomNum).val();
        // var endDate = $('#endDate_'+randomNum).val();
        if(selectedFuel != ''  && region != '' && unitOfMesurement != '' && quantityActual != '') {
            $.ajax({
                url: "{{ url('/refrigerantssaveStationaryCombution') }}",
                method: 'post',
                dataType:"JSON",
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
                    // console.log(data);
                    if(data.status == 'success') {
                        // bootbox.alert(data.message);
                        $('.displayMsg').html('');
                        $('.displayMsg').show();
                        $('.displayMsg').html('<span class="success">'+data.message+'</span>');
                        setTimeout(() => {
                            $('.displayMsg').html('');
                            $('.displayMsg').hide();
                            $('#refrigerantsCombustionDiv').load("{{ url('/getRefreshRefrigerantsCombution')}}");
                        }, 2000);


                    } else if(data.status == 'error') {
                        // bootbox.alert(data.message);
                        $('.displayMsg').html('');
                        $('.displayMsg').show();
                        $('.displayMsg').html('<span class="error">'+data.message+'</span>');
                        return false;
                    } else if(data.status == 'validation') {
                        // console.log("Error from validation");
                        // alert("Error from validation");
                        $.each(data.message, function(i, v) {
                            console.log(i);
                            $("#"+i+"Error_"+randomNum).html(v);
                        });
                        return false;
                    }
                }
            });
        }  else {
            $('.displayMsg').html('');
            $('.displayMsg').show();
            $('.displayMsg').html('<span class="error"> Please fill all inputs</span>');
        }
    }


   
    function deleterefrigerantsCombustion(selectedFuelId) {
        // console.log(selectedFuelId);
            $.ajax({
                url: "{{ url('/deleteRefrigerantsCombution') }}",
                method: 'post',
                dataType:"JSON",
                data: {
                    "selectedId": selectedFuelId,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    // console.log(data);
                    if(data.status == 'success') {
                        // bootbox.alert(data.message);
                        $('.displayMsg').html('');
                        $('.displayMsg').show();
                        $('.displayMsg').html('<span class="success">'+data.message+'</span>');
                        setTimeout(() => {
                            $('.displayMsg').html('');
                            $('.displayMsg').hide();
                            $('#refrigerantsCombustionDiv').load("{{ url('/getRefreshRefrigerantsCombution')}}");
                        }, 2000);
                    } else if(data.status == 'error') {
                        // bootbox.alert(data.message);
                        $('.displayMsg').html('');
                        $('.displayMsg').show();
                        $('.displayMsg').html('<span class="error">'+data.message+'</span>');
                    }
                }
            });
            
    }

 


    function updateFormrefrigerantsCombustionForm(randomNum) {
        $('.updateMsg').html('');
        var particularId = $('#particularId_'+randomNum).val();
        var selectedFuel = $( "#editStationaryParticularText_"+randomNum).text();
        console.log("selectedFuel => "+selectedFuel);
       // var fuelType = $("#fuelType_"+randomNum+" option:selected").text();
        var combustionId = $('#combustionId_'+randomNum).val();
        var region = $('#region_'+randomNum).val();
        var unitOfMesurement = $('#unitOfMesurement_'+randomNum).val();
        var unitOfMesurementText = $("#unitOfMesurement_"+randomNum+" option:selected").text();
        var quantityActual = $('#quantityActual_'+randomNum).val();
        
        if(selectedFuel != ''  && region != '' && unitOfMesurement != '' && quantityActual != '') {
            $.ajax({
                url: "{{ url('/updateRefrigerantsCombustionInfo') }}",
                method: 'post',
                dataType:"JSON",
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
                    if(data.status == 'success') {
                        // bootbox.alert(data.message);
                        $('#updateMsg_'+randomNum).html('');
                        $('#updateMsg_'+randomNum).show();
                        $('#updateMsg_'+randomNum).html('<span class="success">'+data.message+'</span>');
                        setTimeout(() => {
                            $('#updateMsg_'+randomNum).html('');
                            $('#updateMsg_'+randomNum).hide();
                            $('#refrigerantsCombustionDiv').load("{{ url('/getRefreshRefrigerantsCombution')}}");
                        }, 2000);


                    } else if(data.status == 'error') {
                        // bootbox.alert(data.message);
                        $('#updateMsg_'+randomNum).html('');
                        $('#updateMsg_'+randomNum).show();
                        $('#updateMsg_'+randomNum).html('<span class="error">'+data.message+'</span>');
                        return false;
                    } else if(data.status == 'validation') {
                        // console.log("Error from validation");
                        // alert("Error from validation");
                        $.each(data.message, function(i, v) {
                            console.log(i);
                            $("#"+i+"Error_"+randomNum).html(v);
                        });
                        return false;
                    }
                }
            });
        }  else {
            $('.displayMsg').html('');
            $('.displayMsg').show();
            $('.displayMsg').html('<span class="error"> Please fill all inputs</span>');
        }
    }

</script>
