<?php


use App\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomsController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\FrontDeskController;
use App\Http\Controllers\HousekeepingController;

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

// set side bar active dynamic
function set_active($route)
{
    if (is_array($route)) {
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}
//Login
Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('home', function () {
        return view('home');
    });
    Route::get('home', function () {
        return view('home');
    });
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/auth.php';
Auth::routes();

//main dashboard
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->name('home');
    Route::get('/profile', 'profile')->name('profile');
});

//login and logout
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

//register
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'storeUser')->name('register');
});

// forgor password
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('forget-password', 'getEmail')->name('forget-password');
    Route::post('forget-password', 'postEmail')->name('forget-password');
});

//reset password
Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('reset-password/{token}', 'getPassword');
    Route::post('reset-password', 'updatePassword');
});

//booking
Route::controller(BookingController::class)->group(function () {
    Route::get('form/allbooking', 'allbooking')->name('form/allbooking')->middleware('auth');
    Route::get('form/booking/edit/{bkg_id}', 'bookingEdit')->middleware('auth');
    Route::get('form/booking/add', 'bookingAdd')->middleware('auth')->name('form/booking/add');
    Route::post('form/booking/save', 'saveRecord')->middleware('auth')->name('form/booking/save');
    Route::post('form/booking/update', 'updateRecord')->middleware('auth')->name('form/booking/update');
    Route::post('form/booking/delete', 'deleteRecord')->middleware('auth')->name('form/booking/delete');
});

//customer
Route::controller(CustomerController::class)->group(function () {
    Route::get('form/allcustomers/page', 'allCustomers')->middleware('auth')->name('form/allcustomers/page');
    Route::get('form/addcustomer/page', 'addCustomer')->middleware('auth')->name('form/addcustomer/page');
    Route::post('form/addcustomer/save', 'saveCustomer')->middleware('auth')->name('form/addcustomer/save');
    Route::get('form/customer/edit/{bkg_customer_id}', 'updateCustomer')->middleware('auth');
    Route::post('form/customer/update', 'updateRecord')->middleware('auth')->name('form/customer/update');
    Route::post('form/customer/delete', 'deleteRecord')->middleware('auth')->name('form/customer/delete');
});

//room
Route::controller(RoomsController::class)->group(function () {
    Route::get('form/allrooms/page', 'allrooms')->middleware('auth')->name('form/allrooms/page');
    Route::get('form/addroom/page', 'addRoom')->middleware('auth')->name('form/addroom/page');
    Route::get('form/room/edit/{bkg_room_id}', 'editRoom')->middleware('auth');
    Route::post('form/room/save', 'saveRecordRoom')->middleware('auth')->name('form/room/save');
    Route::post('form/room/delete', 'deleteRecord')->middleware('auth')->name('form/room/delete');
    Route::post('form/room/update', 'updateRecord')->middleware('auth')->name('form/room/update');
});

//room types
Route::controller(RoomTypeController::class)->middleware('auth')->group(function () {
    Route::get('form/roomtype/list', 'index')->name('roomtype.index');
    Route::get('form/roomtype/create', 'create')->name('roomtype.create');
    Route::post('form/roomtype/store', 'store')->name('roomtype.store');
    Route::get('form/roomtype/edit/{id}', 'edit')->name('roomtype.edit');
    Route::put('form/roomtype/update/{id}', 'update')->name('roomtype.update');
    Route::delete('form/roomtype/delete/{id}', 'destroy')->name('roomtype.destroy');
    Route::post('form/roomtype/available-rooms', 'getAvailableRoomsForDates')->name('roomtype.available-rooms');
});

// // ----------------------- user management -------------------------//
// Route::controller(UserManagementController::class)->group(function () {
//     Route::get('users/list/page', 'userList')->middleware('auth')->name('users/list/page');
//     Route::get('users/add/new', 'userAddNew')->middleware('auth')->name('users/add/new');
//     /** add new users */
//     Route::get('users/add/edit/{user_id}', 'userView');
//     /** add new users */
//     Route::post('users/update', 'userUpdate')->name('users/update');
//     /** update record */
//     Route::get('users/delete/{id}', 'userDelete')->name('users/delete');
//     /** delere record */
//     Route::get('get-users-data', 'getUsersData')->name('get-users-data');
//     /** get all data users */
// });

