<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\School\DashboardController;
use App\Http\Controllers\School\FileBrowserController;
use App\Http\Controllers\School\GradeController;
use App\Http\Controllers\School\SubjectController;
use App\Http\Controllers\School\ActivityLogController as SchoolActivityLogController;
use App\Http\Controllers\School\SupervisorController;
use App\Http\Controllers\School\UserController as SchoolUserController;
use App\Http\Controllers\School\PlansController;
use App\Http\Controllers\SuperAdmin\Auth\AuthenticatedSessionController as SuperAdminLoginController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\ImpersonationController;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\SchoolActivityController;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\ActivityLogController;
use App\Http\Controllers\SuperAdmin\SchoolUserController as SuperAdminSchoolUserController;
use App\Http\Controllers\SuperAdmin\SettingsController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\Supervisor\ReviewController;
use App\Http\Controllers\Supervisor\SupervisorDashboardController;
use App\Http\Controllers\Supervisor\FileSubmissionController as SupervisorFileSubmissionController;
use App\Http\Controllers\Teacher\FileSubmissionController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MainAdmin\DashboardController as MainAdminDashboardController;
use App\Http\Controllers\MainAdmin\HierarchyController;
use App\Http\Controllers\MainAdmin\SubjectsGradesController;
use App\Http\Controllers\MainAdmin\UserController as MainAdminUserController;
use App\Http\Controllers\Tenant\ContextSwitchController;
use App\Models\Network;
use App\Models\School;
use App\Models\SchoolUserRole;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===========================
// LANGUAGE SWITCHING ROUTES
// ===========================

Route::post('/set-locale', [LanguageController::class, 'update'])->name('locale.update');

// ===========================
// SUPER ADMIN ROUTES
// ===========================
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['setlocale'])
    ->group(function() {

        // Guest routes
        Route::middleware('guest')->group(function() {
            Route::get('/login', [SuperAdminLoginController::class, 'create'])->name('login');
            Route::post('/login', [SuperAdminLoginController::class, 'store'])->name('login.store');
        });

        // Authenticated Super Admin routes
        Route::middleware(['auth', 'superadmin'])->group(function() {
            Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
            Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
                Route::get('/', [ActivityLogController::class, 'index'])->name('index');
                Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
                Route::post('/cleanup', [ActivityLogController::class, 'cleanup'])->name('cleanup');
                Route::get('/{activity}', [ActivityLogController::class, 'show'])->name('show');
                Route::get('/school/{school}', [ActivityLogController::class, 'school'])->name('school');
            });
            // School Management
            Route::get('schools', [SchoolController::class, 'index'])->name('schools.index');
            Route::get('schools/create', [SchoolController::class, 'create'])->name('schools.create');
            Route::post('schools', [SchoolController::class, 'store'])->name('schools.store');
            Route::get('schools/{school}/edit', [SchoolController::class, 'edit'])->name('schools.edit');
            Route::patch('schools/{school}', [SchoolController::class, 'update'])->name('schools.update');
            Route::delete('schools/{school}', [SchoolController::class, 'destroy'])->name('schools.destroy');


            Route::get('/schools/{school}/users', [SuperAdminSchoolUserController::class, 'index'])->name('schools.users.index');
            Route::get('/schools/{school}/activity', [SchoolActivityController::class, 'index'])->name('schools.activity');

            // User Management
            Route::controller(SuperAdminSchoolUserController::class)->group(function() {
                Route::get('/users/{user}/edit', 'edit')->name('users.edit')->scopeBindings();
                Route::patch('/users/{user}', 'update')->name('users.update')->scopeBindings();
                Route::delete('/users/{user}', 'destroy')->name('users.destroy')->scopeBindings();
            });
            Route::post('/users/{user}/impersonate', [ImpersonationController::class, 'start'])->name('users.impersonate');

            // Subscription Management
            Route::resource('subscriptions', SubscriptionController::class)->only(['index', 'edit', 'update']);
            Route::controller(SubscriptionController::class)->prefix('subscriptions')->group(function() {
                Route::patch('/{subscription}/pause', 'pause')->name('subscriptions.pause');
                Route::patch('/{subscription}/resume', 'resume')->name('subscriptions.resume');
                Route::patch('/{subscription}/activate', 'activate')->name('subscriptions.activate'); // Add this

            });

            // Plans and Settings
            Route::resource('plans', PlanController::class);
            Route::controller(SettingsController::class)->group(function() {
                Route::get('/settings', 'index')->name('settings.index');
                Route::patch('/settings', 'update')->name('settings.update');
            });

            // Logout
            Route::post('/logout', function (Request $request) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/');
            })->name('logout');
        });
    });

