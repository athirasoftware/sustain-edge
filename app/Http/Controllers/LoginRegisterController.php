<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\PayUService\Exception;
use Illuminate\Support\Facades\Log;
use Validator;
use DB;
use Illuminate\Support\Facades\File;

class LoginRegisterController extends Controller
{
    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout', 'checkLogin'
        ]);
    }

    public function checkLogin() {
        try {
           // echo $request->email; exit;
            // Log::info("auth checking for login");
            if(Auth::check()) {
                if(Auth::User()->hasAnyRole(['Administrator', 'Team Lead','Employee'])) {
                    // Log::info("Admin login");
                    return redirect()->route('home');
                } /* else if(Auth::User()->hasRole('Team Lead')) {
                    return redirect()->route('teamLead');
                } else if(Auth::User()->hasRole('Employee')) {
                    return redirect()->route('employee');
                } */ else {
                    Log::info("going to hit logut");
                    return redirect()->route('logout');
                }
            } else {
                Log::info("login page");
                return view('login');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display a registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function register() {
        try {
            $roles = Role::pluck('name', 'id');
            $roles = $roles->prepend('Select Role', '')->toArray();
            return view('register', compact('roles'));
        }  catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            /* $request->validate([
                'userName' => 'required|string|max:250',
                'email' => 'required|email|max:250|unique:users',
                'role' => 'required|numeric|max:10',
                'password' => 'required|min:8|confirmed'
            ]); */
            $validator = Validator::make($request->all(), [
                'userName' => 'required|string|max:250',
                'email' => 'required|email|max:250|unique:users',
                'role' => 'required|numeric|max:10',
                'password' => 'required|min:8|confirmed'
            ]);
            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "messages" => $messages);
                return response()->json($response);
            }
     
            Log::info("request->first_name => ".$request->firstName);
            $latestUser = User::create([
                            'first_name' => $request->firstName,
                            'last_name' => $request->lastName,
                            'user_name' => $request->userName,
                            'mobile' => $request->mobile,
                            'email' => $request->email,
                            'role' => $request->role,
                            'status_id' => 1,
                            'password' => Hash::make($request->password)
                        ]);
            $lastInsertedId = $latestUser->id;
            Log::info("lastInsertedId => ".$lastInsertedId);
            if(isset($lastInsertedId) && $lastInsertedId != '') {
                
               /*  $roleUser = RoleUser::create([
                    'role_id' => $request->role,
                    'user_id' => $lastInsertedId,
                ]); */

                $roleUser = new RoleUser();
                $roleUser->role_id = $request->role;
                $roleUser->user_id = $lastInsertedId;
                $roleUser->save();
            }
    
            $credentials = $request->only('email', 'password');
            Auth::attempt($credentials);
            $request->session()->regenerate();
            // return redirect()->route('home')->withSuccess('You have successfully registered & logged in!');
            $response = array("status" => "success", "messages" => 'You have successfully registered & logged in!');
            return response()->json($response);
        }  catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login() {
        try {
            return view('login');
        }  catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                $messages = $validator->messages();
                $response = array("status" => "validation", "messages" => $messages);
                return response()->json($response);
            }
            $credentials = $request->only('email', 'password');
            // Log::info(print_r($credentials, true));
            if(Auth::attempt($credentials)) {
                if(Auth::check()) {
                    Log::info("Step1 Logged In Successfully userId => ".Auth::user()->id);
                } else {
                    Log::info("Step2 Logged In Successfully userId => ".Auth::user()->id);
                }
                $request->session()->regenerate();
                $response = array("status" => "success", "messages" => 'You have successfully logged in!');
                return response()->json($response);

                //return redirect()->route('dashboard')
                  //  ->withSuccess('You have successfully logged in!');
            } else {
                // $messages = ["email" => ["Your provided credentials do not match in our records."] ];
                $messages = "Your provided credentials do not match in our records.";
                $response = array("status" => "error", "messages" => $messages );
                return response()->json($response);
               /*  return back()->withErrors([
                    'email' => 'Your provided credentials do not match in our records.',
                ])->onlyInput('email'); */
            }
        }  catch (\Exception $e) {
            return $e->getMessage();
        }

    } 
    
    /**
     * Display a dashboard to authenticated users.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(){ 
        try {
            //if(Auth::check()) {
                return view('dashboard');
            //}
            
        }  catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withSuccess('You have logged out successfully!');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * 
     */
    public function organizationRegistration(Request $request) {
        try {
            $roles = Role::pluck('name', 'id');
            $roles = $roles->prepend('Select Role', '')->toArray();
            return view('registerOrganization', compact('roles'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveOrganizationInfo(Request $request) {
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

            $fullName  = $email = $nameofOrg =  $sizeofOrg = $industry = $subIndustry = NULL;
            $headQuarters  = $country = $organizationURL = $role = $password = $fileName = $dept = $filePath = NULL;
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
            if(isset($request->dept) && $request->dept != '') {
                $dept  = $request->dept;
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
                $newCompany = new Company();
                $newCompany->business_email =  $email;
                $newCompany->name_of_org =  $nameofOrg;
                $newCompany->size_of_org =  $sizeofOrg;
                $newCompany->industry =  $industry;
                $newCompany->sub_industry =  $subIndustry;
                $newCompany->head_quarters =  $headQuarters;
                $newCompany->country =  $country;
                $newCompany->org_url =  $organizationURL;
                $newCompany->img_path =  $filePath ;
                $newCompany->save();
                $companyId = $newCompany->id;

                // Log::info("request->fullName => ".$fullName);
                $latestUser = new User();
                $latestUser->full_name =  $fullName;
                $latestUser->email =  $email;
                $latestUser->password =  Hash::make($password);
                $latestUser->company_id =  $companyId;
                $latestUser->department =  $dept;
                /* $latestUser->name_of_org =  $nameofOrg;
                $latestUser->size_of_org =  $sizeofOrg;
                $latestUser->industry =  $industry;
                $latestUser->sub_industry =  $subIndustry;
                $latestUser->head_quarters =  $headQuarters;
                $latestUser->country =  $country;
                $latestUser->org_url =  $organizationURL; */
                $latestUser->role =  1; //Administrator
                $latestUser->img_path =  $filePath ;
                $latestUser->status_id =  1;
                $latestUser->save();
                            
                $lastInsertedId = $latestUser->id;
                // Log::info("lastInsertedId => ".$lastInsertedId);
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
            $credentials = $request->only('email', 'password');
            Auth::attempt($credentials);
            $request->session()->regenerate();
            // return redirect()->route('home')->withSuccess('You have successfully registered & logged in!');
            $response = array("status" => "success", "messages" => 'You have successfully registered & logged in!', 'addUser' => $addUser);
            return response()->json($response);
        }  catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    
    
    
}
