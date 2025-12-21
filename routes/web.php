<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ConfirmReportsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisasterController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ReportsController;
use App\Models\User;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DisasterImpactsController;
use App\Http\Controllers\DisasterReportDocumentationsController;
use App\Http\Controllers\DisasterVictimsController;
use App\Http\Controllers\InfografisController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RecapController;

Route::controller(LandingPageController::class)->group(function () {
    Route::get('/', 'home')->name('page.home');
    Route::get('/login', 'login')->name('page.login');
    Route::get('/register', 'register')->name('page.register');
    Route::get('/edukasi-bencana', 'konsultasi')->name('page.konsultasi');
    Route::get('/konsultasi/fetch', 'fetchConsultations')->name('page.fetch.consultations');
    Route::post('/process-gemini-ai', 'processGeminiKonsultasi')->name('page.gemini.konsultasi');
    Route::post('/get-location-data', 'getLocationData')->name('page.location.map');
    Route::get('/infografis-jember', 'infografisJember')->name('page.infografisjember');
    Route::get('/map-load', 'loadMap')->name('page.mapload');
    Route::get('/get-disaster-marker', 'getDisasterMarker')->name('page.markerdisaster');
    Route::get('/report', 'getNewsDisaster')->name('page.reportnews');
    Route::get('/berita/{slug}', 'berita')->name('berita.detail');

    Route::get('/gempa-status', 'gempaStatus')->name('page.gempastatus');
    Route::get('/mountain-status', 'mountainStatus')->name('page.mountainstatus');
});



Route::get('/forgot-password-request', [AccountController::class, 'forgotPassword'])->name('forgot.password');
Route::get('/forgot-password/{token}', [AccountController::class, 'index']);
Route::post('/forgot-password/{token}', [AccountController::class, 'resetPassword'])->name('reset.password');
Route::post('/send-reset-link', [AccountController::class, 'sendResetLink'])->name('send.reset.link');


