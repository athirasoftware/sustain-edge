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

use App\Models\UnitsOfMeasurements;

use App\Models\ScopeOneCombustion;

use App\Models\PurchaseGoodsAndService;

use App\Models\CapitalGoods;

use App\Models\WasteManagement;

use App\Models\BusinessTravel;

use App\Models\EmployeeCommute;

use App\Models\DownStream;

use App\Models\TransportMode;

use App\Models\TransportType;

use App\Models\Soldproducts;

use Illuminate\Support\Facades\Hash;

use Validator;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\File;

use Session;



class GHGScopeThreeController extends Controller

{

    protected $purchaseGoodNServiceEmmissionFactor;

    protected $capitalEmmissionFactor;

    public function __construct()

    {

        $this->middleware('auth');

        $this->middleware('role:Administrator|Team Lead|Employee');

        $this->purchaseGoodNServiceEmmissionFactor = array('Kg' => 0.15, 'Tonnes' => 0.25);

        $this->capitalEmmissionFactor = array('Kg' => 0.15, 'Tonnes' => 0.25);
    }



    public function purchasedgoodsandservices(Request $request)

    {

        try {

            // $financialYearVal = $request->financialYearVal;

            $financialYearVal =  session::get('financialYearVal');

            $query = PurchaseGoodsAndService::select('id', 'purchase_item', 'pur_suplier_info', 'pur_suplier_gst', 'pur_quantity', 'pur_uom',);

            $savedPurchasedItems = $query->where('pur_status', 1)->where('created_by', Auth::user()->id)->get()->toArray();



            Log::info("financialYearVal => " . $financialYearVal);

            return view('scopethree.purchaseofgoodsandservice', compact('financialYearVal', 'savedPurchasedItems'));
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function savePurchaseGoods(Request $request)

    {

        try {

            /*

        "purchase_good_service_item": purchase_good_service_item,

        "supplier_vendor": supplier_vendor,

        "supplier_vendor_info": supplier_vendor_info,

        "quantity": quantity,

        "uom": uom,

        */

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'purchase_good_service_item' => 'required|string|max:250',

                'supplier_vendor' => 'required|string|max:250',

                //'supplier_vendor_info' => 'required|string|max:250',

                'quantity' => 'required|numeric|max:10000',

                'uom' => 'required|string|max:250'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->id) && $request->id != '') {

                $userId = Crypt::decrypt($request->id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            if ($request->uom == 'Kg') {

                $qty = 1;

                $emission_factor = 0.15;
            } else if ($request->uom == 'Tonnes') {

                $qty = 1000;

                $emission_factor = 0.25;
            }

            $converted_val = $request->quantity * $qty;

            DB::beginTransaction();

            try {

                $latestUser = new PurchaseGoodsAndService();

                $latestUser->purchase_item =  $request->purchase_good_service_item;

                $latestUser->pur_suplier_info =  $request->supplier_vendor;

                $latestUser->pur_suplier_gst =  $request->supplier_vendor_info;

                $latestUser->pur_quantity =  $request->quantity;

                $latestUser->pur_uom =  $request->uom;

                $latestUser->pur_converted_val =  $converted_val;

                $latestUser->pur_emission_factor =  $emission_factor;

                $latestUser->pur_total_emissions =  ($emission_factor * $converted_val) / 1000;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->updated_by =  Auth::user()->id;

                $latestUser->pur_status =  1;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Saved Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    public function editPurchaseGoods(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $result = [];

            $encryptedId = $request->purchase_id;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = PurchaseGoodsAndService::find($decryptedId);

                    $existingParticulars->encrptid = Crypt::encrypt($existingParticulars->id);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        //$recorddeleted = CapitalGoods::find($existingParticulars->id)->get()->toArray();

                        if ($existingParticulars) {

                            $isValid = 'success';

                            $errorMsg = 'Record deleted successfully';

                            $result = $existingParticulars;
                        }
                    }
                }
            }

            $data = ['status' => $isValid, 'message' => $errorMsg, 'result' => $result];

            return json_encode($data);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function deletePurchaseGoods(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $encryptedId = $request->selectedId;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = PurchaseGoodsAndService::find($decryptedId);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        $recorddeleted = PurchaseGoodsAndService::find($existingParticulars->id)->delete();

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

    public function updatePurchaseGoods(Request $request)

    {

        try {

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'purchase_good_service_item' => 'required|string|max:250',

                'supplier_vendor' => 'required|string|max:250',

                'supplier_vendor_info' => 'required|string|max:250',

                'quantity' => 'required|numeric|max:10000',

                'uom' => 'required|string|max:250'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->purchase_good_service_item_Id) && $request->purchase_good_service_item_Id != '') {

                $userId = Crypt::decrypt($request->purchase_good_service_item_Id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            if ($request->uom == 'Kg') {

                $qty = 1;
            } else if ($request->uom == 'Tonnes') {

                $qty = 1000;
            }

            $converted_val = $request->quantity * $qty;

            $emission_factor = $this->purchaseGoodNServiceEmmissionFactor[$request->uom];

            $total_emissions = ($emission_factor * $converted_val) / 1000;

            //$update = \DB::table('student') ->where('id', $data['id']) ->limit(1) 

            //->update( [ 'name' => $data['name'], 'address' => $data['address'], 'email' => $data['email'], 'contactno' => $data['contactno'] ]); 

            DB::beginTransaction();

            try {

                //$latestUser = PurchaseGoodsAndService::find();

                $latestUser = PurchaseGoodsAndService::find($userId);

                $latestUser->purchase_item =  $request->purchase_good_service_item;

                $latestUser->pur_suplier_info =  $request->supplier_vendor;

                $latestUser->pur_suplier_gst =  $request->supplier_vendor;

                $latestUser->pur_quantity =  $request->quantity;

                $latestUser->pur_uom =  $request->uom;

                $latestUser->pur_converted_val =  $request->quantity * $qty;

                $latestUser->pur_emission_factor =  $emission_factor;

                $latestUser->pur_total_emissions =  $total_emissions;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->pur_status =  1;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Updated Purchased Goods & Services data Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }



    public function saveCapitalGoods(Request $request)

    {

        try {

            /*

        "purchase_good_service_item": purchase_good_service_item,

        "supplier_vendor": supplier_vendor,

        "supplier_vendor_info": supplier_vendor_info,

        "quantity": quantity,

        "uom": uom,

        */

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'purchase_good_service_item' => 'required|string|max:250',

                'supplier_vendor' => 'required|string|max:250',

                //'supplier_vendor_info' => 'required|string|max:250',

                'quantity' => 'required|numeric|max:10000',

                'uom' => 'required|string|max:250'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->id) && $request->id != '') {

                $userId = Crypt::decrypt($request->id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            if ($request->uom == 'Kg') {

                $qty = 1;
            } else if ($request->uom == 'Tonnes') {

                $qty = 1000;
            }

            $converted_val = $request->quantity * $qty;

            $emission_factor = $this->capitalEmmissionFactor[$request->uom];

            $total_emissions = ($converted_val * $emission_factor) / 1000;

            DB::beginTransaction();

            try {

                $latestUser = new CapitalGoods();

                $latestUser->capital_goods_item =  $request->purchase_good_service_item;

                $latestUser->cap_suplier_info =  $request->supplier_vendor;

                $latestUser->cap_suplier_gst =  '';

                $latestUser->cap_quantity =  $request->quantity;

                $latestUser->cap_uom =  $request->uom;

                $latestUser->cap_converted_val =  $converted_val;

                $latestUser->cap_emission_factor =  $emission_factor;

                $latestUser->cap_total_emissions =  $total_emissions;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->updated_by =  Auth::user()->id;

                $latestUser->cap_status =  1;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Saved Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    public function editCapitalGoods(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $result = [];

            $encryptedId = $request->purchase_id;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = CapitalGoods::find($decryptedId);

                    $existingParticulars->encrptid = Crypt::encrypt($existingParticulars->id);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        //$recorddeleted = CapitalGoods::find($existingParticulars->id)->get()->toArray();

                        if ($existingParticulars) {

                            $isValid = 'success';

                            $errorMsg = 'Please update selected record.';

                            $result = $existingParticulars;
                        }
                    }
                }
            }

            $data = ['status' => $isValid, 'message' => $errorMsg, 'result' => $result];

            return json_encode($data);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function updateCaptialGoods(Request $request)

    {

        try {

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'purchase_good_service_item' => 'required|string|max:250',

                'supplier_vendor' => 'required|string|max:250',

                //'supplier_vendor_info' => 'required|string|max:250',

                'quantity' => 'required|numeric|max:10000',

                'uom' => 'required|string|max:250'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->purchase_good_service_item_Id) && $request->purchase_good_service_item_Id != '') {

                $userId = Crypt::decrypt($request->purchase_good_service_item_Id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            if ($request->uom == 'Kg') {

                $qty = 1;
            } else if ($request->uom == 'Tonnes') {

                $qty = 1000;
            }

            $converted_val = $request->quantity * $qty;

            $emission_factor = $this->capitalEmmissionFactor[$request->uom];

            $total_emissions = ($converted_val * $emission_factor) / 1000;

            //$update = \DB::table('student') ->where('id', $data['id']) ->limit(1) 

            //->update( [ 'name' => $data['name'], 'address' => $data['address'], 'email' => $data['email'], 'contactno' => $data['contactno'] ]); 

            DB::beginTransaction();

            try {

                //capitalEmmissionFactor

                //$latestUser = PurchaseGoodsAndService::find();

                $latestUser = CapitalGoods::find($userId);

                $latestUser->capital_goods_item =  $request->purchase_good_service_item;

                $latestUser->cap_suplier_info =  $request->supplier_vendor;

                $latestUser->cap_suplier_gst =  '';

                $latestUser->cap_quantity =  $request->quantity;

                $latestUser->cap_uom =  $request->uom;

                $latestUser->cap_converted_val =  $converted_val;

                $latestUser->cap_emission_factor =  $emission_factor;

                $latestUser->cap_total_emissions =  $total_emissions;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->updated_by =  Auth::user()->id;

                $latestUser->cap_status =  1;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Updated Purchased Goods & Services data Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function deleteCapitalGoods(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $encryptedId = $request->selectedId;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = CapitalGoods::find($decryptedId);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        $recorddeleted = CapitalGoods::find($existingParticulars->id)->delete();

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

    public function fuelandenergy(Request $request)

    {

        try {

            // $financialYearVal = $request->financialYearVal;

            $financialYearVal =  session::get('financialYearVal');

            Log::info("financialYearVal => " . $financialYearVal);

            return view('scopethree.fuelandenergy', compact('financialYearVal'));
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function capitalgoods(Request $request)

    {

        try {

            // $financialYearVal = $request->financialYearVal;

            $financialYearVal =  session::get('financialYearVal');

            $query = CapitalGoods::select('id', 'capital_goods_item', 'cap_suplier_info', 'cap_quantity', 'cap_uom', 'cap_converted_val', 'cap_total_emissions');

            $savedPurchasedItems = $query->where('cap_status', 1)->where('created_by', Auth::user()->id)->get()->toArray();

            Log::info("financialYearVal => " . $financialYearVal);

            return view('scopethree.capitalgoods', compact('financialYearVal', 'savedPurchasedItems'));
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }



    /*

waste management functionality starts here

*/



    public function waste(Request $request)

    {

        try {

            // $financialYearVal = $request->financialYearVal;

            $financialYearVal =  session::get('financialYearVal');

            $query = WasteManagement::select('id', 'wa_waste_type', 'wa_region_id', 'wa_ef_data', 'wa_treatment_type', 'wa_activity', 'wa_uom', 'wa_quantity',);

            $savedPurchasedItems = $query->where('wa_status', 1)->where('created_by', Auth::user()->id)->get()->toArray();



            Log::info("financialYearVal => " . $financialYearVal);

            return view('scopethree.waste', compact('financialYearVal', 'savedPurchasedItems'));
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function saveWaste(Request $request)

    {

        try {

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'wa_waste_type' => 'required|string|max:250',

                'wa_region_id' => 'required|string|max:250',

                'wa_ef_data' => 'required|string|max:250',

                'wa_treatment_type' => 'required|string|max:250',

                'wa_activity' => 'required|string|max:250',

                'wa_quantity' => 'required|numeric|max:10000',

                'wa_uom' => 'required|string|max:250'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->id) && $request->id != '') {

                $userId = Crypt::decrypt($request->id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            $qty =   0.001;

            $converted_val = $request->wa_quantity * $qty;

            $emission_factor =   0.79;

            $total_emissions = $converted_val * $emission_factor;

            DB::beginTransaction();

            try {

                $latestUser = new WasteManagement();

                $latestUser->wa_waste_type =  $request->wa_waste_type;

                $latestUser->wa_region_id =  $request->wa_region_id;

                $latestUser->wa_treatment_type =  $request->wa_treatment_type;

                $latestUser->wa_ef_data =  $request->wa_ef_data;

                $latestUser->wa_activity =  $request->wa_activity;

                $latestUser->wa_uom =  $request->wa_uom;

                $latestUser->wa_quantity =  $request->wa_quantity;

                $latestUser->wa_converted_val =  $converted_val;

                $latestUser->wa_emission_factor =  $emission_factor;

                $latestUser->wa_total_emissions =  $total_emissions;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->updated_by =  Auth::user()->id;

                $latestUser->wa_status =  1;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Saved Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }



    public function updateWaste(Request $request)

    {

        try {

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'wa_waste_type' => 'required|string|max:250',

                'wa_region_id' => 'required|string|max:250',

                'wa_ef_data' => 'required|string|max:250',

                'wa_treatment_type' => 'required|string|max:250',

                'wa_activity' => 'required|string|max:250',

                'wa_quantity' => 'required|numeric|max:10000',

                'wa_uom' => 'required|string|max:250'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->wa_item_id) && $request->wa_item_id != '') {

                $userId = Crypt::decrypt($request->wa_item_id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            $qty =   0.001;

            $converted_val = $request->wa_quantity * $qty;

            $emission_factor =   0.79;

            $total_emissions = $converted_val * $emission_factor;

            //$update = \DB::table('student') ->where('id', $data['id']) ->limit(1) 

            //->update( [ 'name' => $data['name'], 'address' => $data['address'], 'email' => $data['email'], 'contactno' => $data['contactno'] ]); 

            DB::beginTransaction();

            try {

                //capitalEmmissionFactor

                //$latestUser = PurchaseGoodsAndService::find();

                $latestUser = WasteManagement::find($userId);

                $latestUser->wa_waste_type =  $request->wa_waste_type;

                $latestUser->wa_region_id =  $request->wa_region_id;

                $latestUser->wa_treatment_type =  $request->wa_treatment_type;

                $latestUser->wa_ef_data =  $request->wa_ef_data;

                $latestUser->wa_activity =  $request->wa_activity;

                $latestUser->wa_uom =  $request->wa_uom;

                $latestUser->wa_quantity =  $request->wa_quantity;

                $latestUser->wa_converted_val =  $converted_val;

                $latestUser->wa_emission_factor =  $emission_factor;

                $latestUser->wa_total_emissions =  $total_emissions;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->updated_by =  Auth::user()->id;

                $latestUser->wa_status =  1;

                // echo "<pre>$userId";

                // print_r($latestUser);

                // exit;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Updated Purchased Goods & Services data Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function editWaste(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $result = [];

            $encryptedId = $request->purchase_id;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = WasteManagement::find($decryptedId);

                    $existingParticulars->encrptid = Crypt::encrypt($existingParticulars->id);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        //$recorddeleted = CapitalGoods::find($existingParticulars->id)->get()->toArray();

                        if ($existingParticulars) {

                            $isValid = 'success';

                            $errorMsg = 'Please update selected record.';

                            $result = $existingParticulars;
                        }
                    }
                }
            }

            $data = ['status' => $isValid, 'message' => $errorMsg, 'result' => $result];

            return json_encode($data);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function deleteWaste(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $encryptedId = $request->selectedId;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = WasteManagement::find($decryptedId);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        $recorddeleted = WasteManagement::find($existingParticulars->id)->delete();

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

    /*

waste management functionality Ends here

*/



    /*

BusinessTravel management functionality starts here

*/

    public function businesstravel(Request $request)

    {

        try {

            // $financialYearVal = $request->financialYearVal;

            $financialYearVal =  session::get('financialYearVal');

            $query = BusinessTravel::select('id', 'bu_particulars', 'bu_uom', 'bu_quantity');

            $businessTravelList = $query->where('bu_status', 1)->where('created_by', Auth::user()->id)->get()->toArray();



            $query1 = TransportMode::select('id', 'motm_transport_mode');

            $transport_mode = $query1->where('motm_status', 1)->get()->toArray();



            Log::info("financialYearVal => " . $financialYearVal);

            return view('scopethree.businesstravel', compact('financialYearVal', 'businessTravelList', 'transport_mode'));
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function saveBusinessTravel(Request $request)

    {

        try {

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'bu_particulars' => 'required|string|max:250',

                'bu_region' => 'required|string|max:250',

                'bu_emission_factor_data' => 'required|string|max:250',

                'bu_mode_of_transportation' => 'required|string|max:250',

                'bu_type_of_transportation' => 'required|string|max:250',

                'bu_activity' => 'required|string|max:250',

                'bu_uom' => 'required|string|max:250',

                'bu_quantity' => 'required|numeric|max:10000'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->id) && $request->id != '') {

                $userId = Crypt::decrypt($request->id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            $qty =   0.001;

            $converted_val = $request->bu_quantity * $qty;

            $emission_factor =   0.79;

            $total_emissions = $converted_val * $emission_factor;

            DB::beginTransaction();

            try {

                $latestUser = new BusinessTravel();

                $latestUser->bu_particulars =  $request->bu_particulars;

                $latestUser->bu_region =  $request->bu_region;

                $latestUser->bu_emission_factor_data =  $request->bu_emission_factor_data;

                $latestUser->bu_mode_of_transportation =  $request->bu_mode_of_transportation;

                $latestUser->bu_type_of_transportation =  $request->bu_type_of_transportation;

                $latestUser->bu_one_way_return =  $request->bu_one_way_return;

                $latestUser->bu_from =  $request->bu_from;

                $latestUser->bu_to =  $request->bu_to;

                $latestUser->bu_activity =  $request->bu_activity;

                $latestUser->bu_uom =  $request->bu_uom;

                $latestUser->bu_quantity =  $request->bu_quantity;

                $latestUser->bu_converted_val =  $converted_val;

                $latestUser->bu_emission_factor =  $emission_factor;

                $latestUser->bu_total_emissions =  $total_emissions;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->updated_by =  Auth::user()->id;

                $latestUser->bu_status =  1;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Saved Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }



    public function updateBusinessTravel(Request $request)

    {

        try {

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'bu_particulars' => 'required|string|max:250',

                'bu_region' => 'required|string|max:250',

                'bu_emission_factor_data' => 'required|string|max:250',

                'bu_mode_of_transportation' => 'required|string|max:250',

                'bu_type_of_transportation' => 'required|string|max:250',

                'bu_activity' => 'required|string|max:250',

                'bu_uom' => 'required|string|max:250',

                'bu_quantity' => 'required|numeric|max:10000'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->bu_item_id) && $request->bu_item_id != '') {

                $userId = Crypt::decrypt($request->bu_item_id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            $qty =   0.001;

            $converted_val = $request->bu_quantity * $qty;

            $emission_factor =   0.79;

            $total_emissions = $converted_val * $emission_factor;

            //$update = \DB::table('student') ->where('id', $data['id']) ->limit(1) 

            //->update( [ 'name' => $data['name'], 'address' => $data['address'], 'email' => $data['email'], 'contactno' => $data['contactno'] ]); 

            DB::beginTransaction();

            try {

                //capitalEmmissionFactor

                //$latestUser = PurchaseGoodsAndService::find();

                $latestUser = BusinessTravel::find($userId);

                $latestUser->bu_particulars =  $request->bu_particulars;

                $latestUser->bu_region =  $request->bu_region;

                $latestUser->bu_emission_factor_data =  $request->bu_emission_factor_data;

                $latestUser->bu_mode_of_transportation =  $request->bu_mode_of_transportation;

                $latestUser->bu_type_of_transportation =  $request->bu_type_of_transportation;

                $latestUser->bu_one_way_return =  $request->bu_one_way_return;

                $latestUser->bu_from =  $request->bu_from;

                $latestUser->bu_to =  $request->bu_to;

                $latestUser->bu_activity =  $request->bu_activity;

                $latestUser->bu_uom =  $request->bu_uom;

                $latestUser->bu_quantity =  $request->bu_quantity;

                $latestUser->bu_converted_val =  $converted_val;

                $latestUser->bu_emission_factor =  $emission_factor;

                $latestUser->bu_total_emissions =  $total_emissions;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->updated_by =  Auth::user()->id;

                $latestUser->bu_status =  1;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Updated Purchased Goods & Services data Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function editBusinessTravel(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $result = [];

            $encryptedId = $request->purchase_id;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = BusinessTravel::find($decryptedId);

                    $existingParticulars->encrptid = Crypt::encrypt($existingParticulars->id);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        //$recorddeleted = CapitalGoods::find($existingParticulars->id)->get()->toArray();

                        if ($existingParticulars) {

                            $isValid = 'success';

                            $errorMsg = 'Please update selected record.';

                            $result = $existingParticulars;
                        }
                    }
                }
            }

            $data = ['status' => $isValid, 'message' => $errorMsg, 'result' => $result];

            return json_encode($data);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function deleteBusinessTravel(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $encryptedId = $request->selectedId;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = BusinessTravel::find($decryptedId);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        $recorddeleted = BusinessTravel::find($existingParticulars->id)->delete();

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

    /*

BusinessTravel management functionality ends here

*/





    /*

employeeCommute management functionality ends here

*/

    public function employeeCommute(Request $request)

    {

        try {

            // $financialYearVal = $request->financialYearVal;

            $financialYearVal =  session::get('financialYearVal');

            $query = EmployeeCommute::select('id', 'ec_particulars', 'ec_uom', 'ec_quantity');

            $businessTravelList = $query->where('ec_status', 1)->where('created_by', Auth::user()->id)->get()->toArray();



            $query1 = TransportMode::select('id', 'motm_transport_mode');

            $transport_mode = $query1->where('motm_status', 1)->get()->toArray();



            Log::info("financialYearVal => " . $financialYearVal);

            return view('scopethree.employeecommute', compact('financialYearVal', 'businessTravelList', 'transport_mode'));
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function saveEmployeeCommute(Request $request)

    {

        try {

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'ec_particulars' => 'required|string|max:250',

                'ec_region' => 'required|string|max:250',

                'ec_emission_factor_data' => 'required|string|max:250',

                'ec_mode_of_transportation' => 'required|string|max:250',

                'ec_type_of_transportation' => 'required|string|max:250',

                'ec_activity' => 'required|string|max:250',

                'ec_uom' => 'required|string|max:250',

                'ec_quantity' => 'required|numeric|max:10000'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->id) && $request->id != '') {

                $userId = Crypt::decrypt($request->id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            $qty =   0.001;

            $converted_val = $request->ec_quantity * $qty;

            $emission_factor =   0.79;

            $total_emissions = $converted_val * $emission_factor;

            DB::beginTransaction();

            try {

                $latestUser = new EmployeeCommute();

                $latestUser->ec_particulars =  $request->ec_particulars;

                $latestUser->ec_region =  $request->ec_region;

                $latestUser->ec_emission_factor_data =  $request->ec_emission_factor_data;

                $latestUser->ec_mode_of_transportation =  $request->ec_mode_of_transportation;

                $latestUser->ec_type_of_transportation =  $request->ec_type_of_transportation;

                $latestUser->ec_activity =  $request->ec_activity;

                $latestUser->ec_uom =  $request->ec_uom;

                $latestUser->ec_quantity =  $request->ec_quantity;

                $latestUser->ec_converted_val =  $converted_val;

                $latestUser->ec_emission_factor =  $emission_factor;

                $latestUser->ec_total_emissions =  $total_emissions;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->updated_by =  Auth::user()->id;

                $latestUser->ec_status =  1;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Saved Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }



    public function updateEmployeeCommute(Request $request)

    {

        try {

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'ec_particulars' => 'required|string|max:250',

                'ec_region' => 'required|string|max:250',

                'ec_emission_factor_data' => 'required|string|max:250',

                'ec_mode_of_transportation' => 'required|string|max:250',

                'ec_type_of_transportation' => 'required|string|max:250',

                'ec_activity' => 'required|string|max:250',

                'ec_uom' => 'required|string|max:250',

                'ec_quantity' => 'required|numeric|max:10000'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->ec_item_id) && $request->ec_item_id != '') {

                $userId = Crypt::decrypt($request->ec_item_id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            $qty =   0.001;

            $converted_val = $request->ec_quantity * $qty;

            $emission_factor =   0.79;

            $total_emissions = $converted_val * $emission_factor;

            //$update = \DB::table('student') ->where('id', $data['id']) ->limit(1) 

            //->update( [ 'name' => $data['name'], 'address' => $data['address'], 'email' => $data['email'], 'contactno' => $data['contactno'] ]); 

            DB::beginTransaction();

            try {

                //capitalEmmissionFactor

                //$latestUser = PurchaseGoodsAndService::find();

                $latestUser = EmployeeCommute::find($userId);

                $latestUser->ec_particulars =  $request->ec_particulars;

                $latestUser->ec_region =  $request->ec_region;

                $latestUser->ec_emission_factor_data =  $request->ec_emission_factor_data;

                $latestUser->ec_mode_of_transportation =  $request->ec_mode_of_transportation;

                $latestUser->ec_type_of_transportation =  $request->ec_type_of_transportation;

                $latestUser->ec_activity =  $request->ec_activity;

                $latestUser->ec_uom =  $request->ec_uom;

                $latestUser->ec_quantity =  $request->ec_quantity;

                $latestUser->ec_converted_val =  $converted_val;

                $latestUser->ec_emission_factor =  $emission_factor;

                $latestUser->ec_total_emissions =  $total_emissions;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->updated_by =  Auth::user()->id;

                $latestUser->ec_status =  1;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Updated Purchased Goods & Services data Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function editEmployeeCommute(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $result = [];

            $encryptedId = $request->purchase_id;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = EmployeeCommute::find($decryptedId);

                    $existingParticulars->encrptid = Crypt::encrypt($existingParticulars->id);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        //$recorddeleted = CapitalGoods::find($existingParticulars->id)->get()->toArray();

                        if ($existingParticulars) {

                            $isValid = 'success';

                            $errorMsg = 'Please update selected record.';

                            $result = $existingParticulars;
                        }
                    }
                }
            }

            $data = ['status' => $isValid, 'message' => $errorMsg, 'result' => $result];

            return json_encode($data);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function deleteEmployeeCommute(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $encryptedId = $request->selectedId;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = EmployeeCommute::find($decryptedId);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        $recorddeleted = EmployeeCommute::find($existingParticulars->id)->delete();

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



    /*

employeeCommute management functionality ends here

*/





    /*

downStream management functionality ends here

*/

    public function downstream(Request $request)

    {

        try {

            // $financialYearVal = $request->financialYearVal;

            $financialYearVal =  session::get('financialYearVal');

            $query = DownStream::select('id', 'ds_particulars', 'ds_uom', 'ds_quantity');

            $businessTravelList = $query->where('ds_status', 1)->where('created_by', Auth::user()->id)->get()->toArray();



            $query1 = TransportMode::select('id', 'motm_transport_mode');

            $transport_mode = $query1->where('motm_status', 1)->get()->toArray();



            Log::info("financialYearVal => " . $financialYearVal);

            return view('scopethree.downstream', compact('financialYearVal', 'businessTravelList', 'transport_mode'));
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function saveDownStream(Request $request)

    {

        try {

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'ds_particulars' => 'required|string|max:250',

                'ds_region' => 'required|string|max:250',

                'ds_emission_factor_data' => 'required|string|max:250',

                'ds_mode_of_transportation' => 'required|string|max:250',

                'ds_type_of_transportation' => 'required|string|max:250',

                'ds_activity' => 'required|string|max:250',

                'ds_uom' => 'required|string|max:250',

                'ds_quantity' => 'required|numeric|max:10000'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->id) && $request->id != '') {

                $userId = Crypt::decrypt($request->id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            $qty =   0.001;

            $converted_val = $request->ds_quantity * $qty;

            $emission_factor =   0.79;

            $total_emissions = $converted_val * $emission_factor;

            DB::beginTransaction();

            try {

                $latestUser = new DownStream();

                $latestUser->ds_particulars =  $request->ds_particulars;

                $latestUser->ds_region =  $request->ds_region;

                $latestUser->ds_emission_factor_data =  $request->ds_emission_factor_data;

                $latestUser->ds_mode_of_transportation =  $request->ds_mode_of_transportation;

                $latestUser->ds_type_of_transportation =  $request->ds_type_of_transportation;

                $latestUser->ds_activity =  $request->ds_activity;

                $latestUser->ds_uom =  $request->ds_uom;

                $latestUser->ds_quantity =  $request->ds_quantity;

                $latestUser->ds_converted_val =  $converted_val;

                $latestUser->ds_emission_factor =  $emission_factor;

                $latestUser->ds_total_emissions =  $total_emissions;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->updated_by =  Auth::user()->id;

                $latestUser->ds_status =  1;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Saved Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }



    public function updateDownStream(Request $request)

    {

        try {

            $userId = NULL;

            // Log::info(print_r($request->all(), true));

            $validator = Validator::make($request->all(), [

                'ds_particulars' => 'required|string|max:250',

                'ds_region' => 'required|string|max:250',

                'ds_emission_factor_data' => 'required|string|max:250',

                'ds_mode_of_transportation' => 'required|string|max:250',

                'ds_type_of_transportation' => 'required|string|max:250',

                'ds_activity' => 'required|string|max:250',

                'ds_uom' => 'required|string|max:250',

                'ds_quantity' => 'required|numeric|max:10000'

            ]);



            if ($validator->fails()) {

                $messages = $validator->messages();

                $response = array("status" => "validation", "message" => $messages);

                return response()->json($response);
            }



            $userId = NULL;

            if (isset($request->ds_item_id) && $request->ds_item_id != '') {

                $userId = Crypt::decrypt($request->ds_item_id);
            }



            $companyId = '';

            if (Auth::user()->company_id && Auth::user()->company_id != '') {

                $companyId = Auth::user()->company_id;
            }

            if (!isset($companyId) || $companyId == '') {

                $response = array("status" => "error", "message" => "Invalid user login");

                return response()->json($response);
            }

            $status = 'error';

            $msg = 'Something Went Wrong!';

            $isOwner = 'no';

            $qty =   0.001;

            $converted_val = $request->ds_quantity * $qty;

            $emission_factor =   0.79;

            $total_emissions = $converted_val * $emission_factor;

            //$update = \DB::table('student') ->where('id', $data['id']) ->limit(1) 

            //->update( [ 'name' => $data['name'], 'address' => $data['address'], 'email' => $data['email'], 'contactno' => $data['contactno'] ]); 

            DB::beginTransaction();

            try {

                //capitalEmmissionFactor

                //$latestUser = PurchaseGoodsAndService::find();

                $latestUser = DownStream::find($userId);

                $latestUser->ds_particulars =  $request->ds_particulars;

                $latestUser->ds_region =  $request->ds_region;

                $latestUser->ds_emission_factor_data =  $request->ds_emission_factor_data;

                $latestUser->ds_mode_of_transportation =  $request->ds_mode_of_transportation;

                $latestUser->ds_type_of_transportation =  $request->ds_type_of_transportation;

                $latestUser->ds_activity =  $request->ds_activity;

                $latestUser->ds_uom =  $request->ds_uom;

                $latestUser->ds_quantity =  $request->ds_quantity;

                $latestUser->ds_converted_val =  $converted_val;

                $latestUser->ds_emission_factor =  $emission_factor;

                $latestUser->ds_total_emissions =  $total_emissions;

                $latestUser->created_by =  Auth::user()->id;

                $latestUser->updated_by =  Auth::user()->id;

                $latestUser->ds_status =  1;

                $latestUser->save();



                $lastInsertedId = $latestUser->id;

                if (isset($lastInsertedId) && $lastInsertedId != '') {

                    // Log::info("Stationary Combution Saved Successfully!");

                    $status = 'success';

                    $msg = 'Updated Purchased Goods & Services data Successfully!';
                }

                DB::commit();

                $response = array("status" => $status, "message" => $msg);

                return response()->json($response);
            } catch (\Exception $e) {

                DB::rollback();

                return $e->getMessage();
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function editDownStream(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $result = [];

            $encryptedId = $request->purchase_id;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = DownStream::find($decryptedId);

                    $existingParticulars->encrptid = Crypt::encrypt($existingParticulars->id);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        //$recorddeleted = CapitalGoods::find($existingParticulars->id)->get()->toArray();

                        if ($existingParticulars) {

                            $isValid = 'success';

                            $errorMsg = 'Please update selected record.';

                            $result = $existingParticulars;
                        }
                    }
                }
            }

            $data = ['status' => $isValid, 'message' => $errorMsg, 'result' => $result];

            return json_encode($data);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function deleteDownStream(Request $request)

    {

        try {

            $isValid = 'error';

            $errorMsg = 'Invalid record found';

            $encryptedId = $request->selectedId;

            if (isset($encryptedId) && $encryptedId != '') {

                $decryptedId = Crypt::decrypt($encryptedId);

                if (isset($decryptedId) && $decryptedId != '') {

                    $existingParticulars = EmployeeCommute::find($decryptedId);

                    if (isset($existingParticulars->id) && $existingParticulars->id != '') {

                        $recorddeleted = EmployeeCommute::find($existingParticulars->id)->delete();

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



    /*

downStream management functionality ends here

*/



    /* 

soldproducts management functionality starts here

*/



    public function soldproducts()
    {

        try {

            // $financialYearVal = $request->financialYearVal;

            $financialYearVal =  session::get('financialYearVal');

            $query = Soldproducts::select('id', 'purchase_item', 'pur_suplier_info', 'pur_suplier_gst', 'pur_quantity', 'pur_uom',);

            $savedPurchasedItems = $query->where('pur_status', 1)->where('created_by', Auth::user()->id)->get()->toArray();



            Log::info("financialYearVal => " . $financialYearVal);

            return view('scopethree.purchaseofgoodsandservice', compact('financialYearVal', 'savedPurchasedItems'));
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }



    /* 

soldproducts management functionality ends here

*/





    /*

Master Clasess

*/

    public function typeOfTransport(Request $request)

    {

        try {

            // $financialYearVal = $request->financialYearVal;

            $financialYearVal =  session::get('financialYearVal');

            $query1 = TransportType::select('id', 'transport_type');

            if (isset($request->colm_type)) {

                $query1 = $query1->where($request->colm_type, $request->trasport_id);
            }

            $transport_mode = $query1->where('status', 1)->get()->toArray();

            return $transport_mode;
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
