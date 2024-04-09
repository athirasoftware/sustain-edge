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
use App\Models\PurchaseofElectricityCombustion;
use App\Models\UnitsOfMeasurements;
use App\Models\ScopeOneCombustion;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class GHGScopeTwoController extends Controller
{
    protected $electricityStandardUoM;
    protected $standard_uom;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Administrator|Team Lead|Employee');
        $this->electricityStandardUoM = array('kWh'=>  0.001, 'BTU' => 0.001);
        $this->standard_uom = 'tCO2';
    }


    public function getElectricityofEvs(Request $request)
    {

        try {

            $unitOfMeasurements = [];
            //  $existingStationaryParticulars = ScopeOneCombustion::where('scope_name',"purchaseofElectricity")->get();
            $existingStationaryParticulars = ScopeOneCombustion::where('scope_name', "purchaseofElectricity")
                ->where('fuel_particular', 'Electricity of Evs')->where('created_by_user_id', Auth::user()->id)->get();

            $stationaryParticulars = PurchaseofElectricityCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
            $fuelTypes = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', "Energy")->pluck('unit_of_measurement', 'id')->toArray();
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
            return view('ghg.ElectricityofEvsquestionnaire', compact('stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveElectricityofEvs(Request $request)
    {

        try {
            $randomNum = $request->randomNum;
            // Log::info(print_r($request->all(), true));
            $validator = Validator::make($request->all(), [
                'randomNum' => 'required|string|max:250',
                'particularId' => 'required|numeric',
                'selectedFuel' => 'required|string|max:250',

                'region' => 'required|string|max:250',
                'unitOfMesurement' => 'required|string|max:250',
                'quantityActual' => 'required|numeric|max:10000',

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

            $stationaryCombustionStandards = PurchaseofElectricityCombustion::find($request->particularId);

            Log::info("request->particularId => " . $request->particularId);
            if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {

                $convertedValue1 = $convertedFactorKj =  $conversionFactorKj = $convertedEmission = $totalEmission = '';
                $convertedUom = $convertionUnit = $ncv = $density = $needToBeConvertUom = $emission = $standard = '';

                if (isset($request->unitOfMesurement) && $request->unitOfMesurement != '') {


                    $unitOfMesurementDetails = UnitsOfMeasurements::find($request->unitOfMesurement);


                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $convertedActualQuantity = $request->quantityActual * $convertionUnit;
                    //  $ncv = $stationaryCombustionStandards->ncv_kj_kg;

                    // $needToBeConvertUom = $stationaryCombustionStandards->uom_factor_to_kj;
                    // $convertedFactorKj = $stationaryCombustionStandards->converted_qnty;
                    // $conversionFactorKj = $stationaryCombustionStandards->conversion_factor_to_kj;
                    $emission = $stationaryCombustionStandards->emission_factor_kj_tj;
                    $standard = $stationaryCombustionStandards->standard_uom;
                    $unitOfMesurementText = $request->unitOfMesurementText;



                    // echo $convertedValue1; exit;
                    //$conversionFactorKj = round(($ncv * $density), 2);


                    $totalEmission = $emission * $convertedActualQuantity;

                    $ScopeOneCombustionNew = new ScopeOneCombustion();
                    $ScopeOneCombustionNew->fuel_particular_id  = $request->particularId;
                    $ScopeOneCombustionNew->scope_type  = 2; //stationary 
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

                    // $ScopeOneCombustionNew->ncv = $ncv;


                    $ScopeOneCombustionNew->emission = $emission;

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
                $msg = 'Electricity of Evs Saved Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRefreshElectricityofEvs(Request $request)
    {
        try {
            $unitOfMeasurements = [];
            $wherePurchaseElectricity = array('scope_name' => "purchaseofElectricity", 'fuel_particular' => 'Electricity of Evs', 'created_by_user_id' => Auth::user()->id);
            $existingStationaryParticulars = ScopeOneCombustion::where($wherePurchaseElectricity)->get();
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
            return view('ghg.ElectricityofEvsquestionnaire', compact('stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function deleteElectricityofEvs(Request $request)
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

    public function editElectricityofEvs(Request $request)
    {
        try {
            $combustionEncryptId = $request->combustionId;
            $combustionId = $existingStationaryParticulars = '';
            if (isset($combustionEncryptId) && $combustionEncryptId != '') {
                $combustionId = Crypt::decrypt($combustionEncryptId);
                $existingStationaryParticulars = ScopeOneCombustion::where('id', $combustionId)->first();
            }
            if (isset($combustionId) && $combustionId != '' && isset($existingStationaryParticulars->id) && $existingStationaryParticulars->id != '') {
                $randomId = $request->randomId;
                $unitOfMeasurements = $selectedMeasurement = [];
                $stationaryParticulars = StationaryCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
                $fuelTypes = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', "Energy")->pluck('unit_of_measurement', 'id')->toArray();
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

                return view('ghg.editFuelPurchaseofelectricity', compact('stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes', 'unitOfMeasurements', 'randomId', 'combustionId', 'selectedMeasurement'));
            } else {
                return "Error occurred";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateElectricityofEvs(Request $request)
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
            $particularId = Crypt::decrypt($particularIdEncryptId);
            $stationaryCombustionStandards = ScopeOneCombustion::find($particularId);
            // if (isset($particularIdEncryptId) && $particularIdEncryptId != '') {

            //     if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {
            //         // Log::info("Stationary Combution Saved Successfully!");

            //     } else {
            //         $status = 'error';
            //         $msg = 'Combustion rules not found';
            //         $response = array("status" => $status, "message" => $msg);
            //         return response()->json($response);
            //     }
            // }

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
                    $convertionUnit = $this->electricityStandardUoM[$request->unitOfMesurementText];
                    $convertedActualQuantity = $request->quantityActual * $convertionUnit;
                    //  $ncv = $stationaryCombustionStandards->ncv_kj_kg;

                    // $needToBeConvertUom = $stationaryCombustionStandards->uom_factor_to_kj;
                    // $convertedFactorKj = $stationaryCombustionStandards->converted_qnty;
                    // $conversionFactorKj = $stationaryCombustionStandards->conversion_factor_to_kj;
                    $emission =   0.79;
                    $standard = $this->standard_uom;
                    $unitOfMesurementText = $request->unitOfMesurementText;



                    // echo $convertedValue1; exit;
                    //$conversionFactorKj = round(($ncv * $density), 2);


                    $totalEmission = $emission * $convertedActualQuantity;


                    $ScopeOneCombustionNew->fuel_particular_id  = $combustionId;
                    $ScopeOneCombustionNew->scope_type  = 2; //stationary 
                    $ScopeOneCombustionNew->scope_name  = "purchaseofElectricity";
                    $ScopeOneCombustionNew->fuel_particular  = 'Electricity of Evs';$request->selectedFuel;
                    $ScopeOneCombustionNew->fuel_type = "Length";
                    $ScopeOneCombustionNew->region = $request->region;
                    $ScopeOneCombustionNew->input_uom = $request->unitOfMesurement;

                    $ScopeOneCombustionNew->actual_quantity = $request->quantityActual;
                    $ScopeOneCombustionNew->converted_actual_quantity = $convertedActualQuantity;
                    //$ScopeOneCombustionNew->start_date = date('Y-m-d', strtotime($request->startDate));
                    // $ScopeOneCombustionNew->end_date = date('Y-m-d', strtotime($request->endDate));
                    $ScopeOneCombustionNew->company_id = Auth::user()->company_id;
                    $ScopeOneCombustionNew->created_by_user_id = Auth::user()->id;

                    // if (isset($convertedUom) && $convertedUom != '' && $convertedUom != NULL) {
                    //     $ScopeOneCombustionNew->converted_uom = $convertedUom;
                    // } else {
                    //     $ScopeOneCombustionNew->converted_uom = $unitOfMesurementText;
                    // }
                    $ScopeOneCombustionNew->converted_uom = $unitOfMesurementText;
                    $ScopeOneCombustionNew->conversion_unit = $convertionUnit;

                    // $ScopeOneCombustionNew->ncv = $ncv;


                    $ScopeOneCombustionNew->emission = $emission;

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

            if (isset($lastInsertedId) && $lastInsertedId != '') {
                // Log::info("Stationary Combution Saved Successfully!");
                $status = 'success';
                $msg = 'Electricity of Evs Updated Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function getElectricityPurchased(Request $request)
    {

        try {

            $unitOfMeasurements = [];
            //  $existingStationaryParticulars = ScopeOneCombustion::where('scope_name',"purchaseofElectricity")->get();
            $existingStationaryParticulars = ScopeOneCombustion::where('scope_name', "purchaseofElectricity")->where('fuel_particular', 'Electricity purchased')->where('created_by_user_id', Auth::user()->id)->get();

            $stationaryParticulars = PurchaseofElectricityCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
            $fuelTypes = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', "Energy")->pluck('unit_of_measurement', 'id')->toArray();
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
            return view('ghg.ElectricityPurchasedquestionnaire', compact('stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveElectricityPurchased(Request $request)
    {

        try {
            $randomNum = $request->randomNum;
            // Log::info(print_r($request->all(), true));
            $validator = Validator::make($request->all(), [
                'randomNum' => 'required|string|max:250',
                'particularId' => 'required|numeric',
                'selectedFuel' => 'required|string|max:250',

                'region' => 'required|string|max:250',
                'unitOfMesurement' => 'required|string|max:250',
                'quantityActual' => 'required|numeric|max:10000',

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

            $stationaryCombustionStandards = PurchaseofElectricityCombustion::find($request->particularId);

            Log::info("request->particularId => " . $request->particularId);
            if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {

                $convertedValue1 = $convertedFactorKj =  $conversionFactorKj = $convertedEmission = $totalEmission = '';
                $convertedUom = $convertionUnit = $ncv = $density = $needToBeConvertUom = $emission = $standard = '';

                if (isset($request->unitOfMesurement) && $request->unitOfMesurement != '') {


                    $unitOfMesurementDetails = UnitsOfMeasurements::find($request->unitOfMesurement);


                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $convertedActualQuantity = $request->quantityActual * $convertionUnit;
                    //  $ncv = $stationaryCombustionStandards->ncv_kj_kg;

                    // $needToBeConvertUom = $stationaryCombustionStandards->uom_factor_to_kj;
                    // $convertedFactorKj = $stationaryCombustionStandards->converted_qnty;
                    // $conversionFactorKj = $stationaryCombustionStandards->conversion_factor_to_kj;
                    $emission = $stationaryCombustionStandards->emission_factor_kj_tj;
                    $standard = $stationaryCombustionStandards->standard_uom;
                    $unitOfMesurementText = $request->unitOfMesurementText;



                    // echo $convertedValue1; exit;
                    //$conversionFactorKj = round(($ncv * $density), 2);


                    $totalEmission = $emission * $convertedActualQuantity;

                    $ScopeOneCombustionNew = new ScopeOneCombustion();
                    $ScopeOneCombustionNew->fuel_particular_id  = $request->particularId;
                    $ScopeOneCombustionNew->scope_type  = 2; //stationary 
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

                    // $ScopeOneCombustionNew->ncv = $ncv;


                    $ScopeOneCombustionNew->emission = $emission;

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
                $msg = 'Electricity Purchased Saved Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRefreshElectricityPurchased(Request $request)
    {
        try {
            $unitOfMeasurements = [];
            $existingStationaryParticulars = ScopeOneCombustion::where('scope_name', "purchaseofElectricity")->get();
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
            return view('ghg.ElectricityPurchasedquestionnaire', compact('stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function deleteElectricityPurchased(Request $request)
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

    public function editElectricityPurchased(Request $request)
    {
        try {
            $combustionEncryptId = $request->combustionId;
            $combustionId = $existingStationaryParticulars = '';
            if (isset($combustionEncryptId) && $combustionEncryptId != '') {
                $combustionId = Crypt::decrypt($combustionEncryptId);
                $existingStationaryParticulars = ScopeOneCombustion::where('id', $combustionId)->first();
            }
            if (isset($combustionId) && $combustionId != '' && isset($existingStationaryParticulars->id) && $existingStationaryParticulars->id != '') {
                $randomId = $request->randomId;
                $unitOfMeasurements = $selectedMeasurement = [];
                $stationaryParticulars = StationaryCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
                $fuelTypes = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', "Energy")->pluck('unit_of_measurement', 'id')->toArray();
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

                return view('ghg.editElectricityPurchased', compact('stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes', 'unitOfMeasurements', 'randomId', 'combustionId', 'selectedMeasurement'));
            } else {
                return "Error occurred";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateElectricityPurchased(Request $request)
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


                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $convertedActualQuantity = $request->quantityActual * $convertionUnit;
                    //  $ncv = $stationaryCombustionStandards->ncv_kj_kg;

                    // $needToBeConvertUom = $stationaryCombustionStandards->uom_factor_to_kj;
                    // $convertedFactorKj = $stationaryCombustionStandards->converted_qnty;
                    // $conversionFactorKj = $stationaryCombustionStandards->conversion_factor_to_kj;
                    $emission = $stationaryCombustionStandards->emission_factor_kj_tj;
                    $standard = $stationaryCombustionStandards->standard_uom;
                    $unitOfMesurementText = $request->unitOfMesurementText;



                    // echo $convertedValue1; exit;
                    //$conversionFactorKj = round(($ncv * $density), 2);


                    $totalEmission = $emission * $convertedActualQuantity;


                    $ScopeOneCombustionNew->fuel_particular_id  = $combustionId;
                    $ScopeOneCombustionNew->scope_type  = 2; //stationary 
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

                    // $ScopeOneCombustionNew->ncv = $ncv;


                    $ScopeOneCombustionNew->emission = $emission;

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

            if (isset($lastInsertedId) && $lastInsertedId != '') {
                // Log::info("Stationary Combution Saved Successfully!");
                $status = 'success';
                $msg = 'Electricity Purchased Updated Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }




    public function getElectricityofHeat(Request $request)
    {

        try {

            $unitOfMeasurements = [];
            //  $existingStationaryParticulars = ScopeOneCombustion::where('scope_name',"purchaseofElectricity")->get();
            $existingStationaryParticulars = ScopeOneCombustion::where('scope_name', "purchaseofElectricity")->where('fuel_particular', 'Purchase of heat/steam/cold')->where('created_by_user_id', Auth::user()->id)->get();

            $stationaryParticulars = PurchaseofElectricityCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
            $fuelTypes = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', "Energy")->pluck('unit_of_measurement', 'id')->toArray();
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
            return view('ghg.ElectricityofHeatquestionnaire', compact('stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveElectricityofHeat(Request $request)
    {

        try {
            $randomNum = $request->randomNum;
            // Log::info(print_r($request->all(), true));
            $validator = Validator::make($request->all(), [
                'randomNum' => 'required|string|max:250',
                'particularId' => 'required|numeric',
                'selectedFuel' => 'required|string|max:250',

                'region' => 'required|string|max:250',
                'unitOfMesurement' => 'required|string|max:250',
                'quantityActual' => 'required|numeric|max:10000',

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

            $stationaryCombustionStandards = PurchaseofElectricityCombustion::find($request->particularId);

            Log::info("request->particularId => " . $request->particularId);
            if (isset($stationaryCombustionStandards->id) && $stationaryCombustionStandards->id != '') {

                $convertedValue1 = $convertedFactorKj =  $conversionFactorKj = $convertedEmission = $totalEmission = '';
                $convertedUom = $convertionUnit = $ncv = $density = $needToBeConvertUom = $emission = $standard = '';

                if (isset($request->unitOfMesurement) && $request->unitOfMesurement != '') {


                    $unitOfMesurementDetails = UnitsOfMeasurements::find($request->unitOfMesurement);


                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $convertedActualQuantity = $request->quantityActual * $convertionUnit;
                    //  $ncv = $stationaryCombustionStandards->ncv_kj_kg;

                    // $needToBeConvertUom = $stationaryCombustionStandards->uom_factor_to_kj;
                    // $convertedFactorKj = $stationaryCombustionStandards->converted_qnty;
                    // $conversionFactorKj = $stationaryCombustionStandards->conversion_factor_to_kj;
                    $emission = $stationaryCombustionStandards->emission_factor_kj_tj;
                    $standard = $stationaryCombustionStandards->standard_uom;
                    $unitOfMesurementText = $request->unitOfMesurementText;



                    // echo $convertedValue1; exit;
                    //$conversionFactorKj = round(($ncv * $density), 2);


                    $totalEmission = $emission * $convertedActualQuantity;

                    $ScopeOneCombustionNew = new ScopeOneCombustion();
                    $ScopeOneCombustionNew->fuel_particular_id  = $request->particularId;
                    $ScopeOneCombustionNew->scope_type  = 2; //stationary 
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

                    // $ScopeOneCombustionNew->ncv = $ncv;


                    $ScopeOneCombustionNew->emission = $emission;

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
                $msg = 'Purchase of heat/steam/cold Saved Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRefreshElectricityofHeat(Request $request)
    {
        try {
            $unitOfMeasurements = [];
            $existingStationaryParticulars = ScopeOneCombustion::where('scope_name', "purchaseofElectricity")->get();
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
            return view('ghg.ElectricityofHeatquestionnaire', compact('stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes', 'unitOfMeasurements'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function deleteElectricityofHeat(Request $request)
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

    public function editElectricityofHeat(Request $request)
    {
        try {
            $combustionEncryptId = $request->combustionId;
            $combustionId = $existingStationaryParticulars = '';
            if (isset($combustionEncryptId) && $combustionEncryptId != '') {
                $combustionId = Crypt::decrypt($combustionEncryptId);
                $existingStationaryParticulars = ScopeOneCombustion::where('id', $combustionId)->first();
            }
            if (isset($combustionId) && $combustionId != '' && isset($existingStationaryParticulars->id) && $existingStationaryParticulars->id != '') {
                $randomId = $request->randomId;
                $unitOfMeasurements = $selectedMeasurement = [];
                $stationaryParticulars = StationaryCombustion::select('particulars', 'id')->groupBy('particulars', 'id')->pluck('particulars', 'id')->toArray();
                $fuelTypes = UnitsOfMeasurements::select('id', 'unit_of_measurement')->where('quantity_type', "Energy")->pluck('unit_of_measurement', 'id')->toArray();
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

                return view('ghg.editElectricityofHeat', compact('stationaryParticulars', 'existingStationaryParticulars', 'fuelTypes', 'unitOfMeasurements', 'randomId', 'combustionId', 'selectedMeasurement'));
            } else {
                return "Error occurred";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateElectricityofHeat(Request $request)
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


                    $convertedUom = $stationaryCombustionStandards->converted_uom;
                    $convertionUnit = $stationaryCombustionStandards->conversion_factor_to_s_uom;
                    $convertedActualQuantity = $request->quantityActual * $convertionUnit;
                    //  $ncv = $stationaryCombustionStandards->ncv_kj_kg;

                    // $needToBeConvertUom = $stationaryCombustionStandards->uom_factor_to_kj;
                    // $convertedFactorKj = $stationaryCombustionStandards->converted_qnty;
                    // $conversionFactorKj = $stationaryCombustionStandards->conversion_factor_to_kj;
                    $emission = $stationaryCombustionStandards->emission_factor_kj_tj;
                    $standard = $stationaryCombustionStandards->standard_uom;
                    $unitOfMesurementText = $request->unitOfMesurementText;



                    // echo $convertedValue1; exit;
                    //$conversionFactorKj = round(($ncv * $density), 2);


                    $totalEmission = $emission * $convertedActualQuantity;


                    $ScopeOneCombustionNew->fuel_particular_id  = $combustionId;
                    $ScopeOneCombustionNew->scope_type  = 2; //stationary 
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

                    // $ScopeOneCombustionNew->ncv = $ncv;


                    $ScopeOneCombustionNew->emission = $emission;

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

            if (isset($lastInsertedId) && $lastInsertedId != '') {
                // Log::info("Stationary Combution Saved Successfully!");
                $status = 'success';
                $msg = 'Purchase of heat/steam/cold Updated Successfully!';
            }
            $response = array("status" => $status, "message" => $msg);
            return response()->json($response);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
