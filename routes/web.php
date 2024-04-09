<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginRegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeamLeaderController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GHGScopeOneController;
use App\Http\Controllers\GHGScopeTwoController;
use App\Http\Controllers\ReportsController;

use App\Http\Controllers\GHGScopeThreeController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/* Route::get('/', function () {
    return view('login');
    // return view('welcome');
}); */
// Route::get('/', [LoginRegisterController::class, 'index'])->name('login');
// return view('welcome');

//check role and redirect
Route::get('/', [LoginRegisterController::class, 'checkLogin'])->name('checkLogin');

//Login routes 
Route::controller(LoginRegisterController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    // Route::get('/home', 'index')->name('home');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/orgRegistration', 'organizationRegistration')->name('orgRegForm');
    Route::post('/saveOrgInfo', 'saveOrganizationInfo')->name('saveOrgInfo');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/logout1', 'logout')->name('logout');
});

Route::group(['middleware' => 'auth'], function () {
    //admin routes starts here
    Route::get('/home', [AdminController::class, 'index'])->name('home');
    Route::post('/saveAdminOrgInfo', [AdminController::class, 'saveAdminOrganizationInfo'])->name('saveAdminOrgInfo');
    Route::post('/updateUserInfo', [AdminController::class, 'updateUserInfo'])->name('updateUserInfo');
    Route::get('/getchooseView', [AdminController::class, 'getchooseView'])->name('getchooseView');
    Route::get('/getUsersView', [AdminController::class, 'getUsersView'])->name('getUsersView');
    Route::post('/getUsersList', [AdminController::class, 'getUsersList'])->name('getUsersList');
    Route::get('/addNewUser', [AdminController::class, 'addNewUser'])->name('addNewUser');
    Route::get('/userEdit', [AdminController::class, 'userEdit'])->name('userEdit');
    Route::get('/userDelete', [AdminController::class, 'userDelete'])->name('userDelete');
    Route::post('/userDelete', [AdminController::class, 'userDelete'])->name('userDelete');
    Route::get('/ghgEmissionsView', ['as' => 'ghgEmissionsMainView', AdminController::class, 'ghgEmissionsView'])->name('ghgEmissionsView');
    Route::get('/mobileghgEmissionsView', ['as' => 'mobileghgEmissionsView', AdminController::class, 'mobileghgEmissionsView'])->name('mobileghgEmissionsView');
    Route::get('/refrigerantsghgEmissionsView', ['as' => 'refrigerantsghgEmissionsView', AdminController::class, 'refrigerantsghgEmissionsView'])->name('refrigerantsghgEmissionsView');
   

    Route::get('/ElectricityofEvs', ['as' => 'ElectricityofEvsMainView', AdminController::class, 'ElectricityofEvs'])->name('purchaseElectricty');
    Route::get('/ElectricityPurchased', ['as' => 'ElectricityPurchasedMainView', AdminController::class, 'ElectricityPurchased'])->name('ElectricityPurchased');
    Route::get('/ElectricityofHeat', ['as' => 'ElectricityofHeatMainView', AdminController::class, 'ElectricityofHeat'])->name('ElectricityofHeat');
   
    
    
    
    Route::get('/ghgUserList', [AdminController::class, 'ghgUserList'])->name('ghgUserList');
    Route::get('/addGHGNewUser', [AdminController::class, 'addGHGNewUser'])->name('addGHGNewUser');
    Route::post('/upateUserRole', [AdminController::class, 'upateUserRole'])->name('upateUserRole');
    Route::post('/saveNewUser', [AdminController::class, 'saveNewUser'])->name('saveNewUser');
    Route::get('/ghgUserEditScreen/{id}', [AdminController::class, 'ghgUserEditScreen'])->name('ghgUserEditScreen');
    Route::get('/reports', [ReportsController::class, 'reports'])->name('reports');

    //scope three navigation start here.
    Route::get('/purchasedgoodsandservices', [GHGScopeThreeController::class, 'purchasedgoodsandservices'])->name('purchasedgoodsandservices');
    Route::get('/fuelandenergy', [GHGScopeThreeController::class, 'fuelandenergy'])->name('fuelandenergy');
    Route::get('/capitalgoods', [GHGScopeThreeController::class, 'capitalgoods'])->name('capitalgoods');
    Route::get('/waste', [GHGScopeThreeController::class, 'waste'])->name('waste');
    Route::get('/businesstravel', [GHGScopeThreeController::class, 'businesstravel'])->name('businesstravel');
    Route::get('/employeecommute', [GHGScopeThreeController::class, 'employeecommute'])->name('employeecommute');
    Route::get('/downstream', [GHGScopeThreeController::class, 'downstream'])->name('downstream');
    Route::get('/soldproducts', [GHGScopeThreeController::class, 'soldproducts'])->name('soldproducts');

    Route::get('/ghgReportData', [AdminController::class, 'ghgReportData'])->name('ghgReportData');
    //teamLead routes starts here 
    Route::get('/teamLead', [TeamLeaderController::class, 'index'])->name('teamLead');

    //employee routes starts here
    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee');
    

    Route::post('/typeOfTransport', [GHGScopeThreeController::class, 'typeOfTransport'])->name('typeOfTransport');

    Route::controller(GHGScopeOneController::class)->group(function () {

        Route::post('/getunitofmeasument', 'getunitofmeasument')->name('getunitofmeasument');

        
        Route::get('/getGHGQuestionnaire', 'getGHGQuestionnaire')->name('getGHGQuestionnaire');
        Route::get('/mobilegetGHGQuestionnaire', 'mobilegetGHGQuestionnaire')->name('mobilegetGHGQuestionnaire');
        Route::get('/refrigerantsgetGHGQuestionnaire', 'refrigerantsgetGHGQuestionnaire')->name('refrigerantsgetGHGQuestionnaire');
        
        Route::post('/saveStationaryCombution', 'saveStationaryCombution')->name('saveStationaryCombution');
        Route::post('/mobilesaveStationaryCombution', 'mobilesaveStationaryCombution')->name('mobilesaveStationaryCombution');
        Route::post('/refrigerantssaveStationaryCombution', 'refrigerantssaveStationaryCombution')->name('refrigerantssaveStationaryCombution');

        Route::post('/saveStationaryCombutionscope2', 'saveStationaryCombutionscope2')->name('saveStationaryCombutionscope2');


        Route::post('/updateCombustionInfo', 'updateCombustionInfo')->name('updateCombustionInfo');
        Route::post('/updateMobileCombustionInfo', 'updateMobileCombustionInfo')->name('updateMobileCombustionInfo');
        Route::post('/updateRefrigerantsCombustionInfo', 'updateRefrigerantsCombustionInfo')->name('updateRefrigerantsCombustionInfo');
       
        
        Route::post('/deleteStationaryCombution', 'deleteStationaryCombution')->name('deleteStationaryCombution');
        Route::post('/deleteMobileCombution', 'deleteMobileCombution')->name('deleteMobileCombution');
        Route::post('/deleteRefrigerantsCombution', 'deleteRefrigerantsCombution')->name('deleteRefrigerantsCombution');


        Route::get('/getRefreshStationaryCombution', 'getRefreshStationaryCombution')->name('getRefreshStationaryCombution');
        Route::get('/getRefreshMobileCombution', 'getRefreshMobileCombution')->name('getRefreshMobileCombution');
        Route::get('/getRefreshRefrigerantsCombution', 'getRefreshRefrigerantsCombution')->name('getRefreshRefrigerantsCombution');
       
        
        Route::get('/editFuel', 'editFuel')->name('editFuel');
        Route::get('/editmobileFuel', 'editmobileFuel')->name('editmobileFuel');
        Route::get('/editrefrigerantsFuel', 'editrefrigerantsFuel')->name('editrefrigerantsFuel');
    });

    Route::controller(GHGScopeTwoController::class)->group(function() {
         Route::get('/getElectricityofEvs', 'getElectricityofEvs')->name('getElectricityofEvs');
         Route::post('/saveElectricityofEvs', 'saveElectricityofEvs')->name('saveElectricityofEvs');
         Route::post('/updateElectricityofEvs', 'updateElectricityofEvs')->name('updateElectricityofEvs');
         Route::post('/deleteElectricityofEvs', 'deleteElectricityofEvs')->name('deleteElectricityofEvs');
         Route::get('/getRefreshElectricityofEvs', 'getRefreshElectricityofEvs')->name('getRefreshElectricityofEvs');
         Route::get('/editElectricityofEvs', 'editElectricityofEvs')->name('editElectricityofEvs');

         Route::get('/getElectricityPurchased', 'getElectricityPurchased')->name('getElectricityPurchased');
         Route::post('/saveElectricityPurchased', 'saveElectricityPurchased')->name('saveElectricityPurchased');
         Route::post('/updateElectricityPurchased', 'updateElectricityPurchased')->name('updateElectricityPurchased');
         Route::post('/deleteElectricityPurchased', 'deleteElectricityPurchased')->name('deleteElectricityPurchased');
         Route::get('/getRefreshElectricityPurchased', 'getRefreshElectricityPurchased')->name('getRefreshElectricityPurchased');
         Route::get('/editElectricityPurchased', 'editElectricityPurchased')->name('editElectricityPurchased');

         Route::get('/getElectricityofHeat', 'getElectricityofHeat')->name('getElectricityofHeat');
         Route::post('/saveElectricityofHeat', 'saveElectricityofHeat')->name('saveElectricityofHeat');
         Route::post('/updateElectricityofHeat', 'updateElectricityofHeat')->name('updateElectricityofHeat');
         Route::post('/deleteElectricityofHeat', 'deleteElectricityofHeat')->name('deleteElectricityofHeat');
         Route::get('/getRefreshElectricityofHeat', 'getRefreshElectricityofHeat')->name('getRefreshElectricityofHeat');
         Route::get('/editElectricityofHeat', 'editElectricityofHeat')->name('editElectricityofHeat');
        
     });

    

    Route::controller(GHGScopeThreeController::class)->group(function () {
        Route::post('/savePurchaseGoods', 'savePurchaseGoods')->name('savePurchaseGoods');
        Route::post('/editPurchaseGoods', 'editPurchaseGoods')->name('editPurchaseGoods');
        Route::post('/updatePurchaseGoods', 'updatePurchaseGoods')->name('updatePurchaseGoods');
        Route::post('/deletePurchaseGoods', 'deletePurchaseGoods')->name('deletePurchaseGoods');
        
        Route::post('/saveCapitalGoods', 'saveCapitalGoods')->name('saveCapitalGoods');
        Route::post('/editCapitalGoods', 'editCapitalGoods')->name('editCapitalGoods');
        Route::post('/updateCaptialGoods', 'updateCaptialGoods')->name('updateCaptialGoods');
        Route::post('/deleteCapitalGoods', 'deleteCapitalGoods')->name('deleteCapitalGoods');
        
        Route::post('/saveWaste', 'saveWaste')->name('saveWaste');
        Route::post('/editWaste', 'editWaste')->name('editWaste');
        Route::post('/updateWaste', 'updateWaste')->name('updateWaste');
        Route::post('/deleteWaste', 'deleteWaste')->name('deleteWaste');
        
        Route::post('/saveBusinessTravel', 'saveBusinessTravel')->name('saveBusinessTravel');
        Route::post('/editBusinessTravel', 'editBusinessTravel')->name('editBusinessTravel');
        Route::post('/updateBusinessTravel', 'updateBusinessTravel')->name('updateBusinessTravel');
        Route::post('/deleteBusinessTravel', 'deleteBusinessTravel')->name('deleteBusinessTravel');
        
        Route::post('/saveEmployeeCommute', 'saveEmployeeCommute')->name('saveEmployeeCommute');
        Route::post('/editEmployeeCommute', 'editEmployeeCommute')->name('editEmployeeCommute');
        Route::post('/updateEmployeeCommute', 'updateEmployeeCommute')->name('updateEmployeeCommute');
        Route::post('/deleteEmployeeCommute', 'deleteEmployeeCommute')->name('deleteEmployeeCommute');
        
        Route::post('/saveDownStream', 'saveDownStream')->name('saveDownStream');
        Route::post('/editDownStream', 'editDownStream')->name('editDownStream');
        Route::post('/updateDownStream', 'updateDownStream')->name('updateDownStream');
        Route::post('/deleteDownStream', 'deleteDownStream')->name('deleteDownStream');
    });

    
});