Route::get('/ban-temporary', function () {
    $data = session('ban');

    if (!$data) {
        return redirect()->route('login');
    }
    return view('auth.ban-temporary', compact('data'));
})->name('ban-temporary');
Route::get('/ban-permanent', function () {
    return view('auth.ban-permanent');
})->name('ban-permanent');
// is logged
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/lapor', [LandingPageController::class, 'lapor'])->name('page.lapor');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // sound notif
    // Route::get('/api/reports/pending', function () {
    //     $count = \App\Models\Reports::where('status', 'pending')->count();
    //     return response()->json(['total' => $count]);
    // })->name('api.reports.pending');
    Route::get('/api/pending-reports-count', function () {
        return response()->json([
            'count' => \App\Models\Reports::where('status', 'pending')->count()
        ]);
    });


    //dashboard
    Route::get('/home', [DashboardController::class, 'homeuser'])->name('user.home');
    Route::get('/home', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-infografis', [DashboardController::class, 'indexDashboard'])->name('dashboard.infografis');
    Route::get('/infographic/data', [DashboardController::class, 'getData'])->name('dashboard.infografis.data');
    // Route::get('/eartquake/data', [DashboardController::class, 'eartquake'])->name('dashboard.infografis.eartquake');
    Route::get('/mapload', [DashboardController::class, 'mapLoad'])->name('dashboard.mapload');
    // Route::get('/dashboarduser', [DashboardController::class, 'userdashboard'])->name('user.dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getReportData'])->name('dashboard.chart-data');

    // User Management
    Route::group(['prefix' => 'user-management'], function () {
        Route::get('/', [UserManagementController::class, 'index'])->middleware('permission:read_user_management')->name('user-management.index');
        Route::get('/create', [UserManagementController::class, 'create'])->middleware('permission:create_user_management')->name('user-management.create');
        Route::post('/store', [UserManagementController::class, 'store'])->middleware('permission:create_user_management')->name('user-management.store');
        Route::get('/edit/{id}', [UserManagementController::class, 'edit'])->middleware('permission:update_user_management')->name('user-management.edit');
        Route::put('/update/{id}', [UserManagementController::class, 'update'])->middleware('permission:update_user_management')->name('user-management.update');
        Route::delete('/delete/{id}', [UserManagementController::class, 'delete'])->middleware('permission:delete_user_management')->name('user-management.delete');
        Route::put('/update-permission/{id}', [UserManagementController::class, 'updatepermission'])->name('user-management.update-permission');
        Route::get('/reset-password/{id}', [UserManagementController::class, 'resetPassword'])->middleware('permission:resetPassword_user_management')->name('user-management.reset-password');
        Route::post('/ban/{id}', [UserManagementController::class, 'banUser'])->middleware('permission:ban_user_management')->name('user-management.banUser');
        Route::post('/unBan/{id}', [UserManagementController::class, 'unBanUser'])->middleware('permission:unBan_user_management')->name('user-management.unBanUser');
    });

    // Permission
    Route::group(['prefix' => 'permission'], function () {
        Route::get('/', [PermissionController::class, 'index'])->middleware('permission:read_permission')->name('permission.index');
        Route::get('/create', [PermissionController::class, 'create'])->middleware('permission:create_permission')->name('permission.create');
        Route::post('/store', [PermissionController::class, 'store'])->middleware('permission:create_permission')->name('permission.store');
        Route::get('/edit/{id}', [PermissionController::class, 'edit'])->middleware('permission:update_permission')->name('permission.edit');
        Route::put('/update/{id}', [PermissionController::class, 'update'])->middleware('permission:update_permission')->name('permission.update');
        Route::delete('/delete/{id}', [PermissionController::class, 'delete'])->middleware('permission:delete_permission')->name('permission.delete');
    });

    // Role
    Route::group(['prefix' => 'role'], function () {
        Route::get('/', [RoleController::class, 'index'])->middleware('permission:read_role')->name('role.index');
        Route::get('/create', [RoleController::class, 'create'])->middleware('permission:create_role')->name('role.create');
        Route::post('/store', [RoleController::class, 'store'])->middleware('permission:create_role')->name('role.store');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->middleware('permission:update_role')->name('role.edit');
        Route::put('/update/{id}', [RoleController::class, 'update'])->middleware('permission:update_role')->name('role.update');
        Route::delete('/delete/{id}', [RoleController::class, 'delete'])->middleware('permission:delete_role')->name('role.delete');
    });

    //change password
    Route::get('/change-password', [ChangePasswordController::class, 'changePassword'])->name('user.change-password');
    Route::put('/change-password', [ChangePasswordController::class, 'updatePassword'])->name('user.update-password');

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/', [ReportsController::class, 'index'])->middleware('permission:read_report')->name('reports.index');
        Route::get('/history', [ReportsController::class, 'index'])->middleware('permission:read_report')->name('reports.history');
        Route::get('/create', [ReportsController::class, 'create'])->middleware('permission:create_report')->name('reports.create');
        Route::post('/store', [ReportsController::class, 'store'])->middleware('permission:create_report')->name('reports.store');
        Route::post('/location', [ReportsController::class, 'location'])->middleware('permission:create_report')->name('reports.location');
        Route::get('/edit/{id}', [ReportsController::class, 'edit'])->middleware('permission:update_report')->name('reports.edit');
        Route::put('/update/{id}', [ReportsController::class, 'update'])->middleware('permission:update_report')->name('reports.update');
        Route::delete('/delete/{id}', [ReportsController::class, 'delete'])->middleware('permission:delete_report')->name('reports.delete');
        Route::get('/detail/{id}', [ReportsController::class, 'detail'])->middleware('permission:read_report')->name('reports.detail');
        Route::put('/reports/{id}/accept', [ReportsController::class, 'accept'])->name('reports.accept');
        Route::put('/reports/{id}/reject', [ReportsController::class, 'reject'])->name('reports.reject');
        Route::put('/reports/{id}/process', [ReportsController::class, 'process'])->middleware('permission:process_report')->name('reports.process');
    });

    //confirm reports
    Route::group(['prefix' => 'confirm-reports'], function () {
        Route::get('/', [ConfirmReportsController::class, 'index'])->middleware('permission:read_report')->name('confirm-reports.index');
        Route::get('/create', [ConfirmReportsController::class, 'create'])->middleware('permission:create_report')->name('confirm-reports.create');
        Route::post('/store', [ConfirmReportsController::class, 'store'])->middleware('permission:create_report')->name('confirm-reports.store');
        Route::get('/edit/{id}', [ConfirmReportsController::class, 'edit'])->middleware('permission:update_report')->name('confirm-reports.edit');
        Route::put('/update/{id}', [ConfirmReportsController::class, 'update'])->middleware('permission:update_report')->name('confirm-reports.update');
        Route::delete('/delete/{id}', [ConfirmReportsController::class, 'delete'])->middleware('permission:delete_report')->name('confirm-reports.delete');
        Route::get('/detail/{id}', [ConfirmReportsController::class, 'detail'])->middleware('permission:read_report')->name('confirm-reports.detail');
        Route::put('/{id}/accept', [ConfirmReportsController::class, 'accept'])->middleware('permission:accept_report')->name('confirm-reports.accept');
        Route::put('/{id}/reject', [ConfirmReportsController::class, 'reject'])->middleware('permission:reject_report')->name('confirm-reports.reject');
        Route::put('/{id}/netral', [ConfirmReportsController::class, 'netral'])->middleware('permission:netral_report')->name('confirm-reports.netral');
        Route::post('/export-pdf', [ConfirmReportsController::class, 'export_pdf'])->name('confirm-reports.export-pdf');
        Route::post('/export-excel', [ConfirmReportsController::class, 'export_excel'])->name('confirm-reports.export-excel');
    });

    //news
    Route::group(['prefix' => 'news'], function () {
        Route::get('/', [NewsController::class, 'index'])->middleware('permission:read_news')->name('news.index');
        Route::get('/create', [NewsController::class, 'create'])->middleware('permission:create_news')->name('news.create');
        Route::post('/store', [NewsController::class, 'store'])->middleware('permission:create_news')->name('news.store');
        Route::get('/edit/{id}', [NewsController::class, 'edit'])->middleware('permission:update_news')->name('news.edit');
        Route::put('/update/{id}', [NewsController::class, 'update'])->middleware('permission:update_news')->name('news.update');
        Route::delete('/delete/{id}', [NewsController::class, 'delete'])->middleware('permission:delete_news')->name('news.delete');
        Route::get('/detail/{id}', [NewsController::class, 'detail'])->middleware('permission:read_news')->name('news.detail');
        Route::put('/{id}/publish', [NewsController::class, 'publish'])->middleware('permission:publish_news')->name('news.publish');
        Route::put('/{id}/takedown', [NewsController::class, 'takedown'])->middleware('permission:takedown_news')->name('news.takedown');
    });

    //disaster impacts
    Route::group(['prefix' => 'disaster-impacts'], function () {
        Route::get('/', [DisasterImpactsController::class, 'index'])->middleware('permission:read_disaster_impacts')->name('disaster_impacts.index');
        Route::get('/create/{id}', [DisasterImpactsController::class, 'create'])->middleware('permission:create_disaster_impacts')->name('disaster_impacts.create');
        Route::post('/store', [DisasterImpactsController::class, 'store'])->middleware('permission:create_disaster_impacts')->name('disaster_impacts.store');
        Route::get('/edit/{id}', [DisasterImpactsController::class, 'edit'])->middleware('permission:update_disaster_impacts')->name('disaster_impacts.edit');
        Route::put('/update/{id}', [DisasterImpactsController::class, 'update'])->middleware('permission:update_disaster_impacts')->name('disaster_impacts.update');
        Route::delete('/delete/{id}', [DisasterImpactsController::class, 'delete'])->middleware('permission:delete_disaster_impacts')->name('disaster_impacts.delete');
    });

    //disaster victims
    Route::group(['prefix' => 'disaster-victims'], function () {
        Route::get('/', [DisasterVictimsController::class, 'index'])->middleware('permission:read_disaster_victims')->name('disaster_victims.index');
        Route::get('/create/{id}', [DisasterVictimsController::class, 'create'])->middleware('permission:create_disaster_victims')->name('disaster_victims.create');
        Route::post('/store', [DisasterVictimsController::class, 'store'])->middleware('permission:create_disaster_victims')->name('disaster_victims.store');
        Route::get('/edit/{id}', [DisasterVictimsController::class, 'edit'])->middleware('permission:update_disaster_victims')->name('disaster_victims.edit');
        Route::put('/update/{id}', [DisasterVictimsController::class, 'update'])->middleware('permission:update_disaster_victims')->name('disaster_victims.update');
        Route::delete('/delete/{id}', [DisasterVictimsController::class, 'delete'])->middleware('permission:delete_disaster_victims')->name('disaster_victims.delete');
    });

    Route::prefix('infografis')->controller(InfografisController::class)->group(function () {
        Route::get('/', 'index')->middleware('permission:read_infografis')->name('infografis.index');
        Route::get('/create', 'create')->middleware('permission:create_infografis')->name('infografis.create');
        Route::post('/store', 'store')->middleware('permission:create_infografis')->name('infografis.store');
        Route::get('/edit/{id}', 'edit')->middleware('permission:update_infografis')->name('infografis.edit');
        Route::put('/update/{id}', 'update')->middleware('permission:update_infografis')->name('infografis.update');
        Route::delete('/delete/{id}', 'delete')->middleware('permission:delete_infografis')->name('infografis.delete');
    });



    //profile
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index'])->middleware('permission:read_profile')->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->middleware('permission:update_profile')->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->middleware('permission:update_profile')->name('profile.update');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->middleware('permission:update_profile')->name('profile.update-password');
    });
});
Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
Route::get('/monitoring/image/{filename}', [MonitoringController::class, 'getImage'])->name('monitoring.image');

