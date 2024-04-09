<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayUService\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Session;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        // $this->middleware('role:Administrator');
        $this->middleware('role:Administrator|Team Lead|Employee');
    }
    public function index() {
        try {
            // Log::info("InAdminController");
            return view('admin.home');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getchooseView() {
        try {
            return view('admin.chooseView');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getUsersView() {
        try {
            return view('admin.usersList');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getUsersList(Request $request) {
        try {
            // Page Length
            $pageNumber = ( $request->start / $request->length )+1;
            $pageLength = $request->length;
            $skip       = ($pageNumber-1) * $pageLength;

            // Page Order
            $orderColumnIndex = $request->order[0]['column'] ?? '0';
            $orderBy = $request->order[0]['dir'] ?? 'desc';

            // get data from products table
            // $query = \DB::table('users')->select('*');
            // $query = User::select('id', 'full_name', 'name_of_org', 'industry', 'head_quarters', 'country');
            $query = User::select('id', 'full_name', 'department', 'role');
            // Search
            $userId = Auth::user()->id;
            $search = $request->search;
            $query = $query->where(function($query) use ($search){
                $query->orWhere('full_name', 'like', "%".$search."%");
                // $query->orWhere('name_of_org', 'like', "%".$search."%");
                $query->orWhere('department', 'like', "%".$search."%");
            });

            $orderByName = 'full_name';
            switch($orderColumnIndex){
                case '0':
                    $orderByName = 'full_name';
                    break;
                case '1':
                    $orderByName = 'department';
                    break;
                /*case '2':
                    $orderByName = 'role';
                    break;
                case '3':
                    $orderByName = 'head_quarters';
                    break;
                case '4':
                    $orderByName = 'country';
                    break;
                case '5':
                    $orderByName = 'id';
                    break; */
            }
            $query = $query->where('company_id', Auth::user()->company_id)->where('id', '!=', $userId);
            $query = $query->orderBy($orderByName, $orderBy);
            $recordsFiltered = $recordsTotal = $query->count();
            $users = $query->skip($skip)->take($pageLength)->get();
            if(isset($users) && count($users) > 0 ) {
                $roles = Role::pluck('name', 'id');
                foreach($users as $user) {
                    if(isset($user->id) && $user->id != '') {
                        $user->encryptedId = Crypt::encrypt($user->id);
                    }
                    if(isset($user->role) && $user->role != '') {
                        $user->role = $roles[$user->role];
                    }
                }
            }
            // Log::info(print_r($users, true));
            return response()->json(["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $users], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function userEdit(Request $request) {
        try {
            // print_r($request->all());exit();
            $encryptedId = urldecode($request->id);
            // $encryptedId = $request->id;
            $type = $request->type;
            if(isset($encryptedId) && $encryptedId != '') {
                try {
                    echo "userEdit"; exit;
                    // Log::info("decrypt => ".$encryptedId);
                    $decryptedId = Crypt::decrypt($encryptedId);
                    // Log::info("decrypt => ".$decryptedId);exit();
                    if(isset($decryptedId) && $decryptedId != '') {
                        $userDetails = User::find($decryptedId);
                        $userCompany =  Company::find($userDetails->company_id);
                        if(isset($userDetails->id) && $userDetails->id != '') {
                            $roles = self::getRoles();
                            return view('admin.editUserForm', compact('userDetails', 'roles', 'type', 'userCompany'));
                        } else {
                            return "Invalid user selection";
                        }
                    }
                } catch (DecryptException $error) {
                    return $error->getMessage();
                }
            }
            
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function userDelete(Request $request) {
        try {
            // print_r($request->all());exit();
            $encryptedId = urldecode($request->id);
            // $encryptedId = $request->id;
            $type = $request->type;
            $isAjax = 'no'; 
            if(isset($request->isAjax) && $request->isAjax != '' ) {
                $isAjax = $request->isAjax;
            }
            if(!isset($isAjax) && $isAjax == '') {
                $isAjax = 'no';     
            }
            $errorMsg = 'Something went wrong!';
            $isValid = 'error';
            if(isset($encryptedId) && $encryptedId != '') {
                try {
                    // Log::info("decrypt => ".$encryptedId);
                    $decryptedId = Crypt::decrypt($encryptedId);
                    // Log::info(__FILE__." decrypt => ".$decryptedId);
                    if(isset($decryptedId) && $decryptedId != '') {
                        $userDetails = User::find($decryptedId);
                        if(isset($userDetails->id) && $userDetails->id != '') {
                            Log::info("userId => ".$userDetails->id);
                            $roleUser = RoleUser::where('user_id', $userDetails->id)->first();
                            if(isset($roleUser->id) && $roleUser->id != '') {
                                Log::info("userRoleId => ".$roleUser->id);
                                DB::beginTransaction();
                                try {
                                    $roleUser->delete();
                                    $userDetails->delete();
                                    $isValid = 'success';
                                    $errorMsg = 'User Deleted Successfully.';
                                    DB::commit();
                                } catch(Exception $e) {
                                    DB::rollback();
                                    $errorMsg = $e->getMessage();
                                }
                            }
                            
                        } else {
                            Log::info("NO id found decryptedId => ".$decryptedId);
                        }
                        if($isAjax == 'yes'){
                            $data = ['status' => $isValid, 'message' => $errorMsg];
                            return json_encode($data);
                        }
                        return redirect()->route('home')->with('success_status', 'User Deleted Successfully.');
                        // ->withSuccess('You have successfully registered & logged in!');
                    } else {
                        $errorMsg = 'Invalid user selection';
                    }
                } catch (DecryptException $error) {
                    $errorMsg = $error->getMessage();
                }
            } else {
                $errorMsg = 'Invalid user selection';
            }
            return redirect()->route('home')->with('error_status', $errorMsg);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function addNewUser(Request $request) {
        try {
            $roles = self::getRoles();
            return view('admin.addNewUser', compact('roles'));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveAdminOrganizationInfo(Request $request) {
        try {
            // echo "<pre>";print_r($request->all());exit();
            // Log::info(print_r($request->all())); 
            /* $request->validate([
                'userName' => 'required|string|max:250',
                'email' => 'required|email|max:250|unique:users',
                'role' => 'required|numeric|max:10',
                'password' => 'required|min:8|confirmed'
            ]); */
            $validator = Validator::make($request->all(), [
                'fullName' => 'required|string|max:250',
                'email' => 'required|email|max:250|unique:users',
                'nameofOrg' => 'required|string|max:250',
                'dept' => 'required|string|max:250',
                'sizeofOrg' => 'required|numeric|max:10000',
                'country' => 'required|string|max:250',
                'role' => 'required|numeric|max:10',
                'password' => 'required|min:8|confirmed',
                'file' => 'mimes:pdf,png,gif,jpg,jpeg|max:2048',
            ]);
            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "messages" => $messages);
                return response()->json($response);
            }
            $companyId = '';
            if(Auth::user()->company_id && Auth::user()->company_id != '') {
                $companyId = Auth::user()->company_id;
            }

            if(!isset($companyId) || $companyId == '') {
                $response = array("status" => "validation", "messages" => "Invalid user login");
                return response()->json($response);
            }
            $fullName  = $email = $nameofOrg =  $sizeofOrg = $industry = $subIndustry = NULL;
            $headQuarters  = $country = $organizationURL = $role = $password = $fileName = $filePath = NULL;
            $addUser  = 'no';
            if(isset($request->fullName) && $request->fullName != '') {
                $fullName  = $request->fullName;
            }
            if(isset($request->password) && $request->password != '') {
                $password  = $request->password;
            }
            if(isset($request->email) && $request->email != '') {
                $email  = $request->email;
            }
            if(isset($request->nameofOrg) && $request->nameofOrg != '') {
                $nameofOrg  = $request->nameofOrg;
            }
            if(isset($request->sizeofOrg) && $request->sizeofOrg != '') {
                $sizeofOrg  = $request->sizeofOrg;
            }
            if(isset($request->industry) && $request->industry != '') {
                $industry  = $request->industry;
            }
            if(isset($request->subIndustry) && $request->subIndustry != '') {
                $subIndustry  = $request->subIndustry;
            }
            if(isset($request->headQuarters) && $request->headQuarters != '') {
                $headQuarters  = $request->headQuarters;
            }
            if(isset($request->country) && $request->country != '') {
                $country  = $request->country;
            }
            if(isset($request->organizationURL) && $request->organizationURL != '') {
                $organizationURL  = $request->organizationURL;
            }
            if(isset($request->role) && $request->role != '') {
                $role  = $request->role;
            }
            
            if(isset($request->addUser) && $request->addUser != '') {
                $addUser  = $request->addUser;
            }
            // $fileName = time().'.'.request()->file->getClientOriginalExtension();
            if(isset($request->file) && $request->file != '') {
                $file = $request->file('file');
                // $fileName = time().'.'.request()->file->getClientOriginalExtension();
                $fileName = time()."_".$file->getClientOriginalName();
                $destinationPath = 'uploads';
                $filePath = 'uploads/'.$fileName;
                $file->move($destinationPath, $fileName);
                
             
            }
            DB::beginTransaction();
            try {

                Log::info("request->fullName => ".$fullName);
                $latestUser = new User();
                $latestUser->full_name =  $fullName;
                $latestUser->email =  $email;
                $latestUser->password =  Hash::make($password);
                $latestUser->company_id =  $companyId;
                $latestUser->department =  $request->dept;
                /* $latestUser->name_of_org =  $nameofOrg;
                $latestUser->size_of_org =  $sizeofOrg;
                $latestUser->industry =  $industry;
                $latestUser->sub_industry =  $subIndustry;
                $latestUser->head_quarters =  $headQuarters;
                $latestUser->country =  $country;
                $latestUser->org_url =  $organizationURL; */
                $latestUser->role =  $role;
                $latestUser->img_path =  $filePath;
                $latestUser->created_by =  Auth::user()->id;
                $latestUser->status_id =  1;
                $latestUser->save();
                            
                $lastInsertedId = $latestUser->id;
                Log::info("lastInsertedId => ".$lastInsertedId);
                if(isset($lastInsertedId) && $lastInsertedId != '') {
                    $roleUser = new RoleUser();
                    $roleUser->role_id = $request->role;
                    $roleUser->user_id = $lastInsertedId;
                    $roleUser->save();
                }
                DB::commit();
                // all good
            } catch (\Exception $e) {
                DB::rollback();
                return $e->getMessage();
                // something went wrong
            }
    
            // $credentials = $request->only('email', 'password');
            // Auth::attempt($credentials);
            // $request->session()->regenerate();
            // return redirect()->route('home')->withSuccess('You have successfully registered & logged in!');
            $response = array("status" => "success", "messages" => 'New User Added Successfully', 'addUser' => $addUser);
            return response()->json($response);
        }  catch (\Exception $e) {
            return $e->getMessage();
        }
    }

   

    public function updateUserInfo(Request $request) {
        try {
            $encryptedUserId = $request->userId;
            $userUpdate = $request->userUpdate;
            $decryptedUserId = $userDetails = '';
            if(isset($encryptedUserId) && $encryptedUserId != '') {
                $decryptedUserId = Crypt::decrypt($encryptedUserId);
                // Log::info("decrypt => ".$decryptedId);exit();
                if(isset($decryptedUserId) && $decryptedUserId != '') {
                    $userDetails = User::find($decryptedUserId);
                }
            }
            if(isset($userDetails->id) && $userDetails->id != '') {
                //do nothing 
            } else {
                $response = array("status" => "validation", "messages" => "Invalid user details found");
                return response()->json($response);
            }
            //Log::info($userDetails->id);
            $validator = Validator::make($request->all(), [
                'fullName' => 'required|string|max:250',
                'email' => 'required|email|unique:users,email,'.$userDetails->id,
                'dept' => 'required|string|max:250',
                'nameofOrg' => 'required|string|max:250',
                'sizeofOrg' => 'required|numeric|max:10000',
                'country' => 'required|string|max:250',
                'role' => 'required|numeric|max:10',
                'password' => 'required|min:8|confirmed',
                'file' => 'mimes:pdf,png,gif,jpg,jpeg|max:2048',
            ]);
 
            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "messages" => $messages);
                return response()->json($response);
            }
            /* $companyId = '';
            if(Auth::user()->company_id && Auth::user()->company_id != '') {
                $companyId = Auth::user()->company_id;
            }

            if(!isset($companyId) || $companyId == '') {
                $response = array("status" => "validation", "messages" => "Invalid user login");
                return response()->json($response);
            } */
            $fullName  = $email = $nameofOrg =  $sizeofOrg = $industry = $subIndustry = NULL;
            $headQuarters  = $country = $organizationURL = $role = $password = $fileName = $filePath = NULL;
            $addUser  = 'no';
            if(isset($request->fullName) && $request->fullName != '') {
                $fullName  = $request->fullName;
            }
            if(isset($request->password) && $request->password != '') {
                $password  = $request->password;
            }
            if(isset($request->email) && $request->email != '') {
                $email  = $request->email;
            }
            if(isset($request->nameofOrg) && $request->nameofOrg != '') {
                $nameofOrg  = $request->nameofOrg;
            }
            if(isset($request->sizeofOrg) && $request->sizeofOrg != '') {
                $sizeofOrg  = $request->sizeofOrg;
            }
            if(isset($request->industry) && $request->industry != '') {
                $industry  = $request->industry;
            }
            if(isset($request->subIndustry) && $request->subIndustry != '') {
                $subIndustry  = $request->subIndustry;
            }
            if(isset($request->headQuarters) && $request->headQuarters != '') {
                $headQuarters  = $request->headQuarters;
            }
            if(isset($request->country) && $request->country != '') {
                $country  = $request->country;
            }
            if(isset($request->organizationURL) && $request->organizationURL != '') {
                $organizationURL  = $request->organizationURL;
            }
            if(isset($request->role) && $request->role != '') {
                $role  = $request->role;
            }
            
            if(isset($request->addUser) && $request->addUser != '') {
                $addUser  = $request->addUser;
            }
            // $fileName = time().'.'.request()->file->getClientOriginalExtension();
            if(isset($request->file) && $request->file != '') {
                $file = $request->file('file');
                // $fileName = time().'.'.request()->file->getClientOriginalExtension();
                $fileName = time()."_".$file->getClientOriginalName();
                $destinationPath = 'uploads';
                $filePath = 'uploads/'.$fileName;
                $file->move($destinationPath, $fileName);
                if(isset($userDetails->img_path) && $userDetails->img_path != '') {
                    if (File::exists(public_path($userDetails->img_path))) {
                        unlink(public_path($userDetails->img_path));
                        Log::info("FileDeleted => ".$userDetails->img_path);
                    }
                }
            }

                $userDetails->full_name =  $fullName;
                $userDetails->email =  $email;
                $userDetails->password =  Hash::make($password);
                $userDetails->department =  $request->dept;;
                // $userDetails->company_id =  $companyId;
                /* $userDetails->name_of_org =  $nameofOrg;
                $userDetails->size_of_org =  $sizeofOrg;
                $userDetails->industry =  $industry;
                $userDetails->sub_industry =  $subIndustry;
                $userDetails->head_quarters =  $headQuarters;
                $userDetails->country =  $country;
                $userDetails->org_url =  $organizationURL; */
                $userDetails->role =  $role;
                $userDetails->img_path =  $filePath;
                $userDetails->save();

                $response = array("status" => "success", "messages" => "User updated successfully", "addUser"=>$addUser);
                return response()->json($response);
                            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function ghgEmissionsView(Request $request) {
        try {
            // $financialYearVal = $request->financialYearVal;
            $financialYearVal =  session::get('financialYearVal');
            Log::info("financialYearVal => ".$financialYearVal);
            return view('ghg.ghgMainView', compact('financialYearVal'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function mobileghgEmissionsView(Request $request) {
        try {
            // $financialYearVal = $request->financialYearVal;
            $financialYearVal =  session::get('financialYearVal');
            Log::info("financialYearVal => ".$financialYearVal);
            return view('ghg.mobileghgMainView', compact('financialYearVal'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function refrigerantsghgEmissionsView(Request $request) {
        try {
            // $financialYearVal = $request->financialYearVal;
            $financialYearVal =  session::get('financialYearVal');
            Log::info("financialYearVal => ".$financialYearVal);
            return view('ghg.refrigerantsghgMainView', compact('financialYearVal'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function ElectricityofEvs(Request $request) {
        try {
            
            // $financialYearVal = $request->financialYearVal;
            $financialYearVal =  session::get('financialYearVal');
            Log::info("financialYearVal => ".$financialYearVal);
            return view('ghg.ElectricityofEvsMainview', compact('financialYearVal'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function ElectricityPurchased(Request $request) {
        try {
            
            // $financialYearVal = $request->financialYearVal;
            $financialYearVal =  session::get('financialYearVal');
            Log::info("financialYearVal => ".$financialYearVal);
            return view('ghg.ElectricityPurchasedMainview', compact('financialYearVal'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function ElectricityofHeat(Request $request) {
        try {
            
            // $financialYearVal = $request->financialYearVal;
            $financialYearVal =  session::get('financialYearVal');
            Log::info("financialYearVal => ".$financialYearVal);
            return view('ghg.ElectricityofHeatMainview', compact('financialYearVal'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function reports(Request $request) {
        try {
            echo "fff"; exit;
            // $financialYearVal = $request->financialYearVal;
            $financialYearVal =  session::get('financialYearVal');
            Log::info("financialYearVal => ".$financialYearVal);
            return view('ghg.purchaseOfElectricityMainview', compact('financialYearVal'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function ghgUserList(Request $request) {
        try {
            $companyId = Auth::user()->company_id;
            $userId = Auth::user()->id;
            $userData = User::select('id', 'full_name', 'role')
                            ->where('company_id', $companyId)
                            ->where('id', '!=', $userId)
                            ->orderBy('id', 'DESC')
                            ->get();
            $roles = self::getRoles('yes');
            return view('ghg.userList', compact('roles', 'userData'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function getGHGQuestionnaire(Request $request) {
        try {
            return view('ghg.questionnaire');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function addGHGNewUser(Request $request) {
        try {
            $roles =  self::getRoles();
            return view('ghg.addNewUser', compact('roles'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function upateUserRole(Request $request) {
        try {
            $encryptedUserId = $request->userId;
            $role = $request->role;
            $msg = 'Something Went Wrong!'; 
            $isValid = 'error';
            $msg = "Invalid User Id Found!";
            // Log::info("encryptedUserId => ".$encryptedUserId); 
            if(isset($encryptedUserId) && $encryptedUserId != '') {
                $decryptedUserId = Crypt::decrypt($encryptedUserId);
                // Log::info("decrypt => ".$decryptedUserId);exit();
                if(isset($decryptedUserId) && $decryptedUserId != '') {
                    $userDetails = User::find($decryptedUserId);
                    if(isset($userDetails->id ) && $userDetails->id != '') {
                        DB::beginTransaction();
                        try {
                            $userDetails->role = $role;
                            $userDetails->save();
                            $roleUserDetails = RoleUser::where('user_id', $userDetails->id)->first();
                            if(isset($roleUserDetails->id) && $roleUserDetails->id != '') {
                                $roleUserDetails->role_id = $role;
                                $roleUserDetails->save();
                            }
                            $isValid = "success"; $msg = "User Role Updated Successfully.";
                            // Log::info("user id => ".$userDetails->id." msg ".$msg);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            $msg = $e->getMessage();
                            // something went wrong
                        }
                    }
                }
            }
            Log::info("status => ".$isValid." msg ".$msg);
            $data = ["status" => $isValid, "message" => $msg];

            return json_encode($data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveNewUser(Request $request) {
        try {
            $userId = NULL;
            if(isset($request->id) && $request->id != '') {
                $userId = Crypt::decrypt($request->id);
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:250',
                    'email' => 'required|email|max:250|unique:users,email,'.$userId,
                    'dept' => 'required|string|max:250',
                    'role' => 'required|numeric|max:10',
                    'password' => 'required|min:8|confirmed',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:250',
                    'email' => 'required|email|max:250|unique:users',
                    'dept' => 'required|string|max:250',
                    'role' => 'required|numeric|max:10',
                    'password' => 'required|min:8|confirmed',
                ]);
            }
            
            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "messages" => $messages);
                return response()->json($response);
            }

            $userId = NULL;
            if(isset($request->id) && $request->id != '') {
                $userId = Crypt::decrypt($request->id);
            }

            $companyId = '';
            if(Auth::user()->company_id && Auth::user()->company_id != '') {
                $companyId = Auth::user()->company_id;
            }
            if(!isset($companyId) || $companyId == '') {
                $response = array("status" => "error", "message" => "Invalid user login");
                return response()->json($response);
            }
            $status = 'error';
            $msg = 'Something Went Wrong!';
            $isOwner = 'no';
            DB::beginTransaction();
            try {
                $isRecordExisting = false;
                if(isset($userId) && $userId != '') {
                    $latestUser = User::find($userId);
                    if(isset($latestUser->id) && $latestUser->id != '') {
                        $isRecordExisting = true;
                    }
                }
                if(!$isRecordExisting) {
                    $latestUser = new User();
                }
                $latestUser->full_name =  $request->name;
                $latestUser->email =  $request->email;
                $latestUser->password =  Hash::make($request->password);
                $latestUser->company_id =  $companyId;
                $latestUser->department =  $request->dept;
                $latestUser->role =  $request->role;
                $latestUser->created_by =  Auth::user()->id;
                $latestUser->status_id =  1;
                $latestUser->save();
                if(!$isRecordExisting) {          
                    $lastInsertedId = $latestUser->id;
                } else {
                    $lastInsertedId = $userId;
                }
                // Log::info("lastInsertedId => ".$lastInsertedId);
                if(isset($lastInsertedId) && $lastInsertedId != '') {
                    $roleUser = RoleUser::where('user_id', $lastInsertedId)->first();
                    if(isset($roleUser->id) && $roleUser->id != '') {
                        //do nothing
                    } else {
                        $roleUser = new RoleUser();
                    }
                    $roleUser->role_id = $request->role;
                    $roleUser->user_id = $lastInsertedId;
                    $roleUser->save();
                }
                
                $status = 'success';
                $msg = 'User Saved Successfully.';
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return $e->getMessage();
                // something went wrong
            }
            if(isset($userId) && $userId != '' && $userId == Auth::user()->id) {
                $status = 'success';
                $msg = 'Your Profile Updated Successfully.';
                $isOwner = 'yes';
            }
            $response = array("status" => $status, "message" => $msg, "isOwner" => $isOwner);
            return response()->json($response);
            
        } catch (Exception $e) {
           return $e->getMessage();
        }
    }

    public function ghgUserEditScreen($encryptedId) {
        try {
            // $encryptedId = $request->id;
            if(isset($encryptedId) && $encryptedId != '') {
                
                    $decryptedId = Crypt::decrypt($encryptedId);
                    // Log::info("decrypt => ".$decryptedId);
                    if(isset($decryptedId) && $decryptedId != '') {
                        // Log::info("decrypt step2 => ".$decryptedId);
                        $userDetails = User::find($decryptedId);
                        if(isset($userDetails->id) && $userDetails->id != '') {
                            $roles =  self::getRoles();
                            return view('ghg.editUserForm', compact('userDetails', 'roles'));
                        } else {
                            return "Invalid user selection";
                        }
                    }
                    return "Invalid user selection";
               
            }
        } catch (Exception $e) {
           return $e->getMessage();
        }
    }

    public function getRoles($isAll = 'no') {
        try {
            $inRoles = [];
            if($isAll == 'no') {
                if(Auth::user()->hasRole('Employee')) {
                    $inRoles = ['Employee'];
                }
                if(Auth::user()->hasRole('Team Lead')) {
                    $inRoles = ['Employee', 'Team Lead'];
                }
            }
            if(count($inRoles) > 0) {
                $roles = Role::whereIn('name', $inRoles)->pluck('name', 'id');
            } else {
                $roles = Role::pluck('name', 'id');
            }
            $roles = $roles->prepend('Select Role', '')->toArray();
            return $roles;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