Route::get('/view/{viewName}', function (string $viewName) {
    return view($viewName);
});

Route::get('/rbn', function () {
    $pastYears = 5;
    $currentYear = date('Y');
    $startYear = date('Y') - $pastYears;
    $yearsArray = [];
    // echo "currentYear => ".$currentYear." pastYears => ".$pastYears."<br>";
    for ($i = $startYear; $i <= $currentYear; $i++) {
        $yearsArray[] = $startYear . "-" . $startYear + 1;
        $startYear = $startYear + 1;
    }
    rsort($yearsArray);
    print_r($yearsArray);
    exit();

    $userCompany =  \App\Models\User::find(Auth::user()->id)->roles;
    // $userCompany =  \App\Models\Company::find(Auth::user()->company_id)->user;
    echo "<pre>";
    print_r($userCompany);
    exit();

    $userRole = \App\Models\User::find(Auth::user()->id)->roles[0]->name;
    echo "userRole => " . $userRole;
    exit();

    $destinationPath = 'uploads';
    $fileName  = 'uploads/1702491425_Screenshot (7).png';
    if (File::exists(public_path($fileName))) {
        echo 'File Exists';
        unlink(public_path($fileName));
    } else {
        echo "File Doesn't Exists";
    }
    exit();

    $id = 5;
    $encryptedId = Crypt::encrypt($id);
    echo "encryptedId => " . $encryptedId . "<br>";
    $decryptedId = Crypt::decrypt($encryptedId);
    echo "decryptedId => " . $decryptedId;
    exit();
    echo public_path('uploads');
    echo "<br>";
    echo public_path('files');
});