// Impersonation Leave Route
Route::post('/impersonate/leave', [ImpersonationController::class, 'stop'])
    ->middleware(['auth'])
    ->name('impersonate.leave');

// ===========================
// MAIN ADMIN ROUTES
// ===========================
Route::prefix('{network:slug}/main-admin')
    ->as('main-admin.')
    ->middleware(['setlocale', 'setNetwork'])
    ->scopeBindings()
    ->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
            Route::post('login', [LoginController::class, 'login']);
        });

        Route::middleware(['auth', 'mainadmin'])->group(function () {
            Route::get('dashboard', [MainAdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('hierarchy', [HierarchyController::class, 'index'])->name('hierarchy');
            Route::get('subjects-grades', [SubjectsGradesController::class, 'index'])->name('subjects-grades');
            Route::post('subjects-grades', [SubjectsGradesController::class, 'store'])->name('subjects-grades.store');
            Route::put('subjects-grades/{type}/{id}', [SubjectsGradesController::class, 'update'])->name('subjects-grades.update');
            Route::delete('subjects-grades/{type}/{id}', [SubjectsGradesController::class, 'destroy'])->name('subjects-grades.destroy');

            Route::resource('users', MainAdminUserController::class)->except(['show']);
            Route::post('users/{user}/restore', [MainAdminUserController::class, 'restore'])->name('users.restore');
            Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        });
    });

// ===========================
// TENANT (SCHOOL) ROUTES
// ===========================
Route::prefix('{network:slug}/{branch:slug}')
    ->middleware(['setlocale', 'setNetwork', 'setBranch'])
    ->scopeBindings()
    ->group(function () {
        // ===========================
        // GUEST ROUTES
        // ===========================
        Route::middleware('guest')->group(function () {
            Route::get('/', function (Network $network, School $branch) {
                if ($branch->network_id !== $network->id) {
                    abort(404);
                }

                return view('auth.login');
            })->name('home');

            Route::controller(RegisteredUserController::class)->group(function () {
                Route::get('register', 'create')->name('register');
                Route::post('register', 'store');
            });

            Route::controller(AuthenticatedSessionController::class)->group(function () {
                Route::get('login', 'create')->name('login');
                Route::post('login', 'store');
            });
        });

        // ===========================
        // AUTHENTICATED TENANT ROUTES (ALL ROLES)
        // ===========================
        Route::middleware(['setNetwork', 'setBranch', 'ensure.school.network.match', 'verify.tenant.access', 'auth'])->group(function () {
            // Dashboard (role-based)
            Route::get('/dashboard', function (Network $network, School $branch) {
                if ($branch->network_id !== $network->id) {
                    abort(404, 'Access denied to this network.');
                }

                $user = Auth::user();

                $availableRoles = SchoolUserRole::where('user_id', $user->id)
                    ->where('school_id', $branch->id)
                    ->pluck('role')
                    ->toArray();

                if (empty($availableRoles)) {
                    return redirect()
                        ->to(safe_tenant_route('logout', $branch))
                        ->with('error', __('messages.auth.unauthorized'));
                }

                $role = null;
                $preferredRole = session('active_role');

                if ($preferredRole && in_array($preferredRole, $availableRoles)) {
                    $role = $preferredRole;
                } elseif ($user->role && in_array($user->role, $availableRoles)) {
                    $role = $user->role;
                } else {
                    $role = collect(['admin', 'supervisor', 'teacher'])
                        ->first(fn ($candidate) => in_array($candidate, $availableRoles))
                        ?? $availableRoles[0];
                }

                TenantContext::setActiveContext($branch->id, $role);
                $user->setAttribute('role', $role);
                $user->setAttribute('school_id', $branch->id);

                return match($role) {
                    'admin' => redirect()->to(tenant_route('school.admin.dashboard', $branch)),
                    'teacher' => redirect()->to(tenant_route('teacher.dashboard', $branch)),
                    'supervisor' => redirect()->to(tenant_route('supervisor.dashboard', $branch)),
                    default => redirect()->to(tenant_route('dashboard', $branch)),
                };
            })->name('dashboard');

            Route::post('/context-switch', [ContextSwitchController::class, 'switch'])
                ->name('tenant.context.switch');

            // ===========================
            // PROFILE ROUTES (ALL USERS)
            // ===========================
                    Route::controller(ProfileController::class)
                        ->prefix('profile')
                        ->name('profile.')
                        ->group(function () {
                            Route::get('/', 'edit')->name('edit');
                            Route::patch('/', 'update')->name('update');
                            Route::patch('/password', 'updatePassword')->name('password.update');
                        });

            // ===========================
            // TEACHER ROUTES
            // ===========================
            Route::middleware('role:teacher')
                ->prefix('teacher')
                ->name('teacher.')
                ->group(function () {
                    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])
                        ->name('dashboard');

                    Route::controller(FileSubmissionController::class)
                        ->prefix('files')
                        ->name('files.')
                        ->group(function () {
                            Route::get('/', 'myFiles')->name('index');
                            Route::get('/create', 'create')->name('create');
                            Route::post('/', 'store')->name('store');
                            Route::get('/{fileSubmission}', 'show')->name('show');
                            Route::get('/{fileSubmission}/preview', 'preview')->name('preview');
                            Route::get('/{fileSubmission}/download', 'download')->name('download');
                            Route::delete('/{fileSubmission}', 'destroy')->name('destroy');
                        });
                });

            // ===========================
            // SUPERVISOR ROUTES
            // ===========================
            Route::middleware('role:supervisor')
                ->prefix('supervisor')
                ->name('supervisor.')
                ->group(function () {
                    Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])
                        ->name('dashboard');

                    // Review files
                    Route::controller(ReviewController::class)
                        ->prefix('review-files')
                        ->name('reviews.')
                        ->group(function () {
                            Route::get('/', 'index')->name('index');
                            Route::get('/{fileSubmission}', 'show')->name('show');
                            Route::get('/{fileSubmission}/preview', 'preview')->name('preview');
                            Route::get('/{fileSubmission}/download', 'download')->name('download');
                        });

                    // Supervisor file management
                    Route::controller(SupervisorFileSubmissionController::class)
                        ->prefix('files')
                        ->name('files.')
                        ->group(function () {
                            Route::get('/', 'index')->name('index');
                            Route::get('/create', 'create')->name('create');
                            Route::post('/', 'store')->name('store');
                        });

                    // Supervisor specific files
                    Route::get('/{supervisor}/files', [SupervisorController::class, 'files'])->name('files');
                });

            // ===========================
            // NOTIFICATIONS (ALL AUTHENTICATED USERS)
            // ===========================
            Route::controller(NotificationController::class)
                ->prefix('notifications')
                ->name('notifications.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/{notification}/read', 'markAsRead')->name('read');
                    Route::post('/mark-all-read', 'markAllAsRead')->name('mark-all-read');
                    Route::get('/unread-count', 'unreadCount')->name('unread-count');
                });

            // Logout
            Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        });
    });

