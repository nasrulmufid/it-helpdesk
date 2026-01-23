<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketAttachmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DataMigrationController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('home');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tickets
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
        Route::post('/{ticket}/responses', [TicketController::class, 'addResponse'])->name('addResponse');
        Route::post('/{ticket}/assign', [TicketController::class, 'assign'])->name('assign');
        Route::post('/{ticket}/assign-self', [TicketController::class, 'assignToSelf'])->name('assignToSelf');
        Route::get('/{ticket}/attachments/{attachment}/view', [TicketAttachmentController::class, 'view'])->name('attachments.view');
        Route::get('/{ticket}/attachments/{attachment}/download', [TicketAttachmentController::class, 'download'])->name('attachments.download');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/read-all', [NotificationController::class, 'markAllRead'])->name('readAll');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
    });

    // Reports (Admin & General Manager only)
    Route::middleware('report.access')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/ticket-status', [ReportController::class, 'ticketStatus'])->name('ticket-status');
        Route::get('/category-performance', [ReportController::class, 'categoryPerformance'])->name('category-performance');
        Route::get('/technician-performance', [ReportController::class, 'technicianPerformance'])->name('technician-performance');
        Route::get('/user-activity', [ReportController::class, 'userActivity'])->name('user-activity');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
    });

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // Data Migration (Import/Export)
        Route::get('/migration', [DataMigrationController::class, 'index'])->name('migration.index');
        Route::get('/migration/template/{type}', [DataMigrationController::class, 'downloadTemplate'])->name('migration.template');
        Route::get('/migration/export/{type}', [DataMigrationController::class, 'export'])->name('migration.export');
        Route::post('/migration/import/{type}', [DataMigrationController::class, 'import'])->name('migration.import');

        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Category management
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
    });
});
