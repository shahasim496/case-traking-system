<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskLogController;
use App\Http\Controllers\EvidenceController;

use App\Http\Controllers\SubdivisionController;
use App\Http\Controllers\WitnessFileController;
use App\Http\Controllers\NotificationController;
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

Route::group(['prefix' => 'job', 'middleware' => ['auth', 'banned']], function () {
    
    Route::get('/posting', [App\Http\Controllers\HomeController::class, 'index'])->name('job.posting');
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
    Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users')->middleware('permission:view user');
    Route::get('/all', [App\Http\Controllers\UserController::class, 'all_users'])->name('user.all')->middleware('permission:view user');
    Route::get('/getUsers', [App\Http\Controllers\UserController::class, 'getUsers'])->name('user.getUsers')->middleware('permission:view user');
    Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('user.create')->middleware('permission:create user');
    Route::get('/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('user.edit')->middleware('permission:edit user'); 
});



Route::group(['prefix' => 'user', 'middleware' => ['auth', 'banned']], function () {
   


    Route::post('/update_password/{id}', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('user.updatePassword');
    Route::get('/resend_code', [App\Http\Controllers\UserController::class, 'resendCode'])->name('user.resendCode');


    Route::get('/sendcode', [App\Http\Controllers\UserController::class, 'sendCode'])->name('user.sendCode');
    Route::get('/verifycode', [App\Http\Controllers\UserController::class, 'verifyCode'])->name('user.verifyCode');
    Route::post('/verifyUserCode', [App\Http\Controllers\UserController::class, 'verifyUserCode'])->name('user.verifyUserCode');
    Route::post('/reset_password', [App\Http\Controllers\UserController::class, 'resetPassword'])->name('user.resetPassword');
    Route::post('/store', [App\Http\Controllers\UserController::class, 'store'])->name('user.store')->middleware('permission:create user');
    Route::post('/storeOfficer', [App\Http\Controllers\UserController::class, 'storeOfficer'])->name('user.storeOfficer')->middleware('permission:create user');
    Route::put('/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update')->middleware('permission:edit user');
    Route::put('/updateOfficer/{id}', [App\Http\Controllers\UserController::class, 'updateOfficer'])->name('user.updateOfficer')->middleware('role:SuperAdmin|Admin');
    Route::get('/delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('user.delete')->middleware('permission:delete user');

    Route::get('/markNotification', [App\Http\Controllers\UserController::class, 'markNotification'])->name('user.markNotification');
    Route::get('/banned/{id}', [App\Http\Controllers\UserController::class, 'banned'])->name('user.banned')->middleware('permission:ban user');
});



Route::group(['prefix' => 'user', 'middleware' => ['auth', 'banned']], function () {
    Route::get('/change/password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('user.changePassword');
    Route::post('/change-password', [App\Http\Controllers\UserController::class, 'savePassword'])->name('user.savePassword');
});





Route::group(['prefix' => 'departments', 'middleware' => ['auth', 'banned', 'permission:manage settings']], function () {
    Route::get('/', [App\Http\Controllers\DepartmentController::class, 'index'])->name('departments');
    Route::get('/getDepartments', [App\Http\Controllers\DepartmentController::class, 'getDepartments'])->name('departments.getDepartments');
    Route::get('/create', [App\Http\Controllers\DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/store', [App\Http\Controllers\DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/edit/{id}', [App\Http\Controllers\DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/update/{id}', [App\Http\Controllers\DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/delete/{id}', [App\Http\Controllers\DepartmentController::class, 'delete'])->name('departments.delete');
});

Route::group(['prefix' => 'designations', 'middleware' => ['auth', 'banned', 'permission:manage settings']], function () {
    Route::get('/', [App\Http\Controllers\DesignationController::class, 'index'])->name('designations');
    Route::get('/getDesignations', [App\Http\Controllers\DesignationController::class, 'getDesignations'])->name('designations.getDesignations');
    Route::get('/create', [App\Http\Controllers\DesignationController::class, 'create'])->name('designations.create');
    Route::post('/store', [App\Http\Controllers\DesignationController::class, 'store'])->name('designations.store');
    Route::get('/edit/{id}', [App\Http\Controllers\DesignationController::class, 'edit'])->name('designations.edit');
    Route::put('/update/{id}', [App\Http\Controllers\DesignationController::class, 'update'])->name('designations.update');
    Route::delete('/delete/{id}', [App\Http\Controllers\DesignationController::class, 'delete'])->name('designations.delete');
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

Route::group(['prefix' => 'roles', 'middleware' => ['auth', 'banned', 'permission:manage role and permissions']], function () {
    Route::get('/', [App\Http\Controllers\RoleController::class, 'index'])->name('roles')->middleware('permission:view role');
    Route::get('/getRoles', [App\Http\Controllers\RoleController::class, 'getRoles'])->name('roles.getRoles')->middleware('permission:view role');
    Route::get('/create', [App\Http\Controllers\RoleController::class, 'create'])->name('roles.create')->middleware('permission:create role');
    Route::post('/store', [App\Http\Controllers\RoleController::class, 'store'])->name('roles.store')->middleware('permission:create role');
    Route::get('/edit/{id}', [App\Http\Controllers\RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:edit role');
    Route::put('/update/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('roles.update')->middleware('permission:edit role');
    Route::get('/delete/{id}', [App\Http\Controllers\RoleController::class, 'delete'])->name('roles.delete')->middleware('permission:delete role');
 
    Route::get('/assign-permissions/{id}', [App\Http\Controllers\RoleController::class, 'assignPermissions'])->name('roles.assignPermissions')->middleware('permission:manage permission assignment');
    Route::get('/roles/assign-permissions', [App\Http\Controllers\RoleController::class, 'managePermissions'])->name('roles.managePermissions')->middleware('permission:manage permission assignment');
    Route::post('/roles/assign-permissions', [App\Http\Controllers\RoleController::class, 'storeAssignedPermissions'])->name('roles.storeAssignedPermissions')->middleware('permission:manage permission assignment');


});



Route::group(['prefix' => 'permissions', 'middleware' => ['auth', 'banned']], function () {
    Route::get('/', [App\Http\Controllers\PermissionController::class, 'index'])->name('permissions')->middleware('permission:view permission');
    Route::get('/getPermissions', [App\Http\Controllers\PermissionController::class, 'getPermissions'])->name('permissions.getPermissions')->middleware('permission:view permission')  ;
    Route::get('/create', [App\Http\Controllers\PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:create permission');
    Route::post('/store', [App\Http\Controllers\PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:create permission');
    Route::get('/edit/{id}', [App\Http\Controllers\PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:edit permission');
    Route::put('/update/{id}', [App\Http\Controllers\PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:edit permission');
    Route::delete('/delete/{id}', [App\Http\Controllers\PermissionController::class, 'delete'])->name('permissions.delete')->middleware('permission:delete permission');
});





Route::group([ 'middleware' => ['auth', 'banned']], function () {
    
Route::get('/get-subdivisions/{unitId}', [AdministrativeUnitController::class, 'getSubdivisions']);

Route::get('/get-police-stations/{subdivisionId}', [AdministrativeUnitController::class, 'getPoliceStations']);

Route::resource('admin-units', AdministrativeUnitController::class)->middleware('permission:manage settings');
Route::resource('subdivisions', SubdivisionController::class)->middleware('permission:manage settings');
Route::resource('police-stations', PoliceStationController::class)->middleware('permission:manage settings');












Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

});