// ===========================
// BRANCH ADMIN ROUTES
// ===========================
Route::prefix('{network:slug}/{branch:slug}/admin')
    ->middleware(['setlocale', 'setNetwork', 'setBranch', 'ensure.school.network.match', 'verify.tenant.access', 'auth', 'role:admin'])
    ->name('school.admin.')
    ->scopeBindings()
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('activity-logs', [SchoolActivityLogController::class, 'index'])
            ->name('activity-logs.index');

        // User Management
        Route::resource('users', SchoolUserController::class)->except(['show']);
        Route::post('users/store-ajax', [SchoolUserController::class, 'storeAjax'])->name('users.store-ajax');
        Route::patch('users/{user}/toggle-status', [SchoolUserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('users/{user}/archive', [SchoolUserController::class, 'destroy'])->name('users.archive');
        Route::get('users/archived', [SchoolUserController::class, 'archived'])->name('users.archived');
        Route::patch('users/archived/{user}/restore', [SchoolUserController::class, 'restore'])->name('users.restore');
        Route::delete('users/archived/{user}/force-delete', [SchoolUserController::class, 'forceDelete'])->name('users.force-delete');

        // Subject Management
        Route::controller(SubjectController::class)
            ->prefix('subjects')
            ->name('subjects.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::patch('/{subject}/archive', 'archive')->name('archive');
                Route::patch('/{subject}/restore', 'restore')->name('restore');
            });

        // Grade Management
        Route::controller(GradeController::class)
            ->prefix('grades')
            ->name('grades.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::patch('/{grade}/archive', 'archive')->name('archive');
                Route::patch('/{grade}/restore', 'restore')->name('restore');
            });

        // File Browser
        Route::controller(FileBrowserController::class)
            ->prefix('file-browser')
            ->name('file-browser.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{file}', 'show')->name('show');
                Route::get('/{file}/download', 'download')->name('download');
                Route::get('/{file}/preview', 'preview')->name('preview');
                Route::get('/{file}/preview-data', 'previewData')->name('preview-data');
                Route::delete('/{file}', 'destroy')->name('destroy');
                Route::post('/bulk-download', 'bulkDownload')->name('bulk-download');
                Route::delete('/bulk-delete', 'bulkDelete')->name('bulk-delete');
            });

        // Plans Management
        Route::get('plans', [PlansController::class, 'index'])->name('plans.index');
        Route::get('plans/{plan}', [PlansController::class, 'show'])->name('plans.show');
        Route::get('plans/{plan}/download', [PlansController::class, 'download'])->name('plans.download');

        // Supervisors Management
        Route::controller(SupervisorController::class)
            ->prefix('supervisors')
            ->name('supervisors.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{supervisor}/files', 'files')->name('files');
            });
    });

