<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskLogController;
use App\Http\Controllers\SubdivisionController;
use App\Http\Controllers\WitnessFileController;

use App\Http\Controllers\PoliceStationController;
use App\Http\Controllers\CourtProceedingController;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\AdministrativeUnitController;
use App\Http\Controllers\InvestigationDocumentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



// Route to display the Create Case page


Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/admin/dashboard', [App\Http\Controllers\HomeController::class, 'admin_dashboard'])->name('admin_dashboard');

Route::post('/sendcode', [App\Http\Controllers\SendCodeController::class, 'index'])->name('sendcode');
Route::get('/verifycode', [App\Http\Controllers\SendCodeController::class, 'verifycode'])->name('verifycode');
Route::post('/verifycode', [App\Http\Controllers\SendCodeController::class, 'verifyUserCode'])->name('verifyUserCode');
Route::post('/reset_password', [App\Http\Controllers\SendCodeController::class, 'resetPassword'])->name('resetPassword');
// Route::post('/register', [App\Http\Controllers\RegistrationController::class, 'userRegister'])->name('userRegister');
Route::get('/resend_code', [App\Http\Controllers\SendCodeController::class, 'resendCode'])->name('resendCode');

Route::group(['prefix' => 'user', 'middleware' => ['auth', 'banned']], function () {

    //users 
    Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('/all', [App\Http\Controllers\UserController::class, 'all_users'])->name('user.all');
    Route::get('/getUsers', [App\Http\Controllers\UserController::class, 'getUsers'])->name('user.getUsers');
    Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('user.create');
    //roles and permissions






    Route::get('/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');
    Route::get('/edit/officer/{id}', [App\Http\Controllers\UserController::class, 'editOfficer'])->name('user.editOfficer')->middleware('role:SuperAdmin|Admin');
    Route::get('/profile_setting/{id}', [App\Http\Controllers\UserController::class, 'profileSetting'])->name('user.profile');
    Route::post('/profile_setting/{id}', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('user.updateProfile');
    Route::post('/update_password/{id}', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('user.updatePassword');
    Route::get('/resend_code', [App\Http\Controllers\UserController::class, 'resendCode'])->name('user.resendCode');
    Route::get('/my/application', [App\Http\Controllers\UserController::class, 'myApplication'])->name('user.myApplication');

    Route::get('/sendcode', [App\Http\Controllers\UserController::class, 'sendCode'])->name('user.sendCode');
    Route::get('/verifycode', [App\Http\Controllers\UserController::class, 'verifyCode'])->name('user.verifyCode');
    Route::post('/verifyUserCode', [App\Http\Controllers\UserController::class, 'verifyUserCode'])->name('user.verifyUserCode');
    Route::post('/reset_password', [App\Http\Controllers\UserController::class, 'resetPassword'])->name('user.resetPassword');
    Route::post('/store', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
    Route::post('/storeOfficer', [App\Http\Controllers\UserController::class, 'storeOfficer'])->name('user.storeOfficer');
    Route::put('/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');
    Route::put('/updateOfficer/{id}', [App\Http\Controllers\UserController::class, 'updateOfficer'])->name('user.updateOfficer')->middleware('role:SuperAdmin|Admin');
    Route::get('/delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('user.delete');

    Route::get('/markNotification', [App\Http\Controllers\UserController::class, 'markNotification'])->name('user.markNotification');
    Route::get('/banned/{id}', [App\Http\Controllers\UserController::class, 'banned'])->name('user.banned');
});



Route::group(['prefix' => 'user', 'middleware' => ['auth', 'banned']], function () {
    Route::get('/change/password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('user.changePassword');
    Route::post('/change-password', [App\Http\Controllers\UserController::class, 'savePassword'])->name('user.savePassword');
});





Route::group(['prefix' => 'departments', 'middleware' => ['auth', 'banned']], function () {
    Route::get('/', [App\Http\Controllers\DepartmentController::class, 'index'])->name('departments');
    Route::get('/getDepartments', [App\Http\Controllers\DepartmentController::class, 'getDepartments'])->name('departments.getDepartments');
    Route::get('/create', [App\Http\Controllers\DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/store', [App\Http\Controllers\DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/edit/{id}', [App\Http\Controllers\DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/update/{id}', [App\Http\Controllers\DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/delete/{id}', [App\Http\Controllers\DepartmentController::class, 'delete'])->name('departments.delete');
});

Route::get('/getModelData', [App\Http\Controllers\AppController::class, 'getModelData'])->name('getModelData');

Route::group(['middleware' => ['role:SuperAdmin|Admin|Cadre', 'auth', 'banned']], function () {
    Route::resource('roles-permissions', RoleAndPermissionController::class)->parameters(['roles-permissions' => 'role'])->only([
        'index',
        'show',
        'store',
        'update'
    ]);
});

Route::group(['prefix' => 'roles', 'middleware' => ['auth', 'banned']], function () {
    Route::get('/', [App\Http\Controllers\RoleController::class, 'index'])->name('roles');
    Route::get('/getRoles', [App\Http\Controllers\RoleController::class, 'getRoles'])->name('roles.getRoles');
    Route::get('/create', [App\Http\Controllers\RoleController::class, 'create'])->name('roles.create');
    Route::post('/store', [App\Http\Controllers\RoleController::class, 'store'])->name('roles.store');
    Route::get('/edit/{id}', [App\Http\Controllers\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/update/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');
    Route::get('/delete/{id}', [App\Http\Controllers\RoleController::class, 'delete'])->name('roles.delete');
    Route::delete('/roles/delete/{id}', [App\Http\Controllers\RoleController::class, 'delete'])->name('roles.delete');


    Route::get('/assign-permissions/{id}', [App\Http\Controllers\RoleController::class, 'assignPermissions'])->name('roles.assignPermissions');
    Route::post('/assign-permissions/{id}', [App\Http\Controllers\RoleController::class, 'storeAssignedPermissions'])->name('roles.storeAssignedPermissions');
    Route::get('/roles/assign-permissions', [App\Http\Controllers\RoleController::class, 'managePermissions'])->name('roles.managePermissions');
    Route::post('/roles/assign-permissions', [App\Http\Controllers\RoleController::class, 'storeAssignedPermissions'])->name('roles.storeAssignedPermissions');


});



Route::group(['prefix' => 'permissions', 'middleware' => ['auth', 'banned']], function () {
    Route::get('/', [App\Http\Controllers\PermissionController::class, 'index'])->name('permissions');
    Route::get('/getPermissions', [App\Http\Controllers\PermissionController::class, 'getPermissions'])->name('permissions.getPermissions');
    Route::get('/create', [App\Http\Controllers\PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/store', [App\Http\Controllers\PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/edit/{id}', [App\Http\Controllers\PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/update/{id}', [App\Http\Controllers\PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/delete/{id}', [App\Http\Controllers\PermissionController::class, 'delete'])->name('permissions.delete');
});

Route::group(['prefix' => 'casetype', 'middleware' => ['auth', 'banned']], function () {
    Route::get('/', [App\Http\Controllers\CaseTypeController::class, 'index'])->name('cases'); // Main index route
    Route::get('/getCases', [App\Http\Controllers\CaseTypeController::class, 'getCases'])->name('cases.getCases'); // DataTable route
    Route::get('/create', [App\Http\Controllers\CaseTypeController::class, 'create'])->name('cases.create'); // Create case route
    Route::post('/store', [App\Http\Controllers\CaseTypeController::class, 'store'])->name('cases.store'); // Store case route
    Route::get('/edit/{id}', [App\Http\Controllers\CaseTypeController::class, 'edit'])->name('cases.edit'); // Edit case route
    Route::put('/update/{id}', [App\Http\Controllers\CaseTypeController::class, 'update'])->name('cases.update'); // Update case route
    Route::delete('/delete/{id}', [App\Http\Controllers\CaseTypeController::class, 'delete'])->name('cases.delete'); // Delete case route
});

Route::group(['prefix' => 'designations', 'middleware' => ['auth', 'banned']], function () {
    Route::get('/', [App\Http\Controllers\DesignationController::class, 'index'])->name('designations');
    Route::get('/getDesignations', [App\Http\Controllers\DesignationController::class, 'getDesignations'])->name('designations.getDesignations');
    Route::get('/create', [App\Http\Controllers\DesignationController::class, 'create'])->name('designations.create');
    Route::post('/store', [App\Http\Controllers\DesignationController::class, 'store'])->name('designations.store');
    Route::get('/edit/{id}', [App\Http\Controllers\DesignationController::class, 'edit'])->name('designations.edit');
    Route::put('/update/{id}', [App\Http\Controllers\DesignationController::class, 'update'])->name('designations.update');
    Route::delete('/delete/{id}', [App\Http\Controllers\DesignationController::class, 'delete'])->name('designations.delete');
});



Route::group(['prefix' => 'case', 'middleware' => ['auth', 'banned']], function () {
    Route::get('/create', [App\Http\Controllers\CaseController::class, 'create'])->name('casess.create');
    Route::post('/store', [App\Http\Controllers\CaseController::class, 'store'])->name('casess.store');
    Route::get('/cases', [App\Http\Controllers\CaseController::class, 'index'])->name('casess.index');
   
    Route::get('/cases/{id}/edit', [App\Http\Controllers\CaseController::class, 'edit'])->name('casess.edit');
    Route::delete('/cases/{id}', [App\Http\Controllers\CaseController::class, 'destroy'])->name('casess.destroy');



Route::put('/cases/{id}', [App\Http\Controllers\CaseController::class, 'update'])->name('casess.update');

Route::post('/documents/store/{case_id}', [InvestigationDocumentController::class, 'store'])->name('documents.store');

Route::put('/documents/{id}', [App\Http\Controllers\InvestigationDocumentController::class, 'update'])->name('documents.update');
Route::delete('/documents/{id}', [App\Http\Controllers\InvestigationDocumentController::class, 'destroy'])->name('documents.destroy');


Route::post('/court-proceedings/store/{case_id}', [CourtProceedingController::class, 'store'])->name('court-proceedings.store');
Route::put('/court-proceedings/{id}', [App\Http\Controllers\CourtProceedingController::class, 'update'])->name('court-proceedings.update');
Route::delete('/court-proceedings/{id}', [App\Http\Controllers\CourtProceedingController::class, 'destroy'])->name('court-proceedings.destroy');


Route::post('/evidences/store/{case_id}', [App\Http\Controllers\EvidenceController::class, 'store'])->name('evidences.store');
Route::put('/evidences/{id}', [App\Http\Controllers\EvidenceController::class, 'update'])->name('evidences.update');
Route::delete('/evidences/{id}', [App\Http\Controllers\EvidenceController::class, 'destroy'])->name('evidences.destroy');


Route::post('/witnesses/store/{case_id}', [App\Http\Controllers\WitnessController::class, 'store'])->name('witnesses.store');

Route::put('/witnesses/{id}', [App\Http\Controllers\WitnessController::class, 'update'])->name('witnesses.update');

Route::delete('/witness-files/{id}', [App\Http\Controllers\WitnessController::class, 'destroy'])->name('witness-files.destroy');

Route::delete('/witness-filess/{id}', [App\Http\Controllers\WitnessFileController::class, 'destroy'])->name('witness-filess.destroy');


});


Route::group([ 'middleware' => ['auth', 'banned']], function () {
    
Route::get('/get-subdivisions/{unitId}', [AdministrativeUnitController::class, 'getSubdivisions']);

Route::get('/get-police-stations/{subdivisionId}', [AdministrativeUnitController::class, 'getPoliceStations']);

Route::resource('admin-units', AdministrativeUnitController::class);
Route::resource('subdivisions', SubdivisionController::class);
Route::resource('police-stations', PoliceStationController::class);




Route::get('/get-case-officers', [App\Http\Controllers\CaseController::class, 'getCaseOfficers'])->name('get.case.officers');
Route::get('/get-case-investigation-officers', [App\Http\Controllers\CaseController::class, 'getinvestigationOfficers'])->name('get.case.investigation.officers');
Route::get('/get-case-senior-investigation-officers', [App\Http\Controllers\CaseController::class, 'getseniorinvestigationOfficers'])->name('get.case.senior.investigation.officers');
Route::get('/get-station-sergeants', [App\Http\Controllers\CaseController::class, 'getStationSergeants'])->name('get.station.sergeants');

Route::get('/get-subdivisional-officer', [App\Http\Controllers\CaseController::class, 'getSubdivisionalOfficer'])->name('get.subdivisionalofficer');

Route::get('/get-commanders', [App\Http\Controllers\CaseController::class, 'getCommanders'])->name('get.commanders');

Route::get('/get-dpp-pca', [App\Http\Controllers\CaseController::class, 'getDppPca'])->name('get.dpp.pca');
Route::post('/cases/{id}/take-action', [App\Http\Controllers\CaseController::class, 'takeAction'])->name('cases.takeAction');

Route::get('/task-logs/{case_id}', [TaskLogController::class, 'index'])->name('taskLogs.index');
    
});




