<div class="cmnBx mt-4">
    <div class="row cntr">
       
        <input type="hidden" class="editRandomId" value="{{$randomId}}" />
        <div class="col-lg-4">
        <input type="hidden" name="refrigerantsfuelUOM_{{$randomId}}" id="refrigerantsfuelUOM" value="" />
            <label for="">Region*</label>
            <select name="region_{{$randomId}}" id="region_{{$randomId}}" class="form-control refrigerantsRegion">
                <option value="" >Select Region</option>
                <?php $regionArray = ["India" => "India", "US" => "US", "Europe" => "Europe"]; ?>
                    @if(isset($regionArray) && count($regionArray) > 0) 
                        @foreach($regionArray as $id => $region) 
                            <option value="{{$id}}" @if(isset($existingStationaryParticulars->region) && $id == $existingStationaryParticulars->region) selected @endif>{{$region}}</option>
                        @endforeach
                    @endif
            </select>
            <span class="error" id="regionError_{{$randomId}}"> </span>
        </div>
        <div class="col-lg-4">
            <label for="">Unit of measurement*</label>
            <!-- <select name="unitOfMesurement" id="unitOfMesurement_{{$randomId}}" class="form-control">
                    <option value="">Select Unit of measurement</option>
                    <option value="17" @if($existingStationaryParticulars->input_uom == "17") selected @endif>Litre</option>
                    <option value="22" @if($existingStationaryParticulars->input_uom == "22") selected @endif >Gallon</option>
                    
                </select> -->
            <select name="unitOfMesurement" id="unitOfMesurement_{{$randomId}}" class="form-control">
            <option value="">Select Unit of measurement</option>
                    @if(isset($fuelTypes) && count($fuelTypes) > 0) 
                        @foreach($fuelTypes as $id => $fuelType)
                            @if(isset($fuelType) && $fuelType != '')
                                <option value="{{$id}}" @if(isset($existingStationaryParticulars->input_uom) && $id == $existingStationaryParticulars->input_uom) selected @endif>{{$fuelType}}</option>
                            @endif
                        @endforeach
                    @endif
            </select>
            <span class="error" id="unitOfMesurementError_{{$randomId}}"> </span>
        </div>
        <input type="hidden" name="combustionId_{{$randomId}}" id="combustionId_{{$randomId}}" value="{{Crypt::encrypt($combustionId)}}" />
        <input type="hidden" name="particularId_{{$randomId}}" id="particularId_{{$randomId}}" value="{{Crypt::encrypt($existingStationaryParticulars->fuel_particular_id)}}" />
        <div class="col-lg-4">
            <label for="">Quantity (Actual)*</label>
            <input type="text" name="quantityActual_{{$randomId}}" id="quantityActual_{{$randomId}}" value="{{$existingStationaryParticulars->actual_quantity}}" class="form-control">
            <span class="error" id="quantityActualError_{{$randomId}}"> </span>
        </div>
        <!-- <div class="col-lg-4">
            <label for="">Start Date*</label>
            <div class='input-group date startDate' id="startDatetimepicker_{{$randomId}}">
                <?php $startDate = date("m/d/Y", strtotime($existingStationaryParticulars->start_date)); ?>
                <input type='text' name="startDate_{{$randomId}}" id="startDate_{{$randomId}}"  value="{{$startDate}}"  class="form-control" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <span class="error" id="startDateError_{{$randomId}}"> </span>
        </div> -->
        <!-- <div class="col-lg-4">
            <label for="">End Date*</label>
            <div class='input-group date endDate' id="endDatetimepicker_{{$randomId}}">
                <?php $selectedEndDate = date("m/d/Y", strtotime($existingStationaryParticulars->end_date)); ?>
                <input type='text' name="endDate_{{$randomId}}" id="endDate_{{$randomId}}"  value="{{$selectedEndDate}}" class="form-control" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <span class="error" id="endDateError_{{$randomId}}"> </span>
        </div> -->
        <div class="col-lg-6">
            <div class="rtsertBxc">
                <div class="item">
                    <a href="javascript:void(0)"
                        class="cmnbtn hoveranim removeEdit"  id="removeForm_{{$randomId}}"><span>Cancel</span> <span class="randomNum" style="display:none"></span></a>
                </div>
                <div class="item">
                    <a href="javascript:void(0)" class="cmnbtn hoveranim refrigerantsstationaryUpdate" id="stationaryUpdate_{{$randomId}}"><span>Update</span><span class="randomNum" style="display:none"></span></a>
                </div>
            </div>
        </div>
        <div class="col-lg-12 updateMsg" id="updateMsg_{{$randomId}}" style="display:none"></div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#startDatetimepicker_{{$randomId}}").datepicker({ dateFormat: 'mm/dd/yy' });
        $("#endDatetimepicker_{{$randomId}}").datepicker({ dateFormat: 'mm/dd/yy' });

        $('.removeEdit').on('click', function(){
            // console.log("clicked on removeEdit");
            var editRandomId = '';
            var editRandomIdVal = $(this).attr('id');
           var editRandomIdArray = editRandomIdVal.split("_");
            if(editRandomIdArray[1] != '' && typeof editRandomIdArray[1] !== 'undefined') {
                editRandomId = editRandomIdArray[1];
                $('#selectedViewEdit_'+editRandomId).html('');
                $('#selectedViewEdit_'+editRandomId).hide();
                $('#selectedView_'+editRandomId).show();
            }
        });

      
         
        $(".container").one('click', '.refrigerantsstationaryUpdate', function(e) {
            e.stopPropagation();
            var editRandomId = '';
            var editRandomIdVal = $(this).attr('id');
            var editRandomIdArray = editRandomIdVal.split("_");
            if(editRandomIdArray[1] != '' && typeof editRandomIdArray[1] !== 'undefined') {
                editRandomId = editRandomIdArray[1];
                updateFormrefrigerantsCombustionForm(editRandomId);
            } else {
                alert("Something went wrong!");
            }
        });

    });
</script>