//user management
Route::controller(UserManagementController::class)->group(function () {
    Route::get('users/list/page', 'userList')->middleware('auth')->name('users/list/page');
    Route::get('users/add/new', 'userAddNew')->middleware('auth')->name('users/add/new');
    Route::get('users/add/edit/{user_id}', 'userView');
    Route::post('users/update', 'userUpdate')->name('users/update');
    Route::get('users/delete/{id}', 'userDelete')->name('users/delete');
    Route::get('get-users-data', 'getUsersData')->name('get-users-data');
    // Add this if you need a separate route for getting roles
    Route::get('get-roles', 'getRoles')->name('get-roles');
});


Route::controller(EmployeeController::class)->middleware('auth')->group(function () {
    Route::get('form/employee/list', 'employeesList')->name('form.employee.list');
    Route::get('form/employee/add', 'employeesAdd')->name('form.employee.add');
    Route::post('form/employee/save', 'saveEmployee')->name('form.employee.save');
    Route::get('form/employee/edit/{id}', 'editEmployee')->name('form.employee.edit');
    Route::post('form/employee/update/{id}', 'updateEmployee')->name('form.employee.update');
    Route::delete('form/employee/delete/{id}', 'deleteEmployee')->name('form.employee.delete');
});

//role management
Route::resource('role', RoleController::class)->middleware('auth');

//leavetype
Route::get('/leavetype', [LeaveTypeController::class, 'index'])->name('leavetype.index');
Route::get('/leavetype/create', [LeaveTypeController::class, 'create'])->name('leavetype.create');
Route::post('/leavetype', [LeaveTypeController::class, 'store'])->name('leavetype.store');
Route::get('/leavetype/{id}/edit', [LeaveTypeController::class, 'edit'])->name('leavetype.edit');
Route::put('/leavetype/{id}', [LeaveTypeController::class, 'update'])->name('leavetype.update');
Route::delete('/leavetype/{id}', [LeaveTypeController::class, 'destroy'])->name('leavetype.destroy');

//leave
Route::resource('leave', LeaveController::class)->middleware('auth');
Route::get('/leave/{leave}/approve', [LeaveController::class, 'approve'])->name('leave.approve')->middleware('auth');
Route::get('/leave/{leave}/cancel', [LeaveController::class, 'cancel'])->name('leave.cancel')->middleware('auth');

//front desk operations
Route::controller(FrontDeskController::class)->middleware('auth')->group(function () {
    Route::get('frontdesk/arrivals', 'todaysArrivals')->name('frontdesk.arrivals');
    Route::get('frontdesk/departures', 'todaysDepartures')->name('frontdesk.departures');
    Route::get('frontdesk/in-house', 'inHouseGuests')->name('frontdesk.in-house');
    Route::get('frontdesk/checkin/{booking}', 'checkIn')->name('frontdesk.checkin');
    Route::post('frontdesk/checkin/{booking}', 'processCheckIn')->name('frontdesk.process-checkin');
    Route::get('frontdesk/checkout/{booking}', 'checkOut')->name('frontdesk.checkout');
    Route::post('frontdesk/checkout/{booking}', 'processCheckOut')->name('frontdesk.process-checkout');
    Route::get('frontdesk/walkin', 'walkInBooking')->name('frontdesk.walkin');
    Route::get('frontdesk/room-assignment', 'roomAssignment')->name('frontdesk.room-assignment');
    Route::get('frontdesk/folio/{booking}', 'guestFolio')->name('frontdesk.folio');
    Route::get('frontdesk/night-audit', 'nightAudit')->name('frontdesk.night-audit');
});

//housekeeping operations
Route::controller(HousekeepingController::class)->middleware('auth')->group(function () {
    Route::get('housekeeping/room-status', 'roomStatus')->name('housekeeping.room-status');
    Route::post('housekeeping/room-status/{room}', 'updateRoomStatus')->name('housekeeping.update-status');
    Route::get('housekeeping/cleaning-schedule', 'cleaningSchedule')->name('housekeeping.cleaning-schedule');
    Route::post('housekeeping/mark-cleaned/{room}', 'markAsCleaned')->name('housekeeping.mark-cleaned');
    Route::get('housekeeping/maintenance-requests', 'maintenanceRequests')->name('housekeeping.maintenance-requests');
    Route::post('housekeeping/maintenance-requests', 'createMaintenanceRequest')->name('housekeeping.create-maintenance');
    Route::get('housekeeping/reports', 'housekeepingReports')->name('housekeeping.reports');
    Route::get('housekeeping/lost-found', 'lostAndFound')->name('housekeeping.lost-found');
    Route::post('housekeeping/lost-found', 'addLostFoundItem')->name('housekeeping.add-lost-found');
});
