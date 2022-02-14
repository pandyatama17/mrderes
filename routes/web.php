<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConsoleController;
use App\Http\Controllers\CrumbsController;
use App\Http\Controllers\DesksController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

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



Auth::routes();
Route::middleware(['auth'])->group(function () {
    // Route::get('/', [ReservationController::class,'index'])->name('index');
    Route::get('/', [ReservationController::class,'index'])->name('index');
    Route::get('/booking', [ReservationController::class,'booking'])->name('booking');
    Route::get('/calendar', [ReservationController::class,'list'])->name('calendar');
    Route::get('/calendar/mr', [ReservationController::class,'listMR'])->name('calendar_mr');
    Route::get('/ticket', [ReservationController::class,'ticket'])->name('ticket');
    Route::get('/requests', [ReservationController::class,'showRerservations'])->name('requests');
    Route::get('/account/settings', [UserController::class,'accountSettings'])->name('account_settings');

    Route::get('/approver/requests/approve/{id}', [ReservationController::class,'approveTicket'])->name('approve_ticket');
    Route::get('/approver/requests/reject/{id}', [ReservationController::class,'rejectTicket'])->name('reject_ticket');
    
    Route::get('/approver/team/', [UserController::class,'showTeam'])->name('show_team');
    Route::get('/approver/team/ticket/requests', [ReservationController::class,'showTicketRequests'])->name('ticket_requests');

    // admin routes
    Route::get('/admin/users/', [AdminController::class,'showUsers'])->name('show_users');
    Route::get('/admin/desks/', [AdminController::class,'showDesks'])->name('show_desks');
    Route::get('/admin/reservation/create', [AdminController::class,'createReservation'])->name('create_reservation_admin');
});

Route::post('/booking/ticket/submit', [ReservationController::class,'storeTicket'])->name('submit_ticket');
Route::post('/booking/reservation/submit', [ReservationController::class,'store'])->name('submit_reservation');

Route::post('/admin/user/submit', [AdminController::class,'storeuser'])->name('submit_user');
Route::post('/admin/reservation/submit', [AdminController::class,'storeReservation'])->name('admin_submit_reservation');
Route::post('/account/settings/changePassword', [UserController::class,'changePassword'])->name('account_change_password');
Route::post('/account/settings/update', [UserController::class,'update'])->name('account_update');
Route::get('/admin/user/changeStatus/{id}', [AdminController::class,'toggleUserStatus'])->name('admin_change_user_status');
Route::get('/admin/user/resetPassword/{id}', [AdminController::class,'resetPassword'])->name('admin_reset_user_password');

Route::get('/ajax/getDesksByTicket/{ticket_id}',[DesksController::class,'getDesksByTicket'])->name('getDesksByTicket');
Route::post('/ajax/getDesksForAdmin',[AdminController::class,'getDesks'])->name('getDesksForAdmin');
Route::get('/ajax/getCalendarByDate/{date}',[ReservationController::class,'getCalendarByDate'])->name('getCalendarByDate');
Route::get('/ajax/getMRCalendarByDate/{date}',[ReservationController::class,'getMRCalendarByDate'])->name('getMRCalendarByDate');
Route::get('/ajax/checkIn/{reservation_id}',[ReservationController::class,'checkIn'])->name('check_in');
Route::get('/ajax/cancelReservation/{reservation_id}',[ReservationController::class,'deleteReservation'])->name('delete_reservation');
Route::get('/ajax/getReservations/{type}',[ReservationController::class,'reservationsAjax'])->name('getRerservationsWithType');
Route::get('/ajax/getUsers/{method}',[AdminController::class,'getUsers'])->name('getUsers');
Route::get('/ajax/getTickets/{method}',[ReservationController::class,'getTickets'])->name('getTickets');
Route::get('/ajax/routesession/set/{url}', function ($url) {
    Session::flash('page',$url);
    echo $url;
})->name('set_route_session');
Route::get('/ajax/getCrumbs/{page}',[CrumbsController::class,'getCrumbs'])->name('getCrumbs');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/debug/getDesksByTicket/{ticket_id}',[DesksController::class,'getDesksByTicket']);

Route::get('/console/migrate',[ConsoleController::class, 'migrate']);
Route::get('/console/clear-views', [ConsoleController::class, 'clearViews']);