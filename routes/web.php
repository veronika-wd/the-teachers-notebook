<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/login', [LoginController::class, 'loginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::get('/qualifications/all', [QualificationController::class, 'all'])->name('qualifications.all');
    Route::get('/achievements/all', [AchievementController::class, 'all'])->name('achievements.all');

    Route::get('/competitions/create', [CompetitionController::class, 'create'])->name('competitions.create');
    Route::post('/competitions', [CompetitionController::class, 'store'])->name('competitions.store');// Новые маршруты для файлов конкурсов
    Route::post('/competitions/{competition}/upload-file', [CompetitionController::class, 'uploadFile'])->name('competitions.uploadFile');
    Route::get('/competitions/files/{file}/download', [CompetitionController::class, 'downloadFile'])->name('competitions.downloadFile');
    Route::delete('/competitions/files/{file}', [CompetitionController::class, 'deleteFile'])->name('competitions.deleteFile');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/schedule/edit', [ScheduleController::class, 'edit'])->name('schedule.edit');
    Route::post('/schedule/edit', [ScheduleController::class, 'store'])->name('schedule.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/calendar', function () {
        return view('calendar');
    })->name('calendar');
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/database', [StudentController::class, 'database'])->name('database.index');

    Route::prefix('notifications')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/show/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
        Route::post('/notifications/comment/{notification}', [NotificationController::class, 'comment'])->name('notifications.comment');
    });
    Route::prefix('cabinet')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('cabinet.index');
        Route::patch('/', [TeacherController::class, 'update'])->name('cabinet.update');
        Route::get('/achievement', [TeacherController::class, 'achievements'])->name('cabinet.achievements.upload');
        Route::post('/achievement', [TeacherController::class, 'storeAchievement'])->name('cabinet.achievements.store');
    });
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('students.index');
        Route::get('/{student}', [StudentController::class, 'show'])->name('students.show');
        Route::post('/{student}', [StudentController::class, 'storeAchievement'])->name('students.achievements.store');
        Route::patch('/{student}', [StudentController::class, 'update'])->name('students.update');
    });
    Route::prefix('guardians')->group(function () {
        Route::get('/{guardian}', [GuardianController::class, 'show'])->name('guardians.show');
        Route::patch('/{guardian}', [GuardianController::class, 'update'])->name('guardians.update');
        Route::post('/{guardian}', [GuardianController::class, 'store'])->name('guardians.students.store');
    });

    Route::prefix('attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/{class}', [AttendanceController::class, 'show'])->name('attendance.show');
        Route::post('/{class}', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::post('/{class}/payment', [AttendanceController::class, 'storePayment'])->name('attendance.payment.store');
        Route::delete('/{class}/delete', [AttendanceController::class, 'deletePayment'])->name('attendance.payment.delete');
    });
    Route::prefix('qualifications')->group(function () {
        Route::get('/', [QualificationController::class, 'index'])->name('qualifications.index');
        Route::post('/create', [QualificationController::class, 'store'])->name('qualifications.store');
    });

    Route::prefix('competitions')->group(function () {
        Route::get('/', [CompetitionController::class, 'index'])->name('competitions.index');
        Route::get('/{competition}', [CompetitionController::class, 'show'])->name('competitions.show');
        Route::post('/{competition}/add-student', [CompetitionController::class, 'addStudent'])->name('competitions.addStudent');
        Route::post('/{competition}/remove-student', [CompetitionController::class, 'removeStudent'])->name('competitions.removeStudent');
    });

    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    });

});





Route::prefix('api/events')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('events.index');
    Route::post('/', [EventController::class, 'store'])->name('events.store');
    Route::put('/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/counts', [EventController::class, 'getCountsByDate'])->name('events.counts');
    Route::get('/birthdays', [EventController::class, 'getBirthdays']);

});


Route::prefix('api/students')->group(function () {
    Route::get('/', [StudentController::class, 'getStudents'])->name('students.index');
});

Route::prefix('api/teachers')->group(function () {
    Route::get('/', [TeacherController::class, 'getTeachers'])->name('teachers.index');
});

Route::prefix('api/guardians')->group(function () {
    Route::get('/', [GuardianController::class, 'getGuardians'])->name('guardians.index');
});