//Rekapitulasi Laporan
Route::group(['prefix' => 'rekapitulasi-laporan'], function () {
    Route::get('/', [RecapController::class, 'index'])->middleware('permission:read_recap')->name('recap.index');
    Route::post('/export-pdf', [RecapController::class, 'exportPDFRecap'])->middleware('permission:exportPDF_recap')->name('recap.export-pdf');
    Route::post('/export-excel', [RecapController::class, 'exportExcelRecap'])->middleware('permission:exportExcel_recap')->name('recap.export-excel');
    Route::get('/detail/{id}', [RecapController::class, 'detailRecaps'])->name('recap.detail');
    Route::get('/victim-table/{id}', [RecapController::class, 'dataTableKorban'])->name('recap.victims.dataTable');
});

//DOkumentasi Laporan Bencana
Route::group(['prefix' => 'disaster-report-documentations'], function () {
    Route::get('/', [DisasterReportDocumentationsController::class, 'index'])->middleware('permission:read_disaster_report_documentations')->name('disaster_report_documentations.index');
    Route::get('/create', [DisasterReportDocumentationsController::class, 'create'])->middleware('permission:create_disaster_report_documentations')->name('disaster_report_documentations.create');
    Route::post('/store', [DisasterReportDocumentationsController::class, 'store'])->middleware('permission:create_disaster_report_documentations')->name('disaster_report_documentations.store');
    Route::get('/edit/{id}', [DisasterReportDocumentationsController::class, 'edit'])->middleware('permission:update_disaster_report_documentations')->name('disaster_report_documentations.edit');
    Route::put('/update/{id}', [DisasterReportDocumentationsController::class, 'update'])->middleware('permission:update_disaster_report_documentations')->name('disaster_report_documentations.update');
    Route::delete('/delete/{id}', [DisasterReportDocumentationsController::class, 'delete'])->middleware('permission:delete_disaster_report_documentations')->name('disaster_report_documentations.delete');
    Route::get('/detail/{id}', [DisasterReportDocumentationsController::class, 'detail'])->middleware('permission:read_disaster_report_documentations')->name('disaster_report_documentations.detail');
    Route::post('/export-pdf', [DisasterReportDocumentationsController::class, 'exportPDF'])->middleware('permission:exportPDF_disaster_report_documentations')->name('disaster_report_documentations.export-pdf');
    Route::post('/export-excel', [DisasterReportDocumentationsController::class, 'exportExcel'])->middleware('permission:exportExcel_disaster_report_documentations')->name('disaster_report_documentations.export-excel');
});
//disaster
Route::group(['prefix' => 'categorydisaster'], function () {
    Route::get('/', [DisasterController::class, 'index'])->middleware('permission:read_disaster_category')->name('disaster.index');
    Route::get('/create', [DisasterController::class, 'create'])->middleware('permission:create_disaster_category')->name('disaster.create');
    Route::post('/store', [DisasterController::class, 'store'])->middleware('permission:create_disaster_category')->name('disaster.store');
    Route::get('/edit/{id}', [DisasterController::class, 'edit'])->middleware('permission:update_disaster_category')->name('disaster.edit');
    Route::put('/update/{id}', [DisasterController::class, 'update'])->middleware('permission:update_disaster_category')->name('disaster.update');
    Route::delete('/destroy/{id}', [DisasterController::class, 'destroy'])->middleware('permission:delete_disaster_category')->name('disaster.destroy');
});

