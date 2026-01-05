<aside
    :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 lg:static lg:translate-x-0"
>
    {{-- SIDEBAR HEADER --}}
    <div
        :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 pt-8 sidebar-header pb-7"
    >
        <a href="{{ route('admin.dashboard') }}">
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
                <span class="text-xl font-semibold text-brand-600">LSE Admin</span>
            </span>

            <span class="logo-icon" :class="sidebarToggle ? 'lg:block' : 'hidden'">
                <span class="text-lg font-bold text-brand-600">L</span>
            </span>
        </a>
    </div>

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        {{-- Sidebar Menu --}}
        <nav>
            {{-- Menu Group --}}
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                        МЕНЮ
                    </span>

                    <svg
                        :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="mx-auto fill-current menu-group-icon"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill=""
                        />
                    </svg>
                </h3>

                <ul class="flex flex-col gap-4 mb-6">
                    {{-- Menu Item Dashboard --}}
                    <li>
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="menu-item group {{ request()->routeIs('admin.dashboard') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.dashboard') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Панель керування
                            </span>
                        </a>
                    </li>

                    {{-- Menu Item Courses --}}
                    <li>
                        <a
                            href="{{ route('admin.courses.index') }}"
                            class="menu-item group {{ request()->routeIs('admin.courses.*') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.courses.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Курси
                            </span>
                        </a>
                    </li>

                    {{-- Menu Item Webinars --}}
                    <li>
                        <a
                            href="{{ route('admin.webinars.index') }}"
                            class="menu-item group {{ request()->routeIs('admin.webinars.*') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.webinars.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Вебінари
                            </span>
                        </a>
                    </li>

                    {{-- Menu Item Students --}}
                    <li>
                        <a
                            href="{{ route('admin.students.index') }}"
                            class="menu-item group {{ request()->routeIs('admin.students.*') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.students.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Студенти
                            </span>
                        </a>
                    </li>

                    @can('access-student-groups')
                    {{-- Menu Item Student Groups --}}
{{--                    <li>--}}
{{--                        <a--}}
{{--                            href="{{ route('admin.student-groups.index') }}"--}}
{{--                            class="menu-item group {{ request()->routeIs('admin.student-groups.*') ? 'menu-item-active' : 'menu-item-inactive' }}"--}}
{{--                        >--}}
{{--                            <svg--}}
{{--                                class="{{ request()->routeIs('admin.student-groups.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"--}}
{{--                                width="24"--}}
{{--                                height="24"--}}
{{--                                viewBox="0 0 24 24"--}}
{{--                                fill="none"--}}
{{--                                stroke="currentColor"--}}
{{--                                stroke-width="1.5"--}}
{{--                                xmlns="http://www.w3.org/2000/svg"--}}
{{--                            >--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />--}}
{{--                            </svg>--}}

