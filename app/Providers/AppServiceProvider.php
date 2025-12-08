<?php

namespace App\Providers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Models\User;
use App\Policies\CoursePolicy;
use App\Policies\LessonPolicy;
use App\Policies\ModulePolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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

        $this->configureAuthorization();
    }

    private function configureAuthorization(): void
    {
        Gate::before(function (User $user, string $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Module::class, ModulePolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);

        Gate::define('access-teachers', fn (User $user) => false);
        Gate::define('access-users', fn (User $user) => false);
        Gate::define('access-finances', fn (User $user) => false);
        Gate::define('access-student-groups', fn (User $user) => $user->isAdmin() || $user->isTeacher());
        Gate::define('access-progress', fn (User $user) => $user->isAdmin());
    }
}