Route::group(['prefix' => 'consultation'], function () {
    Route::get('/', [ConsultationController::class, 'index'])->middleware('permission:read_consultation')->name('consultation.index');
    Route::get('/create', [ConsultationController::class, 'create'])->middleware('permission:create_consultation')->name('consultation.create');
    Route::post('/store', [ConsultationController::class, 'store'])->middleware('permission:create_consultation')->name('consultation.store');
    Route::get('/edit/{id}', [ConsultationController::class, 'edit'])->middleware('permission:update_consultation')->name('consultation.edit');
    Route::put('/update/{id}', [ConsultationController::class, 'update'])->middleware('permission:update_consultation')->name('consultation.update');
    Route::delete('/delete/{id}', [ConsultationController::class, 'delete'])->middleware('permission:delete_consultation')->name('consultation.delete');
});
//
// Route::group(['prefix' => 'disaster'], function () {
//     Route::get('/', [DisasterController::class, 'index'])->('permission:')->name('disaster.index');
//     Route::get('/create', [DisasterController::class, 'create'])->name('disaster.create');
//     Route::get('/edit/{id}', [DisasterController::class, 'edit'])->name('disaster.edit');
//     Route::post('/store', [DisasterController::class, 'store'])->name('disaster.store');
//     Route::put('/update/{id}', [DisasterController::class, 'update'])->name('disaster.update');
//     Route::delete('/destroy/{id}', [DisasterController::class, 'destroy'])->name('disaster.destroy');
// });

Route::get('auth/google/redirect', [GoogleController::class, 'redirect'])->name('socialite.redirect');

Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('socialite.callback');
Route::get('auth/login-otp', [GoogleController::class, 'verifyForm'])->name('login-otp.index');
Route::post('auth/login-otp', [GoogleController::class, 'verify'])->name('login-otp.verify');
Route::get('/login/otp/resend', [GoogleController::class, 'resendOtp'])->name('login-otp.resend');

Route::post('/comments', [CommentsController::class, 'store'])->name('comments.store');
Route::get('/comments/{newsId}', [CommentsController::class, 'getComments'])->name('comments.get');

require __DIR__ . '/auth.php';
