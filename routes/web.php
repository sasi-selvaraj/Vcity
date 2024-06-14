<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\MarketerController;
use App\Http\Controllers\MarketerPayoutController;
use App\Http\Controllers\MonthlyReport;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\VisitorController;

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
	// Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	// Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile-update', [UserProfileController::class, 'update'])->name('profile.update');
	// Projects
	Route::resource('projects',ProjectController::class);
	Route::post('projects/import', [ProjectController::class, 'import'])->name('projects.import');
	// Plots
	Route::resource('plots',PlotController::class);
	Route::post('plots/import', [PlotController::class, 'import'])->name('plots.import');
	Route::post('plots/list', [HomeController::class, 'plots'])->name('plots.list');
	Route::post('plots/status', [HomeController::class, 'plotsStatus'])->name('plots.status');
	Route::get('marketers/hierarchy', [MarketerController::class, 'hierarchy'])->name('marketers.hierarchy');
	Route::get('marketers/fetch/hierarchy', [MarketerController::class, 'fetchHierarchy'])->name('marketers.fetch.hierarchy');
	Route::resource('marketers',MarketerController::class);
	Route::post('marketers/update/status', [MarketerController::class, 'changeStatus'])->name('marketers.update.status');
	// Route::resource('marketer-payout',MarketerPayoutController::class);
	// Route::post('marketer-payout/fetch-projects', [MarketerPayoutController::class, 'fetchProjects'])->name('marketer-payout.fetch-projects');
	// Route::post('marketer-payout/fetch-plots', [MarketerPayoutController::class, 'fetchPlots'])->name('marketer-payout.fetch-plots');
	// Route::get('payouts/download_receipt/{id}',[MarketerPayoutController::class,'download_receipt']);


	Route::get('marketer-progress/index', [MarketerController::class, 'marketerProgress'])->name('marketer-progress.index');
	Route::get('marketer-progress/datatable-ajax', [MarketerController::class, 'datatable_ajax'])->name('marketer-progress.datatable_ajax');
	Route::get('marketer-progress/show/{id}', [MarketerController::class, 'marketerProgressShow'])->name('marketer-progress.show');
	Route::get('monthly-report/index', [MonthlyReportController::class, 'monthlyReport'])->name('monthly-report.index');
	Route::get('monthly-report/datatable-ajax', [MonthlyReportController::class, 'datatable_monthly'])->name('monthly-report.datatable_ajax');
	Route::get('monthly-report/show/{id}', [MonthlyReportController::class, 'monthlyReportShow'])->name('monthly-report.show');

	Route::post('marketers/import', [MarketerController::class, 'import'])->name('marketers.import');
	Route::get('/fetch-director-details', [MarketerController::class, 'fetchDirectorDetails'])->name('fetch.director.details');
	Route::post('marketers/remove-attachment',[MarketerController::class,'removeAttachment'])->name('marketers.remove.attachment');
	Route::resource('visitors',VisitorController::class);
	Route::resource('payments',PaymentController::class);
	Route::get('payments/getdata/{plot_id}',[PaymentController::class,'getPaymentsData'])->name('payments.getData');
	Route::get('payments/plots/{project_id}',[PaymentController::class,'plots'])->name('plot.list');
	Route::get('payments/selectPlots/{project_id}',[PaymentController::class,'selectPlots'])->name('selectPlot.list');
	Route::get('payments/marketer/{marketer_id}',[PaymentController::class,'marketer'])->name('marketer.list');
	Route::get('payments/download_receipt/{id}/{status}',[PaymentController::class,'download_receipt']);
	Route::resource('directors',DirectorController::class);
	Route::get('directors/profile', [DirectorController::class, 'profile'])->name('directors.profile');
	Route::post('directors/update/status', [DirectorController::class, 'changeStatus'])->name('directors.update.status');
	Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