// ===========================
// ROOT REDIRECT
// ===========================
Route::get('/', [LandingController::class, 'index'])
    ->middleware(['setlocale'])
    ->name('landing');

// ===========================
// PWA ROUTES & ASSETS
// ===========================

// PWA Manifest with Enhanced Desktop Support
Route::get('/manifest.json', function (Request $request) {
    $schoolSlug = $request->query('school');
    $school = School::with('network')->where('slug', $schoolSlug)->first();
    $locale = App::getLocale();

    $slugPrefix = $school && $school->network ? "/{$school->network->slug}/{$school->slug}" : '';

    $manifest = [
        'name' => ($school ? $school->name : 'Scholder') . ' - ' . __('messages.educational_platform', [], $locale),
        'short_name' => $school ? $school->name : 'Scholder',
        'description' => __('messages.platform_description', [], $locale),
        'start_url' => $school ? "{$slugPrefix}/dashboard" : '/',
        'scope' => $school ? "{$slugPrefix}/" : '/',
        'display' => 'standalone',
        'background_color' => '#ffffff',
        'theme_color' => $school->theme_color ?? '#3B82F6',
        'orientation' => 'any',
        'categories' => ['education', 'productivity'],
        'lang' => $locale,
        'dir' => $locale === 'ar' ? 'rtl' : 'ltr',
        'id' => $school ? "scholder-pwa-{$school->slug}" : 'scholder-pwa',
        'prefer_related_applications' => false,

        // Desktop-specific enhancements
        'display_override' => ['window-controls-overlay', 'standalone', 'minimal-ui'],
        'protocol_handlers' => [
            [
                'protocol' => 'web+scholder',
                'url' => "{$slugPrefix}/handle-protocol?url=%s"
            ]
        ],
        'share_target' => [
            'action' => "{$slugPrefix}/share-target",
            'method' => 'POST',
            'enctype' => 'multipart/form-data',
            'params' => [
                'title' => 'title',
                'text' => 'text',
                'url' => 'url',
                'files' => [
                    [
                        'name' => 'files',
                        'accept' => ['*/*']
                    ]
                ]
            ]
        ],

        // Icons (can be customized per school later)
        'icons' => [
            [
                'src' => '/Scholder-192.png',
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ],
            [
                'src' => '/Scholder-512.png',
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ],
            [
                'src' => '/Scholder-1024.png',
                'sizes' => '1024x1024',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
        ],

        // Shortcuts (now scoped per school)
        'shortcuts' => [
            [
                'name' => __('messages.upload_file', [], $locale),
                'short_name' => __('messages.upload', [], $locale),
                'description' => __('messages.quick_upload', [], $locale),
                'url' => "{$slugPrefix}/teacher/files/create",
                'icons' => [['src' => '/Scholder-192.png', 'sizes' => '192x192']]
            ],
            [
                'name' => __('messages.my_files', [], $locale),
                'short_name' => __('messages.files', [], $locale),
                'description' => __('messages.view_files', [], $locale),
                'url' => "{$slugPrefix}/teacher/files",
                'icons' => [['src' => '/Scholder-192.png', 'sizes' => '192x192']]
            ],
            [
                'name' => __('messages.dashboard.dashboard', [], $locale),
                'short_name' => __('messages.dashboard.dashboard', [], $locale),
                'description' => __('messages.go_to_dashboard', [], $locale),
                'url' => "{$slugPrefix}/dashboard",
                'icons' => [['src' => '/Scholder-192.png', 'sizes' => '192x192']]
            ]
        ],

        // Screenshots and platform info
        'screenshots' => [
            [
                'src' => '/screenshots/desktop-dashboard.png',
                'sizes' => '1920x1080',
                'type' => 'image/png',
                'platform' => 'wide',
                'label' => 'Dashboard view on desktop'
            ],
            [
                'src' => '/screenshots/mobile-upload.png',
                'sizes' => '1080x1920',
                'type' => 'image/png',
                'platform' => 'narrow',
                'label' => 'Upload feature on mobile'
            ]
        ],

        'edge_side_panel' => [
            'preferred_width' => 480
        ]
    ];

    return response()->json($manifest)
        ->header('Content-Type', 'application/manifest+json')
        ->header('Cache-Control', 'public, max-age=3600');
});


// Enhanced Service Worker with Desktop Features
Route::get('/sw.js', function () {
    $js = <<<'JS'
const CACHE_NAME = 'scholder-v2';
const urlsToCache = [
    '/',
    '/Scholder-192.png',
    '/Scholder-512.png',
    '/manifest.json',
    '/build/assets/app.css',
    '/build/assets/app.js'
];

// Install event - cache resources
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
            .then(() => self.skipWaiting())
    );
});

