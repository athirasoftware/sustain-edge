<div class="cmnBx mt-4">
    <div class="row cntr">
        <div class="col-lg-4">
            <label for="">Fuel type*</label>
            <select name="fuelType" id="fuelType_{{$randomId}}" class="form-control fuelType">
                <option value="">Select Fuel</option>
                @if(isset($fuelTypes) && count($fuelTypes) > 0)
                @foreach($fuelTypes as $id => $fuelType)
                @if(isset($fuelType) && $fuelType != '')
                <option value="{{$id}}" @if(isset($existingStationaryParticulars->fuel_type) && $fuelType == $existingStationaryParticulars->fuel_type) selected @endif>{{$fuelType}}</option>
                @endif
                @endforeach
                @endif
            </select>
            <span class="error" id="fuelTypeError_{{$randomId}}"> </span>
        </div>
        <input type="hidden" class="editRandomId" value="{{$randomId}}" />
        <div class="col-lg-4">
            <label for="">Region*</label>
            <select name="region_{{$randomId}}" id="region_{{$randomId}}" class="form-control">
                <option value="">Select Region</option>
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
            <select name="unitOfMesurement" id="unitOfMesurement_{{$randomId}}" class="form-control">
                <option value="">Select Fuel</option>
                @if(isset($selectedMeasurement) && count($selectedMeasurement) > 0)
                @foreach($selectedMeasurement as $id => $measurement)
                <option value="{{$id}}" @if(isset($existingStationaryParticulars->input_uom) && $id == $existingStationaryParticulars->input_uom) selected @endif>{{$measurement}}</option>
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
        <div class="col-lg-4">
            <label for="">Start Date*</label>
            <div class='input-group date startDate' id="startDatetimepicker_{{$randomId}}">
                <?php $startDate = date("m/d/Y", strtotime($existingStationaryParticulars->start_date)); ?>
                <input type='text' name="startDate_{{$randomId}}" id="startDate_{{$randomId}}" value="{{$startDate}}" class="form-control startdate" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <span class="error" id="startDateError_{{$randomId}}"> </span>
        </div>
        <div class="col-lg-4">
            <label for="">End Date*</label>
            <div class='input-group date endDate' id="endDatetimepicker_{{$randomId}}">
                <?php $selectedEndDate = date("m/d/Y", strtotime($existingStationaryParticulars->end_date)); ?>
                <input type='text' name="endDate_{{$randomId}}" id="endDate_{{$randomId}}" value="{{$selectedEndDate}}" class="form-control enddate" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <span class="error" id="endDateError_{{$randomId}}"> </span>
        </div>
        <div class="col-lg-6">
            <div class="rtsertBxc">
                <div class="item">
                    <a href="javascript:void(0)" class="cmnbtn hoveranim removeEdit" id="removeForm_{{$randomId}}"><span>Cancel</span> <span class="randomNum" style="display:none"></span></a>
                </div>
                <div class="item">
                    <a href="javascript:void(0)" class="cmnbtn hoveranim stationaryUpdate" id="stationaryUpdate_{{$randomId}}"><span>Update</span><span class="randomNum" style="display:none"></span></a>
                </div>
            </div>
        </div>
        <div class="col-lg-12 updateMsg" id="updateMsg_{{$randomId}}" style="display:none"></div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#startDatetimepicker_{{$randomId}}").datepicker({
            dateFormat: 'mm/dd/yy',
            minDate:0,
        });
        $("#endDatetimepicker_{{$randomId}}").datepicker({
            dateFormat: 'mm/dd/yy',
            minDate:0,
        });

        $('.removeEdit').on('click', function() {
            // console.log("clicked on removeEdit");
            var editRandomId = '';
            var editRandomIdVal = $(this).attr('id');
            var editRandomIdArray = editRandomIdVal.split("_");
            if (editRandomIdArray[1] != '' && typeof editRandomIdArray[1] !== 'undefined') {
                editRandomId = editRandomIdArray[1];
                $('#selectedViewEdit_' + editRandomId).html('');
                $('#selectedViewEdit_' + editRandomId).hide();
                $('#selectedView_' + editRandomId).show();
            }
        });

        $(".container").one('click', '.stationaryUpdate', function(e) {
            e.stopPropagation();
            var editRandomId = '';
            var editRandomIdVal = $(this).attr('id');
            var editRandomIdArray = editRandomIdVal.split("_");
            if (editRandomIdArray[1] != '' && typeof editRandomIdArray[1] !== 'undefined') {
                editRandomId = editRandomIdArray[1];
                updateFormCombustionForm(editRandomId);
            } else {
                alert("Something went wrong!");
            }
        });

    });
</script>