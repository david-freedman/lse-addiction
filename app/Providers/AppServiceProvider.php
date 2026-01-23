<?php

namespace App\Providers;

use App\Domains\Certificate\Models\Certificate;
use App\Domains\Course\Models\Course;
use App\Domains\Dashboard\ViewModels\StudentDashboardViewModel;
use App\Domains\Homework\Models\Homework;
use App\Domains\Homework\Models\HomeworkSubmission;
use App\Domains\Homework\Observers\HomeworkSubmissionObserver;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Student\Models\Student;
use App\Models\User;
use App\Policies\CertificatePolicy;
use App\Policies\CoursePolicy;
use App\Policies\HomeworkPolicy;
use App\Policies\HomeworkSubmissionPolicy;
use App\Policies\LessonPolicy;
use App\Policies\ModulePolicy;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('pagination.tailwind');

        HomeworkSubmission::observe(HomeworkSubmissionObserver::class);

        $this->configureAuthorization();
        $this->configureViewComposers();
    }

    private function configureAuthorization(): void
    {
        Gate::before(function (Authenticatable $user, string $ability) {
            if ($user instanceof User && $user->isAdmin()) {
                return true;
            }
        });

        Gate::policy(Certificate::class, CertificatePolicy::class);
        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Module::class, ModulePolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);
        Gate::policy(Homework::class, HomeworkPolicy::class);
        Gate::policy(HomeworkSubmission::class, HomeworkSubmissionPolicy::class);

        Gate::define('access-teachers', fn (Authenticatable $user) => $user instanceof User && false);
        Gate::define('access-users', fn (Authenticatable $user) => $user instanceof User && false);
        Gate::define('access-finances', fn (Authenticatable $user) => $user instanceof User && false);
        Gate::define('access-student-groups', fn (Authenticatable $user) => $user instanceof User && ($user->isAdmin() || $user->isTeacher()));
        Gate::define('access-progress', fn (Authenticatable $user) => $user instanceof User && ($user->isAdmin() || $user->isTeacher()));
    }

    private function configureViewComposers(): void
    {
        View::composer('layouts.app', function ($view) {
            if (auth()->check() && auth()->user() instanceof Student) {
                $viewModel = new StudentDashboardViewModel(auth()->user());
                $view->with('calendarData', $viewModel->calendarData());
            }
        });
    }
}