// Activate event - clean old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch event - network first, fallback to cache
self.addEventListener('fetch', event => {
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    return caches.match('/offline.html');
                })
        );
    } else {
        event.respondWith(
            fetch(event.request)
                .then(response => {
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }
                    const responseToCache = response.clone();
                    caches.open(CACHE_NAME)
                        .then(cache => {
                            cache.put(event.request, responseToCache);
                        });
                    return response;
                })
                .catch(() => {
                    return caches.match(event.request);
                })
        );
    }
});

// Push notification support
self.addEventListener('push', event => {
    const options = {
        body: event.data ? event.data.text() : 'New notification from Scholder',
        icon: '/Scholder-192.png',
        badge: '/Scholder-192.png',
        vibrate: [200, 100, 200],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        }
    };

    event.waitUntil(
        self.registration.showNotification('Scholder', options)
    );
});

// Notification click handler
self.addEventListener('notificationclick', event => {
    event.notification.close();

    event.waitUntil(
        clients.matchAll({ type: 'window' })
            .then(clientList => {
                for (const client of clientList) {
                    if (client.url === '/' && 'focus' in client) {
                        return client.focus();
                    }
                }
                if (clients.openWindow) {
                    return clients.openWindow('/');
                }
            })
    );
});

// Background sync for offline actions
self.addEventListener('sync', event => {
    if (event.tag === 'upload-files') {
        event.waitUntil(uploadPendingFiles());
    }
});

async function uploadPendingFiles() {
    // Implementation for syncing offline file uploads
    // when connection is restored
}

// Handle share target API
self.addEventListener('fetch', event => {
    if (event.request.url.includes('/share-target') && event.request.method === 'POST') {
        event.respondWith(handleSharedContent(event.request));
    }
});

async function handleSharedContent(request) {
    const formData = await request.formData();
    const files = formData.getAll('files');
    const title = formData.get('title');

    // Store shared content for processing when app opens
    const cache = await caches.open('shared-content');
    await cache.put('/shared', new Response(JSON.stringify({
        files: files.length,
        title: title,
        timestamp: Date.now()
    })));

    return Response.redirect('/', 303);
}
JS;

    return response($js)
        ->header('Content-Type', 'application/javascript')
        ->header('Service-Worker-Allowed', '/')
        ->header('Cache-Control', 'no-cache');
})->name('service-worker');

// Offline fallback page
Route::get('/offline.html', function () {
    return view('offline');
})->name('offline');

// Install Instructions Page
Route::prefix('{network:slug}/{branch:slug}')
    ->middleware(['setlocale', 'setNetwork', 'setBranch'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/install', function (Network $network, School $branch) {
            if ($branch->network_id !== $network->id) {
                abort(404);
            }

            return view('install-instructions', ['school' => $branch]);
        })->name('install.instructions');

        // Desktop App Download Page
        Route::get('/desktop', function (Network $network, School $branch) {
            if ($branch->network_id !== $network->id) {
                abort(404);
            }

            return view('desktop-app', ['school' => $branch]);
        })->name('desktop.app');
    });

// Handle protocol for desktop
Route::get('/handle-protocol', function (Request $request) {
    $url = $request->query('url');
    // Handle custom protocol URLs
    return redirect($url ?: '/');
})->name('protocol.handler');

// Share target endpoint
Route::post('/share-target', function (Request $request) {
    // Handle shared content from share API
    if ($request->hasFile('files')) {
        // Store files temporarily and redirect to upload page
        session()->flash('shared_files', $request->file('files'));

        if (Auth::check() && Auth::user()->school) {
            return redirect()->to(tenant_route('teacher.files.create', Auth::user()->school));
        }
    }

    return redirect('/');
})->name('share.target')->middleware(['setlocale']);


Route::get('/health', \App\Http\Controllers\HealthCheckController::class)
    ->name('health');