{{--                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">--}}
{{--                                Групи студентів--}}
{{--                            </span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    @endcan

                    @can('access-progress')
                    {{-- Menu Item Progress Tree --}}
                    <li>
                        <a
                            href="{{ route('admin.progress.tree') }}"
                            class="menu-item group {{ request()->routeIs('admin.progress.tree') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.progress.tree') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Прогрес студентів
                            </span>
                        </a>
                    </li>

                    {{-- Menu Item Homework --}}
                    <li>
                        <a
                            href="{{ route('admin.homework.index') }}"
                            class="menu-item group {{ request()->routeIs('admin.homework.*') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.homework.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Домашні завдання
                            </span>
                        </a>
                    </li>

                    {{-- Menu Item Comments --}}
                    <li>
                        <a
                            href="{{ route('admin.comments.index') }}"
                            class="menu-item group {{ request()->routeIs('admin.comments.*') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.comments.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Коментарі
                            </span>
                        </a>
                    </li>

                    {{-- Menu Item Certificates --}}
                    <li>
                        <a
                            href="{{ route('admin.certificates.index') }}"
                            class="menu-item group {{ request()->routeIs('admin.certificates.*') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.certificates.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Сертифікати
                            </span>
                        </a>
                    </li>

                    {{-- Menu Item Statistics --}}
                    <li>
                        <a
                            href="{{ route('admin.progress.dashboard') }}"
                            class="menu-item group {{ request()->routeIs('admin.progress.dashboard') || request()->routeIs('admin.progress.course') || request()->routeIs('admin.progress.student') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.progress.dashboard') || request()->routeIs('admin.progress.course') || request()->routeIs('admin.progress.student') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Статистика
                            </span>
                        </a>
                    </li>
                    @endcan

                    @can('access-teachers')
                    {{-- Menu Item Teachers --}}
                    <li>
                        <a
                            href="{{ route('admin.teachers.index') }}"
                            class="menu-item group {{ request()->routeIs('admin.teachers.*') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.teachers.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Викладачі
                            </span>
                        </a>
                    </li>
                    @endcan

                    @can('access-users')
                    {{-- Menu Item Users --}}
                    <li>
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="menu-item group {{ request()->routeIs('admin.users.*') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.users.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Користувачі
                            </span>
                        </a>
                    </li>
                    @endcan

                    @can('access-finances')
                    {{-- Menu Item Finances --}}
                    <li>
                        <a
                            href="{{ route('admin.finances.index') }}"
                            class="menu-item group {{ request()->routeIs('admin.finances.*') ? 'menu-item-active' : 'menu-item-inactive' }}"
                        >
                            <svg
                                class="{{ request()->routeIs('admin.finances.*') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Фінанси
                            </span>
                        </a>
                    </li>
                    @endcan

                    {{-- Menu Item UI Kit --}}
{{--                    <li>--}}
{{--                        <a--}}
{{--                            href="{{ route('admin.ui-kit') }}"--}}
{{--                            class="menu-item group {{ request()->routeIs('admin.ui-kit') ? 'menu-item-active' : 'menu-item-inactive' }}"--}}
{{--                        >--}}
{{--                            <svg--}}
{{--                                class="{{ request()->routeIs('admin.ui-kit') ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"--}}
{{--                                width="24"--}}
{{--                                height="24"--}}
{{--                                viewBox="0 0 24 24"--}}
{{--                                fill="none"--}}
{{--                                xmlns="http://www.w3.org/2000/svg"--}}
{{--                            >--}}
{{--                                <path--}}
{{--                                    fill-rule="evenodd"--}}
{{--                                    clip-rule="evenodd"--}}
{{--                                    d="M3.75 3C3.75 2.58579 4.08579 2.25 4.5 2.25H9.5C9.91421 2.25 10.25 2.58579 10.25 3V9C10.25 9.41421 9.91421 9.75 9.5 9.75H4.5C4.08579 9.75 3.75 9.41421 3.75 9V3ZM5.25 3.75V8.25H8.75V3.75H5.25ZM13.75 3C13.75 2.58579 14.0858 2.25 14.5 2.25H19.5C19.9142 2.25 20.25 2.58579 20.25 3V9C20.25 9.41421 19.9142 9.75 19.5 9.75H14.5C14.0858 9.75 13.75 9.41421 13.75 9V3ZM15.25 3.75V8.25H18.75V3.75H15.25ZM3.75 14C3.75 13.5858 4.08579 13.25 4.5 13.25H9.5C9.91421 13.25 10.25 13.5858 10.25 14V20C10.25 20.4142 9.91421 20.75 9.5 20.75H4.5C4.08579 20.75 3.75 20.4142 3.75 20V14ZM5.25 14.75V19.25H8.75V14.75H5.25ZM13.75 14C13.75 13.5858 14.0858 13.25 14.5 13.25H19.5C19.9142 13.25 20.25 13.5858 20.25 14V20C20.25 20.4142 19.9142 20.75 19.5 20.75H14.5C14.0858 20.75 13.75 20.4142 13.75 20V14ZM15.25 14.75V19.25H18.75V14.75H15.25Z"--}}
{{--                                    fill=""--}}
{{--                                />--}}
{{--                            </svg>--}}

{{--                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">--}}
{{--                                UI Kit--}}
{{--                            </span>--}}
{{--                        </a>--}}
{{--                    </li>--}}

                </ul>
            </div>
        </nav>
    </div>
</aside>
