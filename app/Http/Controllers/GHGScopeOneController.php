<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayUService\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\StationaryCombustion;
use App\Models\MobileCombustion;
use App\Models\RefrigerantsCombustion;
use App\Models\PurchaseofElectricityCombustion;
use App\Models\UnitsOfMeasurements;
use App\Models\ScopeOneCombustion;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class GHGScopeOneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Administrator|Team Lead|Employee');
    }

    public function getunitofmeasument(Request $request)
    {

        try {

            $isValid = 'error';
            $errorMsg = 'Invalid record found';
            $encryptedId = $request->selectedId;
            if (isset($encryptedId) && $encryptedId != '') {
                $fuelTypes = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $encryptedId)->get();
            }
            $data = $fuelTypes;
            return json_encode($data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getGHGQuestionnaire(Request $request)
    {

        try {
            $unitOfMeasurements = [];
            $existingStationaryParticulars = ScopeOneCombustion::where('scope_name', "stationaryCombution")->where('created_by_user_id', Auth::user()->id)->get();

            $existingMobileParticulars = ScopeOneCombustion::where('scope_name', "mobileCombution")->where('created_by_user_id', Auth::user()->id)->get();
            $existingRefrigerantsParticulars = ScopeOneCombustion::where('scope_name', "refrigerantsCombution")->where('created_by_user_id', Auth::user()->id)->get();

            $stationaryParticulars = StationaryCombustion::select('particulars', 'id', 'quantity_type')->groupBy('quantity_type', 'particulars', 'id')->get();
            $mobileParticulars = MobileCombustion::select('particulars', 'id', 'quantity_type')->groupBy('quantity_type', 'particulars', 'id')->get();
            // echo "<pre>".print_r($existingRefrigerantsParticulars); exit;
            $refrigerantsParticulars = RefrigerantsCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->get();
            $refrigerantsUnits = UnitsOfMeasurements::select('id', 'unit_of_measurement')->whereIn('unit_of_measurement', ['KG', 'Litre'])->pluck('unit_of_measurement', 'id')->toArray();
            $fuelTypes = UnitsOfMeasurements::groupBy('quantity_type')->pluck('quantity_type')->toArray();
            // print_r($refrigerantsUnits); exit;
            $seletedparticulars = StationaryCombustion::pluck('fuel_type', 'id')->toArray();
            if (isset($fuelTypes) && count($fuelTypes) > 0) {
                foreach ($fuelTypes as $fuel) {
                    if (isset($unitOfMeasurements[$fuel]) && is_array($unitOfMeasurements[$fuel])) {
                        // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    } else {
                        $unitOfMeasurements[$fuel] = [];
                    }
                    $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                }
            }
            //print_r($existingMobileParticulars); exit;
            if (isset($seletedparticulars) && count($seletedparticulars) > 0) {
                foreach ($seletedparticulars as $pfuel) {
                    if (isset($selectedfuelType[$pfuel]) && is_array($selectedfuelType[$pfuel])) {
                        // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    } else {
                        $selectedfuelType[$pfuel] = [];
                    }
                    $selectedfuelType[$pfuel] = StationaryCombustion::select('id', 'fuel_type')->where('particulars', $pfuel)->pluck('fuel_type', 'id')->toArray();
                }
            }
            // echo "<pre>";print_r($existingMobileParticulars);exit();
            return view('ghg.questionnaire', compact('refrigerantsUnits', 'refrigerantsParticulars', 'mobileParticulars', 'selectedfuelType', 'stationaryParticulars', 'existingStationaryParticulars', 'existingMobileParticulars', 'existingRefrigerantsParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function mobilegetGHGQuestionnaire(Request $request)
    {
        try {
            $unitOfMeasurements = [];
            $existingStationaryParticulars = ScopeOneCombustion::where('scope_name', "stationaryCombution")->where('created_by_user_id', Auth::user()->id)->get();

            $existingMobileParticulars = ScopeOneCombustion::where('scope_name', "mobileCombution")->where('created_by_user_id', Auth::user()->id)->get();
            $existingRefrigerantsParticulars = ScopeOneCombustion::where('scope_name', "refrigerantsCombution")->where('created_by_user_id', Auth::user()->id)->get();

            $stationaryParticulars = StationaryCombustion::select('particulars', 'id', 'quantity_type')->groupBy('quantity_type', 'particulars', 'id')->get();
            $mobileParticulars = MobileCombustion::select('particulars', 'id', 'quantity_type')->groupBy('quantity_type', 'particulars', 'id')->get();
            // echo "<pre>".print_r($existingRefrigerantsParticulars); exit;
            $refrigerantsParticulars = RefrigerantsCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->get();
            $refrigerantsUnits = UnitsOfMeasurements::select('id', 'unit_of_measurement')->whereIn('unit_of_measurement', ['KG', 'Litre'])->pluck('unit_of_measurement', 'id')->toArray();
            $fuelTypes = UnitsOfMeasurements::groupBy('quantity_type')->pluck('quantity_type')->toArray();
            // print_r($refrigerantsUnits); exit;
            $seletedparticulars = StationaryCombustion::pluck('fuel_type', 'id')->toArray();
            if (isset($fuelTypes) && count($fuelTypes) > 0) {
                foreach ($fuelTypes as $fuel) {
                    if (isset($unitOfMeasurements[$fuel]) && is_array($unitOfMeasurements[$fuel])) {
                        // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    } else {
                        $unitOfMeasurements[$fuel] = [];
                    }
                    $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                }
            }
            //print_r($existingMobileParticulars); exit;
            if (isset($seletedparticulars) && count($seletedparticulars) > 0) {
                foreach ($seletedparticulars as $pfuel) {
                    if (isset($selectedfuelType[$pfuel]) && is_array($selectedfuelType[$pfuel])) {
                        // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    } else {
                        $selectedfuelType[$pfuel] = [];
                    }
                    $selectedfuelType[$pfuel] = StationaryCombustion::select('id', 'fuel_type')->where('particulars', $pfuel)->pluck('fuel_type', 'id')->toArray();
                }
            }
            // echo "<pre>";print_r($existingMobileParticulars);exit();
            return view('ghg.mobilequestionnaire', compact('refrigerantsUnits', 'refrigerantsParticulars', 'mobileParticulars', 'selectedfuelType', 'stationaryParticulars', 'existingStationaryParticulars', 'existingMobileParticulars', 'existingRefrigerantsParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function refrigerantsgetGHGQuestionnaire(Request $request)
    {
        try {
            $unitOfMeasurements = [];
            $existingStationaryParticulars = ScopeOneCombustion::where('scope_name', "stationaryCombution")->where('created_by_user_id', Auth::user()->id)->get();

            $existingMobileParticulars = ScopeOneCombustion::where('scope_name', "mobileCombution")->where('created_by_user_id', Auth::user()->id)->get();
            $existingRefrigerantsParticulars = ScopeOneCombustion::where('scope_name', "refrigerantsCombution")->where('created_by_user_id', Auth::user()->id)->get();

            $stationaryParticulars = StationaryCombustion::select('particulars', 'id', 'quantity_type')->groupBy('quantity_type', 'particulars', 'id')->get();
            $mobileParticulars = MobileCombustion::select('particulars', 'id', 'quantity_type')->groupBy('quantity_type', 'particulars', 'id')->get();
            //  echo "<pre>";
            //  print_r($existingRefrigerantsParticulars); 
            //  echo "</pre>";exit;
            $refrigerantsParticulars = RefrigerantsCombustion::select('particulars', 'id', 'quantity_type')->groupBy('quantity_type', 'particulars', 'id')->get();
            $refrigerantsUnits = UnitsOfMeasurements::select('id', 'unit_of_measurement')->whereIn('unit_of_measurement', ['KG', 'Litre'])->pluck('unit_of_measurement', 'id')->toArray();
            $fuelTypes = UnitsOfMeasurements::groupBy('quantity_type')->pluck('quantity_type')->toArray();
            // print_r($refrigerantsUnits); exit;
            $seletedparticulars = StationaryCombustion::pluck('fuel_type', 'id')->toArray();
            if (isset($fuelTypes) && count($fuelTypes) > 0) {
                foreach ($fuelTypes as $fuel) {
                    if (isset($unitOfMeasurements[$fuel]) && is_array($unitOfMeasurements[$fuel])) {
                        // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    } else {
                        $unitOfMeasurements[$fuel] = [];
                    }
                    $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                }
            }
            if (isset($seletedparticulars) && count($seletedparticulars) > 0) {
                foreach ($seletedparticulars as $pfuel) {
                    if (isset($selectedfuelType[$pfuel]) && is_array($selectedfuelType[$pfuel])) {
                        // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    } else {
                        $selectedfuelType[$pfuel] = [];
                    }
                    $selectedfuelType[$pfuel] = StationaryCombustion::select('id', 'fuel_type')->where('particulars', $pfuel)->pluck('fuel_type', 'id')->toArray();
                }
            }
            return view('ghg.refrigerantsquestionnaire', compact('refrigerantsUnits', 'refrigerantsParticulars', 'mobileParticulars', 'selectedfuelType', 'stationaryParticulars', 'existingStationaryParticulars', 'existingMobileParticulars', 'existingRefrigerantsParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function getPurchaseGHGQuestionnaire(Request $request)
    {

        try {
            $unitOfMeasurements = [];
            $existingStationaryParticulars = ScopeOneCombustion::select('fuel_particular', 'id')->where('scope_name', "purchaseofElectricity")->groupBy('fuel_particular', 'id')->pluck('fuel_particular', 'id')->toArray();
            $stationaryParticulars = StationaryCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
            $fuelTypes = UnitsOfMeasurements::groupBy('quantity_type')->pluck('quantity_type')->toArray();
            if (isset($fuelTypes) && count($fuelTypes) > 0) {
                foreach ($fuelTypes as $fuel) {
                    if (isset($unitOfMeasurements[$fuel]) && is_array($unitOfMeasurements[$fuel])) {
                        // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    } else {
                        $unitOfMeasurements[$fuel] = [];
                    }
                    $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                }
            }
            // echo "<pre>";print_r($existingStationaryParticulars);exit;
            return view('ghg.purchasequestionnaire', compact('stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveStationaryCombution(Request $request)
    {
        try {
            $randomNum = $request->randomNum;
            // Log::info(print_r($request->all(), true));
            $validator = Validator::make($request->all(), [
                'randomNum' => 'required|string|max:250',
                'particularId' => 'required|numeric',
                'selectedFuel' => 'required|string|max:250',
                //'fuelType' => 'required|string|max:250',
                'region' => 'required|string|max:250',
                'unitOfMesurement' => 'required|string|max:250',
                'quantityActual' => 'required|numeric|max:10000'
                // 'startDate' => 'required|date',
                // 'endDate' => 'required|date|after:startDate'
            ]);
            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "message" => $messages);
                return response()->json($response);
            }/*  else {
                $response = array("status" => "success", "message" => 'Stationary Combution Saved Successfully');
                return response()->json($response);
            } */
            $status = 'error';
            $msg = 'Something Went Wrong!';
            $lastInsertedId = '';
            $convertedActualQuantity = 0;
            $stationaryCombustionStandards = StationaryCombustion::find($request->particularId);
            
            Log::info("request->particularId => " . $request->particularId);
            if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {

                $convertedValue1 = $convertedFactorKj =  $conversionFactorKj = $convertedEmission = $totalEmission = '';
                $convertedUom = $convertionUnit = $ncv = $density = $needToBeConvertUom = $emission = $standard = '';

                if (isset($request->unitOfMesurement) && $request->unitOfMesurement != '' && is_numeric($request->unitOfMesurement)) {


                    $unitOfMesurementDetails = UnitsOfMeasurements::find($request->unitOfMesurement);
                    if (isset($unitOfMesurementDetails->id) && $unitOfMesurementDetails->id != '') {
                        // Log::info($unitOfMesurementDetails->uom_description);
                        $convertedActualQuantity = $request->quantityActual * $unitOfMesurementDetails->converted_value;
                    }
                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $ncv = $stationaryCombustionStandards->ncv_kj_kg;
                    if (isset($stationaryCombustionStandards->density) && $stationaryCombustionStandards->density != '') {
                        $density = $stationaryCombustionStandards->density;
                    } else {
                        $density = 0;
                    }
                    $needToBeConvertUom = $stationaryCombustionStandards->uom_factor_to_kj;
                    // $convertedFactorKj = $stationaryCombustionStandards->converted_qnty;
                    // $conversionFactorKj = $stationaryCombustionStandards->conversion_factor_to_kj;
                    $emission = $stationaryCombustionStandards->emission_factor_kj_tj;
                    $standard = $stationaryCombustionStandards->standard_uom;
                    $unitOfMesurementText = $request->unitOfMesurementText;


                    $convertedValue1 = round(($convertedActualQuantity * $convertionUnit), 2);
                    $conversionFactorKj = $ncv * $density;
                    $convertedFactorKj = $convertedActualQuantity * $conversionFactorKj;
                    $convertedEmission = number_format(($emission / 1000) / 1000000000, 10);

                    $totalEmission = number_format(($convertedEmission * $convertedFactorKj), 2);

                    $ScopeOneCombustionNew = new ScopeOneCombustion();
                    $ScopeOneCombustionNew->fuel_particular_id  = $request->particularId;
                    $ScopeOneCombustionNew->scope_type  = 1; //stationary 
                    $ScopeOneCombustionNew->scope_name  = "stationaryCombution";
                    $ScopeOneCombustionNew->fuel_particular  = $request->selectedFuel;
                    $ScopeOneCombustionNew->fuel_type = $stationaryCombustionStandards->fuel_type;
                    $ScopeOneCombustionNew->region = $request->region;
                    $ScopeOneCombustionNew->input_uom = $request->unitOfMesurement;

                    $ScopeOneCombustionNew->actual_quantity = $request->quantityActual;
                    $ScopeOneCombustionNew->converted_actual_quantity = $convertedActualQuantity;

                    $ScopeOneCombustionNew->company_id = Auth::user()->company_id;
                    $ScopeOneCombustionNew->created_by_user_id = Auth::user()->id;

                    if (isset($convertedUom) && $convertedUom != '' && $convertedUom != NULL) {
                        $ScopeOneCombustionNew->converted_uom = $convertedUom;
                    } else {
                        $ScopeOneCombustionNew->converted_uom = $unitOfMesurementText;
                    }
                    $ScopeOneCombustionNew->conversion_unit = $convertionUnit;
                    $ScopeOneCombustionNew->converted_value1 = $convertedValue1;
                    $ScopeOneCombustionNew->ncv = $ncv;
                    $ScopeOneCombustionNew->density = $density;
                    if (isset($needToBeConvertUom) && $needToBeConvertUom != ''  && $needToBeConvertUom != NULL) {
                        $ScopeOneCombustionNew->uom = $needToBeConvertUom;
                    } else {
                        $ScopeOneCombustionNew->uom = $request->unitOfMesurement;
                    }
                    $ScopeOneCombustionNew->conversion_factor_kj = $conversionFactorKj;
                    $ScopeOneCombustionNew->converted_factor_kj = $convertedFactorKj;
                    $ScopeOneCombustionNew->emission = $emission;
                    $ScopeOneCombustionNew->converted_emission = $convertedEmission;
                    $ScopeOneCombustionNew->total_emission = $totalEmission;
                    $ScopeOneCombustionNew->standard = $standard;

                    $ScopeOneCombustionNew->save();
                    $lastInsertedId = $ScopeOneCombustionNew->id;
                } else {
                    $msg = 'Invalid unit of measurements';
                }
            } else {
                $msg = 'Invalid fuel particular found';
            }
            if (isset($lastInsertedId) && $lastInsertedId != '') {
                // Log::info("Stationary Combution Saved Successfully!");
                $status = 'success';
                $msg = 'Stationary Combution Saved Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function mobilesaveStationaryCombution(Request $request)
    {
        try {
            $randomNum = $request->randomNum;
            // Log::info(print_r($request->all(), true));
            $validator = Validator::make($request->all(), [
                'randomNum' => 'required|string|max:250',
                'particularId' => 'required|numeric',
                'selectedFuel' => 'required|string|max:250',
                //'fuelType' => 'required|string|max:250',
                'region' => 'required|string|max:250',
                'unitOfMesurement' => 'required|string|max:250',
                'quantityActual' => 'required|numeric|max:10000'
                // 'startDate' => 'required|date',
                // 'endDate' => 'required|date|after:startDate'
            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "message" => $messages);
                return response()->json($response);
            }/*  else {
                $response = array("status" => "success", "message" => 'Stationary Combution Saved Successfully');
                return response()->json($response);
            } */
            $status = 'error';
            $msg = 'Something Went Wrong!';
            $lastInsertedId = '';
            $convertedActualQuantity = 0;
            $stationaryCombustionStandards = MobileCombustion::find($request->particularId);
            // print_r($stationaryCombustionStandards); exit;
            Log::info("request->particularId => " . $request->particularId);
            if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {

                $convertedValue1 = $convertedFactorKj =  $conversionFactorKj = $convertedEmission = $totalEmission = '';
                $convertedUom = $convertionUnit = $ncv = $density = $needToBeConvertUom = $emission = $standard = '';

                if (isset($request->unitOfMesurement) && $request->unitOfMesurement != '' && is_numeric($request->unitOfMesurement)) {


                    $unitOfMesurementDetails = UnitsOfMeasurements::find($request->unitOfMesurement);
                    if (isset($unitOfMesurementDetails->id) && $unitOfMesurementDetails->id != '') {
                        // Log::info($unitOfMesurementDetails->uom_description);
                        $convertedActualQuantity = $request->quantityActual * $unitOfMesurementDetails->converted_value;
                    }
                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $ncv = $stationaryCombustionStandards->ncv_kj_kg;
                    if (isset($stationaryCombustionStandards->density) && $stationaryCombustionStandards->density != '') {
                        $density = $stationaryCombustionStandards->density;
                    } else {
                        $density = 0;
                    }
                    $needToBeConvertUom = $stationaryCombustionStandards->uom_factor_to_kj;
                    // $convertedFactorKj = $stationaryCombustionStandards->converted_qnty;
                    // $conversionFactorKj = $stationaryCombustionStandards->conversion_factor_to_kj;
                    $emission = $stationaryCombustionStandards->emission_factor_kj_tj;
                    $standard = $stationaryCombustionStandards->standard_uom;
                    $unitOfMesurementText = $request->unitOfMesurementText;


                    $convertedValue1 = round(($convertedActualQuantity * $convertionUnit), 2);
                    $conversionFactorKj = round(($ncv * $density), 2);
                    $convertedFactorKj = round(($convertedActualQuantity * $conversionFactorKj), 2);
                    $convertedEmission = number_format(($emission / 1000) / 1000000000, 10);

                    $totalEmission = number_format(($convertedEmission * $convertedFactorKj), 3);

                    $ScopeOneCombustionNew = new ScopeOneCombustion();
                    $ScopeOneCombustionNew->fuel_particular_id  = $request->particularId;
                    $ScopeOneCombustionNew->scope_type  = 1; //mobile 
                    $ScopeOneCombustionNew->scope_name  = "mobileCombution";
                    $ScopeOneCombustionNew->fuel_particular  = $request->selectedFuel;
                    $ScopeOneCombustionNew->fuel_type = $stationaryCombustionStandards->fuel_type;
                    $ScopeOneCombustionNew->region = $request->region;
                    $ScopeOneCombustionNew->input_uom = $request->unitOfMesurement;

                    $ScopeOneCombustionNew->actual_quantity = $request->quantityActual;
                    $ScopeOneCombustionNew->converted_actual_quantity = $convertedActualQuantity;

                    $ScopeOneCombustionNew->company_id = Auth::user()->company_id;
                    $ScopeOneCombustionNew->created_by_user_id = Auth::user()->id;

                    if (isset($convertedUom) && $convertedUom != '' && $convertedUom != NULL) {
                        $ScopeOneCombustionNew->converted_uom = $convertedUom;
                    } else {
                        $ScopeOneCombustionNew->converted_uom = $unitOfMesurementText;
                    }
                    $ScopeOneCombustionNew->conversion_unit = $convertionUnit;
                    $ScopeOneCombustionNew->converted_value1 = $convertedValue1;
                    $ScopeOneCombustionNew->ncv = $ncv;
                    $ScopeOneCombustionNew->density = $density;
                    if (isset($needToBeConvertUom) && $needToBeConvertUom != ''  && $needToBeConvertUom != NULL) {
                        $ScopeOneCombustionNew->uom = $needToBeConvertUom;
                    } else {
                        $ScopeOneCombustionNew->uom = $request->unitOfMesurement;
                    }
                    $ScopeOneCombustionNew->conversion_factor_kj = $conversionFactorKj;
                    $ScopeOneCombustionNew->converted_factor_kj = $convertedFactorKj;
                    $ScopeOneCombustionNew->emission = $emission;
                    $ScopeOneCombustionNew->converted_emission = $convertedEmission;
                    $ScopeOneCombustionNew->total_emission = $totalEmission;
                    $ScopeOneCombustionNew->standard = $standard;

                    $ScopeOneCombustionNew->save();
                    $lastInsertedId = $ScopeOneCombustionNew->id;
                } else {
                    $msg = 'Invalid unit of measurements';
                }
            } else {
                $msg = 'Invalid fuel particular found';
            }
            if (isset($lastInsertedId) && $lastInsertedId != '') {
                // Log::info("Stationary Combution Saved Successfully!");
                $status = 'success';
                $msg = 'Mobile Combution Saved Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function refrigerantssaveStationaryCombution(Request $request)
    {
        try {
            $randomNum = $request->randomNum;
            // Log::info(print_r($request->all(), true));
            $validator = Validator::make($request->all(), [
                'randomNum' => 'required|string|max:250',
                'particularId' => 'required|numeric',
                'selectedFuel' => 'required|string|max:250',
                //'fuelType' => 'required|string|max:250',
                'region' => 'required|string|max:250',
                'unitOfMesurement' => 'required|string|max:250',
                'quantityActual' => 'required|numeric|max:10000'
                // 'startDate' => 'required|date',
                // 'endDate' => 'required|date|after:startDate'
            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "message" => $messages);
                return response()->json($response);
            }/*  else {
                $response = array("status" => "success", "message" => 'Stationary Combution Saved Successfully');
                return response()->json($response);
            } */
            $status = 'error';
            $msg = 'Something Went Wrong!';
            $lastInsertedId = '';
            $convertedActualQuantity = 0;
            $stationaryCombustionStandards = RefrigerantsCombustion::find($request->particularId);
            // print_r($stationaryCombustionStandards); exit;
            Log::info("request->particularId => " . $request->particularId);
            if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {

                $convertedValue1 = $convertedFactorKj =  $conversionFactorKj = $convertedEmission = $totalEmission = '';
                $convertedUom = $convertionUnit = $ncv = $density = $needToBeConvertUom = $emission = $standard = '';

                if (isset($request->unitOfMesurement) && $request->unitOfMesurement != '' && is_numeric($request->unitOfMesurement)) {


                    $unitOfMesurementDetails = UnitsOfMeasurements::find($request->unitOfMesurement);

                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $global_warming = $stationaryCombustionStandards->global_warming;


                    $standard = $stationaryCombustionStandards->standard_uom;


                    $convertedValue1 = $request->quantityActual * $convertionUnit;

                    $totalEmission = $global_warming * $convertedValue1;

                    $ScopeOneCombustionNew = new ScopeOneCombustion();
                    $ScopeOneCombustionNew->fuel_particular_id  = $request->particularId;
                    $ScopeOneCombustionNew->scope_type  = 1; //refrigerants
                    $ScopeOneCombustionNew->scope_name  = "refrigerantsCombution";
                    $ScopeOneCombustionNew->fuel_particular  = $request->selectedFuel;
                    $ScopeOneCombustionNew->fuel_type = $stationaryCombustionStandards->fuel_type;
                    $ScopeOneCombustionNew->region = $request->region;
                    $ScopeOneCombustionNew->input_uom = $request->unitOfMesurement;

                    $ScopeOneCombustionNew->actual_quantity = $request->quantityActual;

                    $ScopeOneCombustionNew->company_id = Auth::user()->company_id;
                    $ScopeOneCombustionNew->created_by_user_id = Auth::user()->id;

                    if (isset($convertedUom) && $convertedUom != '' && $convertedUom != NULL) {
                        $ScopeOneCombustionNew->converted_uom = $convertedUom;
                    } else {
                        $ScopeOneCombustionNew->converted_uom = $unitOfMesurementText;
                    }
                    $ScopeOneCombustionNew->conversion_unit = $convertionUnit;
                    $ScopeOneCombustionNew->converted_value1 = $convertedValue1;
                    $ScopeOneCombustionNew->uom = $request->unitOfMesurement;

                    $ScopeOneCombustionNew->total_emission = $totalEmission;
                    $ScopeOneCombustionNew->standard = $stationaryCombustionStandards->standard;

                    $ScopeOneCombustionNew->save();
                    $lastInsertedId = $ScopeOneCombustionNew->id;
                } else {
                    $msg = 'Invalid unit of measurements';
                }
            } else {
                $msg = 'Invalid fuel particular found';
            }
            if (isset($lastInsertedId) && $lastInsertedId != '') {
                // Log::info("Stationary Combution Saved Successfully!");
                $status = 'success';
                $msg = 'Refrigerants Combution Saved Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function savePurchaseElectricity(Request $request)
    {

        try {
            $randomNum = $request->randomNum;
            // Log::info(print_r($request->all(), true));
            $validator = Validator::make($request->all(), [
                //  'randomNum' => 'required|string|max:250',
                //  'particularId' => 'required|numeric',
                //'selectedFuel' => 'required|string|max:250',

                // 'region' => 'required|string|max:250',
                // 'unitOfMesurement' => 'required|string|max:250',
                // 'quantityActual' => 'required|numeric|max:10000',

            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "message" => $messages);
                return response()->json($response);
            }/*  else {
                $response = array("status" => "success", "message" => 'Stationary Combution Saved Successfully');
                return response()->json($response);
            } */
            $status = 'error';
            $msg = 'Something Went Wrong!';
            $lastInsertedId = '';
            $convertedActualQuantity = 0;
            $stationaryCombustionStandards = StationaryCombustion::find($request->particularId);

            Log::info("request->particularId => " . $request->particularId);
            if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {

                $convertedValue1 = $convertedFactorKj =  $conversionFactorKj = $convertedEmission = $totalEmission = '';
                $convertedUom = $convertionUnit = $ncv = $density = $needToBeConvertUom = $emission = $standard = '';

                if (isset($request->unitOfMesurement) && $request->unitOfMesurement != '') {


                    $unitOfMesurementDetails = UnitsOfMeasurements::find($request->unitOfMesurement);
                    if (isset($unitOfMesurementDetails->id) && $unitOfMesurementDetails->id != '') {
                        // Log::info($unitOfMesurementDetails->uom_description);
                        $convertedActualQuantity = $request->quantityActual * $unitOfMesurementDetails->converted_value;
                    }
                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $ncv = $stationaryCombustionStandards->ncv_kj_kg;
                    if (isset($stationaryCombustionStandards->density) && $stationaryCombustionStandards->density != '') {
                        $density = $stationaryCombustionStandards->density;
                    } else {
                        $density = 0;
                    }
                    $needToBeConvertUom = $stationaryCombustionStandards->uom_factor_to_kj;
                    // $convertedFactorKj = $stationaryCombustionStandards->converted_qnty;
                    // $conversionFactorKj = $stationaryCombustionStandards->conversion_factor_to_kj;
                    $emission = $stationaryCombustionStandards->emission_factor_kj_tj;
                    $standard = $stationaryCombustionStandards->standard_uom;
                    $unitOfMesurementText = $request->unitOfMesurementText;


                    $convertedValue1 = round(($convertedActualQuantity * $convertionUnit), 2);
                    $conversionFactorKj = round(($ncv * $density), 2);
                    $convertedFactorKj = round(($convertedActualQuantity * $conversionFactorKj), 2);
                    $convertedEmission = number_format(($emission / 1000) / 1000000000, 10);

                    $totalEmission = number_format(($convertedEmission * $convertedFactorKj), 3);

                    $ScopeOneCombustionNew = new ScopeOneCombustion();
                    $ScopeOneCombustionNew->fuel_particular_id  = $request->particularId;
                    $ScopeOneCombustionNew->scope_type  = 2; //purchaseofelectricity
                    $ScopeOneCombustionNew->scope_name  = "purchaseofElectricity";
                    $ScopeOneCombustionNew->fuel_particular  = $request->selectedFuel;
                    $ScopeOneCombustionNew->fuel_type = "Length";
                    $ScopeOneCombustionNew->region = $request->region;
                    $ScopeOneCombustionNew->input_uom = $request->unitOfMesurement;

                    $ScopeOneCombustionNew->actual_quantity = $request->quantityActual;
                    $ScopeOneCombustionNew->converted_actual_quantity = $convertedActualQuantity;
                    //$ScopeOneCombustionNew->start_date = date('Y-m-d', strtotime($request->startDate));
                    // $ScopeOneCombustionNew->end_date = date('Y-m-d', strtotime($request->endDate));
                    $ScopeOneCombustionNew->company_id = Auth::user()->company_id;
                    $ScopeOneCombustionNew->created_by_user_id = Auth::user()->id;

                    if (isset($convertedUom) && $convertedUom != '' && $convertedUom != NULL) {
                        $ScopeOneCombustionNew->converted_uom = $convertedUom;
                    } else {
                        $ScopeOneCombustionNew->converted_uom = $unitOfMesurementText;
                    }
                    $ScopeOneCombustionNew->conversion_unit = $convertionUnit;
                    $ScopeOneCombustionNew->converted_value1 = $convertedValue1;
                    $ScopeOneCombustionNew->ncv = $ncv;
                    $ScopeOneCombustionNew->density = $density;
                    if (isset($needToBeConvertUom) && $needToBeConvertUom != ''  && $needToBeConvertUom != NULL) {
                        $ScopeOneCombustionNew->uom = $needToBeConvertUom;
                    } else {
                        $ScopeOneCombustionNew->uom = $request->unitOfMesurement;
                    }
                    $ScopeOneCombustionNew->conversion_factor_kj = $conversionFactorKj;
                    $ScopeOneCombustionNew->converted_factor_kj = $convertedFactorKj;
                    $ScopeOneCombustionNew->emission = $emission;
                    $ScopeOneCombustionNew->converted_emission = $convertedEmission;
                    $ScopeOneCombustionNew->total_emission = $totalEmission;
                    $ScopeOneCombustionNew->standard = $standard;

                    $ScopeOneCombustionNew->save();
                    $lastInsertedId = $ScopeOneCombustionNew->id;
                } else {
                    $msg = 'Invalid unit of measurements';
                }
            } else {
                $msg = 'Invalid fuel particular found';
            }
            if (isset($lastInsertedId) && $lastInsertedId != '') {
                // Log::info("Stationary Combution Saved Successfully!");
                $status = 'success';
                $msg = 'Stationary Combution Saved Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function getRefreshStationaryCombution(Request $request)
    {
        try {
            $unitOfMeasurements = [];
            // $existingStationaryParticulars = ScopeOneCombustion::select('fuel_particular', 'id')->where('scope_name',"stationaryCombution")->groupBy('fuel_particular', 'id')->pluck('fuel_particular', 'id')->toArray();
            $existingStationaryParticulars = ScopeOneCombustion::where('scope_name', "stationaryCombution")->get();

            $existingMobileParticulars = ScopeOneCombustion::select('fuel_particular', 'id')->where('scope_name', "mobileCombution")->groupBy('fuel_particular', 'id')->pluck('fuel_particular', 'id')->toArray();
            $existingRefrigerantsParticulars = ScopeOneCombustion::select('fuel_particular', 'id')->where('scope_name', "refrigerantsCombution")->groupBy('fuel_particular', 'id')->pluck('fuel_particular', 'id')->toArray();

            // $stationaryParticulars = StationaryCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
            $stationaryParticulars = StationaryCombustion::select('particulars', 'id', 'quantity_type')->groupBy('quantity_type', 'particulars', 'id')->get();

            $fuelTypes = UnitsOfMeasurements::groupBy('quantity_type')->pluck('quantity_type')->toArray();
            if (isset($fuelTypes) && count($fuelTypes) > 0) {
                foreach ($fuelTypes as $fuel) {
                    if (isset($unitOfMeasurements[$fuel]) && is_array($unitOfMeasurements[$fuel])) {
                        // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    } else {
                        $unitOfMeasurements[$fuel] = [];
                    }
                    $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                }
            }
            // echo "<pre>";print_r($unitOfMeasurements);exit();
            return view('ghg.stationaryCombustion', compact('stationaryParticulars', 'existingStationaryParticulars', 'existingMobileParticulars', 'existingRefrigerantsParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRefreshMobileCombution(Request $request)
    {
        try {
            $unitOfMeasurements = [];
            // $existingStationaryParticulars = ScopeOneCombustion::select('fuel_particular', 'id')->where('scope_name',"stationaryCombution")->groupBy('fuel_particular', 'id')->pluck('fuel_particular', 'id')->toArray();
            $existingStationaryParticulars = ScopeOneCombustion::where('scope_name', "stationaryCombution")->get();

            $existingMobileParticulars = ScopeOneCombustion::where('scope_name', "mobileCombution")->get();
            $existingRefrigerantsParticulars = ScopeOneCombustion::where('scope_name', "refrigerantsCombution")->get();

            // $stationaryParticulars = StationaryCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
            $mobileParticulars = MobileCombustion::select('particulars', 'id', 'quantity_type')->groupBy('quantity_type', 'particulars', 'id')->get();

            $fuelTypes = UnitsOfMeasurements::groupBy('quantity_type')->pluck('quantity_type')->toArray();
            if (isset($fuelTypes) && count($fuelTypes) > 0) {
                foreach ($fuelTypes as $fuel) {
                    if (isset($unitOfMeasurements[$fuel]) && is_array($unitOfMeasurements[$fuel])) {
                        // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    } else {
                        $unitOfMeasurements[$fuel] = [];
                    }
                    $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                }
            }
            // echo "<pre>";print_r($unitOfMeasurements);exit();
            return view('ghg.mobileCombustion', compact('mobileParticulars', 'existingStationaryParticulars', 'existingMobileParticulars', 'existingRefrigerantsParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRefreshRefrigerantsCombution(Request $request)
    {
        try {
            $unitOfMeasurements = [];
            // $existingStationaryParticulars = ScopeOneCombustion::select('fuel_particular', 'id')->where('scope_name',"stationaryCombution")->groupBy('fuel_particular', 'id')->pluck('fuel_particular', 'id')->toArray();
            $existingStationaryParticulars = ScopeOneCombustion::where('scope_name', "stationaryCombution")->get();

            $existingMobileParticulars = ScopeOneCombustion::where('scope_name', "mobileCombution")->get();
            $existingRefrigerantsParticulars = ScopeOneCombustion::where('scope_name', "refrigerantsCombution")->get();

            // $stationaryParticulars = StationaryCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
            $refrigerantsParticulars = RefrigerantsCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->get();


            $fuelTypes = UnitsOfMeasurements::groupBy('quantity_type')->pluck('quantity_type')->toArray();
            if (isset($fuelTypes) && count($fuelTypes) > 0) {
                foreach ($fuelTypes as $fuel) {
                    if (isset($unitOfMeasurements[$fuel]) && is_array($unitOfMeasurements[$fuel])) {
                        // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    } else {
                        $unitOfMeasurements[$fuel] = [];
                    }
                    $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                }
            }
            // echo "<pre>";print_r($unitOfMeasurements);exit();
            return view('ghg.refrigerants', compact('refrigerantsParticulars', 'existingStationaryParticulars', 'existingMobileParticulars', 'existingRefrigerantsParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRefreshPurchaseofElectricity(Request $request)
    {
        try {
            $unitOfMeasurements = [];
            $existingStationaryParticulars = ScopeOneCombustion::select('fuel_particular', 'id')->groupBy('fuel_particular', 'id')->pluck('fuel_particular', 'id')->toArray();
            $stationaryParticulars = StationaryCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
            $fuelTypes = UnitsOfMeasurements::groupBy('quantity_type')->pluck('quantity_type')->toArray();
            if (isset($fuelTypes) && count($fuelTypes) > 0) {
                foreach ($fuelTypes as $fuel) {
                    if (isset($unitOfMeasurements[$fuel]) && is_array($unitOfMeasurements[$fuel])) {
                        // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    } else {
                        $unitOfMeasurements[$fuel] = [];
                    }
                    $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                }
            }
            // echo "<pre>";print_r($unitOfMeasurements);exit();
            return view('ghg.purchasestationaryCombustion', compact('stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function deleteStationaryCombution(Request $request)
    {
        try {

            $isValid = 'error';
            $errorMsg = 'Invalid record found';
            $encryptedId = $request->selectedId;
            if (isset($encryptedId) && $encryptedId != '') {
                $decryptedId = Crypt::decrypt($encryptedId);
                if (isset($decryptedId) && $decryptedId != '') {
                    $existingParticulars = ScopeOneCombustion::where('id', $decryptedId)->first();
                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {
                        $existingParticulars->deleted_at = date('Y-m-d h:i:s');
                        $recorddeleted = $existingParticulars->save();
                        if ($recorddeleted) {
                            $isValid = 'success';
                            $errorMsg = 'Record deleted successfully';
                        }
                    }
                }
            }
            $data = ['status' => $isValid, 'message' => $errorMsg];
            return json_encode($data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteMobileCombution(Request $request)
    {
        try {

            $isValid = 'error';
            $errorMsg = 'Invalid record found';
            $encryptedId = $request->selectedId;
            if (isset($encryptedId) && $encryptedId != '') {
                $decryptedId = Crypt::decrypt($encryptedId);
                if (isset($decryptedId) && $decryptedId != '') {
                    $existingParticulars = ScopeOneCombustion::where('id', $decryptedId)->first();
                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {
                        $existingParticulars->deleted_at = date('Y-m-d h:i:s');
                        $recorddeleted = $existingParticulars->save();
                        if ($recorddeleted) {
                            $isValid = 'success';
                            $errorMsg = 'Record deleted successfully';
                        }
                    }
                }
            }
            $data = ['status' => $isValid, 'message' => $errorMsg];
            return json_encode($data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteRefrigerantsCombution(Request $request)
    {

        try {

            $isValid = 'error';
            $errorMsg = 'Invalid record found';
            $encryptedId = $request->selectedId;
            if (isset($encryptedId) && $encryptedId != '') {
                $decryptedId = Crypt::decrypt($encryptedId);
                if (isset($decryptedId) && $decryptedId != '') {
                    $existingParticulars = ScopeOneCombustion::where('id', $decryptedId)->first();
                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {
                        $existingParticulars->deleted_at = date('Y-m-d h:i:s');
                        $recorddeleted = $existingParticulars->save();
                        if ($recorddeleted) {
                            $isValid = 'success';
                            $errorMsg = 'Record deleted successfully';
                        }
                    }
                }
            }
            $data = ['status' => $isValid, 'message' => $errorMsg];
            return json_encode($data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function editFuel(Request $request)
    {
        try {
            $combustionEncryptId = $request->combustionId;
            $combustionId = $existingStationaryParticulars = '';
            if (isset($combustionEncryptId) && $combustionEncryptId != '') {
                $combustionId = Crypt::decrypt($combustionEncryptId);

                $existingStationaryParticulars = ScopeOneCombustion::where('id', $combustionId)->first();
                // print_r($existingStationaryParticulars); exit;
            }
            if (isset($combustionId) && $combustionId != '' && isset($existingStationaryParticulars->id) && $existingStationaryParticulars->id != '') {
                $randomId = $request->randomId;
                $unitOfMeasurements = $selectedMeasurement = [];
                $stationaryParticulars = StationaryCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
                $selfuelTypes = StationaryCombustion::select('quantity_type')->where('id', $existingStationaryParticulars->fuel_particular_id)->first();

                $fuelTypes = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $selfuelTypes->quantity_type)->pluck('unit_of_measurement', 'id')->toArray();
                // print_r($fuelTypes); exit;
                $seletedparticulars = StationaryCombustion::pluck('fuel_type', 'id')->toArray();
                if (isset($fuelTypes) && count($fuelTypes) > 0) {
                    foreach ($fuelTypes as $fuel) {
                        if (isset($unitOfMeasurements[$fuel]) && is_array($unitOfMeasurements[$fuel])) {
                            // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                        } else {
                            $unitOfMeasurements[$fuel] = [];
                        }
                        $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                    }
                }
                //print_r($existingMobileParticulars); exit;
                if (isset($seletedparticulars) && count($seletedparticulars) > 0) {
                    foreach ($seletedparticulars as $pfuel) {
                        if (isset($selectedfuelType[$pfuel]) && is_array($selectedfuelType[$pfuel])) {
                            // $unitOfMeasurements[$fuel] = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $fuel)->pluck('unit_of_measurement', 'id')->toArray();
                        } else {
                            $selectedfuelType[$pfuel] = [];
                        }
                        $selectedfuelType[$pfuel] = StationaryCombustion::select('id', 'fuel_type')->where('particulars', $pfuel)->pluck('fuel_type', 'id')->toArray();
                    }
                }
                return view('ghg.editFuel', compact('unitOfMeasurements', 'selfuelTypes', 'selectedMeasurement', 'stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes',  'randomId', 'combustionId'));
            } else {
                return "Error occurred";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }




    public function editmobileFuel(Request $request)
    {
        try {
            $combustionEncryptId = $request->combustionId;
            $combustionId = $existingStationaryParticulars = '';
            if (isset($combustionEncryptId) && $combustionEncryptId != '') {
                $combustionId = Crypt::decrypt($combustionEncryptId);

                $existingStationaryParticulars = ScopeOneCombustion::where('id', $combustionId)->first();
                // print_r($existingStationaryParticulars); exit;
            }
            if (isset($combustionId) && $combustionId != '' && isset($existingStationaryParticulars->id) && $existingStationaryParticulars->id != '') {
                $randomId = $request->randomId;
                $unitOfMeasurements = $selectedMeasurement = [];
                $stationaryParticulars = MobileCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
                $selfuelTypes = MobileCombustion::select('quantity_type')->where('id', $existingStationaryParticulars->fuel_particular_id)->first();

                $fuelTypes = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $selfuelTypes->quantity_type)->pluck('unit_of_measurement', 'id')->toArray();
                // print_r($fuelTypes); exit;

                return view('ghg.editmobileFuel', compact('unitOfMeasurements', 'selfuelTypes', 'selectedMeasurement', 'stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes',  'randomId', 'combustionId'));
            } else {
                return "Error occurred";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function editrefrigerantsFuel(Request $request)
    {
        try {
            $combustionEncryptId = $request->combustionId;
            $combustionId = $existingStationaryParticulars = '';
            if (isset($combustionEncryptId) && $combustionEncryptId != '') {
                $combustionId = Crypt::decrypt($combustionEncryptId);

                $existingStationaryParticulars = ScopeOneCombustion::where('id', $combustionId)->first();
                $selfuelTypes = StationaryCombustion::select('quantity_type')->where('id', $existingStationaryParticulars->fuel_particular_id)->first();

                $fuelTypes = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $selfuelTypes->quantity_type)->pluck('unit_of_measurement', 'id')->toArray();

                // print_r($existingStationaryParticulars); exit;
            }
            if (isset($combustionId) && $combustionId != '' && isset($existingStationaryParticulars->id) && $existingStationaryParticulars->id != '') {
                $randomId = $request->randomId;

                $unitOfMeasurements = $selectedMeasurement = [];
                $stationaryParticulars = RefrigerantsCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
                $selfuelTypes = RefrigerantsCombustion::select('quantity_type')->where('id', $existingStationaryParticulars->fuel_particular_id)->first();

                $fuelTypes = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', $selfuelTypes->quantity_type)->pluck('unit_of_measurement', 'id')->toArray();
                // print_r($fuelTypes); exit;



                return view('ghg.editRefrigerantsFuel', compact('selfuelTypes', 'fuelTypes', 'stationaryParticulars', 'existingStationaryParticulars',  'randomId', 'combustionId'));
            } else {
                return "Error occurred";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateCombustionInfo(Request $request)
    {
        try {

            $randomNum = $request->randomNum;
            // Log::info(print_r($request->all(), true));
            $validator = Validator::make($request->all(), [
                'randomNum' => 'required|string|max:250',
                'particularId' => 'required|string',
                'selectedFuel' => 'required|string|max:250',

                'region' => 'required|string|max:250',
                'unitOfMesurement' => 'required',
                'quantityActual' => 'required|numeric|max:10000',

            ]);

            $combustionId = $particularId = '';
            $combustionEncryptId = $request->combustionId;
            $particularIdEncryptId = $request->particularId;
            if (isset($particularIdEncryptId) && $particularIdEncryptId != '') {
                $particularId = Crypt::decrypt($particularIdEncryptId);
                $stationaryCombustionStandards = StationaryCombustion::find($particularId);

                if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {
                    // Log::info("Stationary Combution Saved Successfully!");

                } else {
                    $status = 'error';
                    $msg = 'Combustion rules not found';
                    $response = array("status" => $status, "message" => $msg);
                    return response()->json($response);
                }
            }

            if (isset($combustionEncryptId) && $combustionEncryptId != '') {
                $combustionId = Crypt::decrypt($combustionEncryptId);
                $ScopeOneCombustionNew = ScopeOneCombustion::where('id', $combustionId)->first();

                if (isset($ScopeOneCombustionNew->id) && $ScopeOneCombustionNew->id != '') {
                    // Log::info("Stationary Combution Saved Successfully!");

                } else {
                    $status = 'error';
                    $msg = 'Record not found';
                    $response = array("status" => $status, "message" => $msg);
                    return response()->json($response);
                }
            }

            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "message" => $messages);
                return response()->json($response);
            }
            $status = 'error';
            $msg = 'Something Went Wrong!';
            $lastInsertedId = '';
            $convertedActualQuantity = 0;

            Log::info("request->particularId => " . $request->particularId);
            if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {

                $convertedValue1 = $convertedFactorKj =  $conversionFactorKj = $convertedEmission = $totalEmission = '';
                $convertedUom = $convertionUnit = $ncv = $density = $needToBeConvertUom = $emission = $standard = '';

                if (isset($request->unitOfMesurement) && $request->unitOfMesurement != '' && is_numeric($request->unitOfMesurement)) {


                    $unitOfMesurementDetails = UnitsOfMeasurements::find($request->unitOfMesurement);
                    if (isset($unitOfMesurementDetails->id) && $unitOfMesurementDetails->id != '') {
                        // Log::info($unitOfMesurementDetails->uom_description);
                        $convertedActualQuantity = $request->quantityActual * $unitOfMesurementDetails->converted_value;
                    }
                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $ncv = $stationaryCombustionStandards->ncv_kj_kg;
                    if (isset($stationaryCombustionStandards->density) && $stationaryCombustionStandards->density != '') {
                        $density = $stationaryCombustionStandards->density;
                    } else {
                        $density = 0;
                    }
                    $needToBeConvertUom = $stationaryCombustionStandards->uom_factor_to_kj;
                    // $convertedFactorKj = $stationaryCombustionStandards->converted_qnty;
                    // $conversionFactorKj = $stationaryCombustionStandards->conversion_factor_to_kj;
                    $emission = $stationaryCombustionStandards->emission_factor_kj_tj;
                    $standard = $stationaryCombustionStandards->standard_uom;
                    $unitOfMesurementText = $request->unitOfMesurementText;


                    $convertedValue1 = round(($convertedActualQuantity * $convertionUnit), 2);
                    $conversionFactorKj = round(($ncv * $density), 2);
                    $convertedFactorKj = round(($convertedActualQuantity * $conversionFactorKj), 2);
                    $convertedEmission = number_format(($emission / 1000) / 1000000000, 10);

                    $totalEmission = number_format(($convertedEmission * $convertedFactorKj), 3);

                    // $ScopeOneCombustionNew = new ScopeOneCombustion();

                    if (isset($ScopeOneCombustionNew->id) && $ScopeOneCombustionNew->id != '') {
                        $ScopeOneCombustionNew->fuel_particular_id  = $particularId;
                        $ScopeOneCombustionNew->scope_type  = 1; //stationary 
                        $ScopeOneCombustionNew->scope_name  = "stationaryCombution"; //stationary 
                        $ScopeOneCombustionNew->fuel_particular  = $request->selectedFuel;
                        $ScopeOneCombustionNew->fuel_type = $ScopeOneCombustionNew->fuel_type;
                        $ScopeOneCombustionNew->region = $request->region;
                        $ScopeOneCombustionNew->input_uom = $request->unitOfMesurement;

                        $ScopeOneCombustionNew->actual_quantity = $request->quantityActual;
                        $ScopeOneCombustionNew->converted_actual_quantity = $convertedActualQuantity;

                        $ScopeOneCombustionNew->company_id = Auth::user()->company_id;
                        $ScopeOneCombustionNew->created_by_user_id = Auth::user()->id;

                        if (isset($convertedUom) && $convertedUom != '' && $convertedUom != NULL) {
                            $ScopeOneCombustionNew->converted_uom = $convertedUom;
                        } else {
                            $ScopeOneCombustionNew->converted_uom = $unitOfMesurementText;
                        }
                        $ScopeOneCombustionNew->conversion_unit = $convertionUnit;
                        $ScopeOneCombustionNew->converted_value1 = $convertedValue1;
                        $ScopeOneCombustionNew->ncv = $ncv;
                        $ScopeOneCombustionNew->density = $density;
                        if (isset($needToBeConvertUom) && $needToBeConvertUom != ''  && $needToBeConvertUom != NULL) {
                            $ScopeOneCombustionNew->uom = $needToBeConvertUom;
                        } else {
                            $ScopeOneCombustionNew->uom = $request->unitOfMesurement;
                        }
                        $ScopeOneCombustionNew->conversion_factor_kj = $conversionFactorKj;
                        $ScopeOneCombustionNew->converted_factor_kj = $convertedFactorKj;
                        $ScopeOneCombustionNew->emission = $emission;
                        $ScopeOneCombustionNew->converted_emission = $convertedEmission;
                        $ScopeOneCombustionNew->total_emission = $totalEmission;
                        $ScopeOneCombustionNew->standard = $standard;

                        $ScopeOneCombustionNew->save();
                        $lastInsertedId = $ScopeOneCombustionNew->id;
                    } else {
                        $msg = 'Record not found to update data';
                    }
                } else {
                    $msg = 'Invalid unit of measurements';
                }
            } else {
                $msg = 'Invalid fuel particular found';
            }
            if (isset($lastInsertedId) && $lastInsertedId != '') {
                // Log::info("Stationary Combution Saved Successfully!");
                $status = 'success';
                $msg = 'Stationary Combution Updated Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateMobileCombustionInfo(Request $request)
    {
        try {

            $randomNum = $request->randomNum;
            // Log::info(print_r($request->all(), true));
            $validator = Validator::make($request->all(), [
                'randomNum' => 'required|string|max:250',
                'particularId' => 'required|string',
                'selectedFuel' => 'required|string|max:250',

                'region' => 'required|string|max:250',
                'unitOfMesurement' => 'required',
                'quantityActual' => 'required|numeric|max:10000',

            ]);

            $combustionId = $particularId = '';
            $combustionEncryptId = $request->combustionId;
            $particularIdEncryptId = $request->particularId;
            if (isset($particularIdEncryptId) && $particularIdEncryptId != '') {
                $particularId = Crypt::decrypt($particularIdEncryptId);
                $stationaryCombustionStandards = MobileCombustion::find($particularId);

                if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {
                    // Log::info("Stationary Combution Saved Successfully!");

                } else {
                    $status = 'error';
                    $msg = 'Combustion rules not found';
                    $response = array("status" => $status, "message" => $msg);
                    return response()->json($response);
                }
            }

            if (isset($combustionEncryptId) && $combustionEncryptId != '') {
                $combustionId = Crypt::decrypt($combustionEncryptId);
                $ScopeOneCombustionNew = ScopeOneCombustion::where('id', $combustionId)->first();

                if (isset($ScopeOneCombustionNew->id) && $ScopeOneCombustionNew->id != '') {
                    // Log::info("Stationary Combution Saved Successfully!");

                } else {
                    $status = 'error';
                    $msg = 'Record not found';
                    $response = array("status" => $status, "message" => $msg);
                    return response()->json($response);
                }
            }

            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "message" => $messages);
                return response()->json($response);
            }
            $status = 'error';
            $msg = 'Something Went Wrong!';
            $lastInsertedId = '';
            $convertedActualQuantity = 0;

            Log::info("request->particularId => " . $request->particularId);
            if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {

                $convertedValue1 = $convertedFactorKj =  $conversionFactorKj = $convertedEmission = $totalEmission = '';
                $convertedUom = $convertionUnit = $ncv = $density = $needToBeConvertUom = $emission = $standard = '';

                if (isset($request->unitOfMesurement) && $request->unitOfMesurement != '' && is_numeric($request->unitOfMesurement)) {


                    $unitOfMesurementDetails = UnitsOfMeasurements::find($request->unitOfMesurement);
                    if (isset($unitOfMesurementDetails->id) && $unitOfMesurementDetails->id != '') {
                        // Log::info($unitOfMesurementDetails->uom_description);
                        $convertedActualQuantity = $request->quantityActual * $unitOfMesurementDetails->converted_value;
                    }
                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $ncv = $stationaryCombustionStandards->ncv_kj_kg;
                    if (isset($stationaryCombustionStandards->density) && $stationaryCombustionStandards->density != '') {
                        $density = $stationaryCombustionStandards->density;
                    } else {
                        $density = 0;
                    }
                    $needToBeConvertUom = $stationaryCombustionStandards->uom_factor_to_kj;
                    // $convertedFactorKj = $stationaryCombustionStandards->converted_qnty;
                    // $conversionFactorKj = $stationaryCombustionStandards->conversion_factor_to_kj;
                    $emission = $stationaryCombustionStandards->emission_factor_kj_tj;
                    $standard = $stationaryCombustionStandards->standard_uom;
                    $unitOfMesurementText = $request->unitOfMesurementText;


                    $convertedValue1 = round(($convertedActualQuantity * $convertionUnit), 2);
                    $conversionFactorKj = round(($ncv * $density), 2);
                    $convertedFactorKj = round(($convertedActualQuantity * $conversionFactorKj), 2);
                    $convertedEmission = number_format(($emission / 1000) / 1000000000, 10);

                    $totalEmission = number_format(($convertedEmission * $convertedFactorKj), 3);

                    // $ScopeOneCombustionNew = new ScopeOneCombustion();

                    if (isset($ScopeOneCombustionNew->id) && $ScopeOneCombustionNew->id != '') {
                        $ScopeOneCombustionNew->fuel_particular_id  = $particularId;
                        $ScopeOneCombustionNew->scope_type  = 1; //stationary 
                        $ScopeOneCombustionNew->scope_name  = "mobileCombution"; //stationary 
                        $ScopeOneCombustionNew->fuel_particular  = $request->selectedFuel;
                        $ScopeOneCombustionNew->fuel_type = $ScopeOneCombustionNew->fuel_type;
                        $ScopeOneCombustionNew->region = $request->region;
                        $ScopeOneCombustionNew->input_uom = $request->unitOfMesurement;

                        $ScopeOneCombustionNew->actual_quantity = $request->quantityActual;
                        $ScopeOneCombustionNew->converted_actual_quantity = $convertedActualQuantity;

                        $ScopeOneCombustionNew->company_id = Auth::user()->company_id;
                        $ScopeOneCombustionNew->created_by_user_id = Auth::user()->id;

                        if (isset($convertedUom) && $convertedUom != '' && $convertedUom != NULL) {
                            $ScopeOneCombustionNew->converted_uom = $convertedUom;
                        } else {
                            $ScopeOneCombustionNew->converted_uom = $unitOfMesurementText;
                        }
                        $ScopeOneCombustionNew->conversion_unit = $convertionUnit;
                        $ScopeOneCombustionNew->converted_value1 = $convertedValue1;
                        $ScopeOneCombustionNew->ncv = $ncv;
                        $ScopeOneCombustionNew->density = $density;
                        if (isset($needToBeConvertUom) && $needToBeConvertUom != ''  && $needToBeConvertUom != NULL) {
                            $ScopeOneCombustionNew->uom = $needToBeConvertUom;
                        } else {
                            $ScopeOneCombustionNew->uom = $request->unitOfMesurement;
                        }
                        $ScopeOneCombustionNew->conversion_factor_kj = $conversionFactorKj;
                        $ScopeOneCombustionNew->converted_factor_kj = $convertedFactorKj;
                        $ScopeOneCombustionNew->emission = $emission;
                        $ScopeOneCombustionNew->converted_emission = $convertedEmission;
                        $ScopeOneCombustionNew->total_emission = $totalEmission;
                        $ScopeOneCombustionNew->standard = $standard;

                        $ScopeOneCombustionNew->save();
                        $lastInsertedId = $ScopeOneCombustionNew->id;
                    } else {
                        $msg = 'Record not found to update data';
                    }
                } else {
                    $msg = 'Invalid unit of measurements';
                }
            } else {
                $msg = 'Invalid fuel particular found';
            }
            if (isset($lastInsertedId) && $lastInsertedId != '') {
                // Log::info("Stationary Combution Saved Successfully!");
                $status = 'success';
                $msg = 'Mobile Combution Updated Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateRefrigerantsCombustionInfo(Request $request)
    {
        try {

            $randomNum = $request->randomNum;
            // Log::info(print_r($request->all(), true));
            $validator = Validator::make($request->all(), [
                'randomNum' => 'required|string|max:250',
                'particularId' => 'required|string',
                'selectedFuel' => 'required|string|max:250',

                'region' => 'required|string|max:250',
                'unitOfMesurement' => 'required',
                'quantityActual' => 'required|numeric|max:10000',

            ]);

            $combustionId = $particularId = '';
            $combustionEncryptId = $request->combustionId;
            $particularIdEncryptId = $request->particularId;
            if (isset($particularIdEncryptId) && $particularIdEncryptId != '') {
                $particularId = Crypt::decrypt($particularIdEncryptId);
                $stationaryCombustionStandards = RefrigerantsCombustion::find($particularId);

                if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {
                    // Log::info("Stationary Combution Saved Successfully!");

                } else {
                    $status = 'error';
                    $msg = 'Combustion rules not found';
                    $response = array("status" => $status, "message" => $msg);
                    return response()->json($response);
                }
            }

            if (isset($combustionEncryptId) && $combustionEncryptId != '') {
                $combustionId = Crypt::decrypt($combustionEncryptId);
                $ScopeOneCombustionNew = ScopeOneCombustion::where('id', $combustionId)->first();

                if (isset($ScopeOneCombustionNew->id) && $ScopeOneCombustionNew->id != '') {
                    // Log::info("Stationary Combution Saved Successfully!");

                } else {
                    $status = 'error';
                    $msg = 'Record not found';
                    $response = array("status" => $status, "message" => $msg);
                    return response()->json($response);
                }
            }

            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "message" => $messages);
                return response()->json($response);
            }
            $status = 'error';
            $msg = 'Something Went Wrong!';
            $lastInsertedId = '';
            $convertedActualQuantity = 0;

            Log::info("request->particularId => " . $request->particularId);
            if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {

                $convertedValue1 = $convertedFactorKj =  $conversionFactorKj = $convertedEmission = $totalEmission = '';
                $convertedUom = $convertionUnit = $ncv = $density = $needToBeConvertUom = $emission = $standard = '';

                if (isset($request->unitOfMesurement) && $request->unitOfMesurement != '' && is_numeric($request->unitOfMesurement)) {


                    $unitOfMesurementDetails = UnitsOfMeasurements::find($request->unitOfMesurement);

                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $global_warming = $stationaryCombustionStandards->global_warming;


                    $standard = $stationaryCombustionStandards->standard_uom;


                    $convertedValue1 = $request->quantityActual * $convertionUnit;

                    $totalEmission = $global_warming * $convertedValue1;

                    $ScopeOneCombustionNew->fuel_particular_id  = $particularId;
                    $ScopeOneCombustionNew->scope_type  = 1; //refrigerants
                    $ScopeOneCombustionNew->scope_name  = "refrigerantsCombution";
                    $ScopeOneCombustionNew->fuel_particular  = $request->selectedFuel;
                    $ScopeOneCombustionNew->fuel_type = $stationaryCombustionStandards->fuel_type;
                    $ScopeOneCombustionNew->region = $request->region;
                    $ScopeOneCombustionNew->input_uom = $request->unitOfMesurement;

                    $ScopeOneCombustionNew->actual_quantity = $request->quantityActual;

                    $ScopeOneCombustionNew->company_id = Auth::user()->company_id;
                    $ScopeOneCombustionNew->created_by_user_id = Auth::user()->id;

                    if (isset($convertedUom) && $convertedUom != '' && $convertedUom != NULL) {
                        $ScopeOneCombustionNew->converted_uom = $convertedUom;
                    } else {
                        $ScopeOneCombustionNew->converted_uom = $unitOfMesurementText;
                    }
                    $ScopeOneCombustionNew->conversion_unit = $convertionUnit;
                    $ScopeOneCombustionNew->converted_value1 = $convertedValue1;
                    $ScopeOneCombustionNew->uom = $request->unitOfMesurement;

                    $ScopeOneCombustionNew->total_emission = $totalEmission;
                    $ScopeOneCombustionNew->standard = $stationaryCombustionStandards->standard;

                    $ScopeOneCombustionNew->save();
                    $lastInsertedId = $ScopeOneCombustionNew->id;
                } else {
                    $msg = 'Invalid unit of measurements';
                }
            } else {
                $msg = 'Invalid fuel particular found';
            }
            if (isset($lastInsertedId) && $lastInsertedId != '') {
                // Log::info("Stationary Combution Saved Successfully!");
                $status = 'success';
                $msg = 'Mobile Combution Updated Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
