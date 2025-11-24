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
        <nav x-data="{selected: $persist('{{ $currentMenu ?? 'Dashboard' }}')}">
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
                            @click="selected = 'Dashboard'"
                            class="menu-item group"
                            :class="selected === 'Dashboard' || '{{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }}' === 'true' ? 'menu-item-active' : 'menu-item-inactive'"
                        >
                            <svg
                                :class="selected === 'Dashboard' || '{{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }}' === 'true' ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z"
                                    fill=""
                                />
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
                            @click="selected = 'Courses'"
                            class="menu-item group"
                            :class="selected === 'Courses' || '{{ request()->routeIs('admin.courses.*') ? 'true' : 'false' }}' === 'true' ? 'menu-item-active' : 'menu-item-inactive'"
                        >
                            <svg
                                :class="selected === 'Courses' || '{{ request()->routeIs('admin.courses.*') ? 'true' : 'false' }}' === 'true' ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M8.50391 4.25C8.50391 3.83579 8.83969 3.5 9.25391 3.5H15.2777C15.4766 3.5 15.6674 3.57902 15.8081 3.71967L18.2807 6.19234C18.4214 6.333 18.5004 6.52376 18.5004 6.72268V16.75C18.5004 17.1642 18.1646 17.5 17.7504 17.5H16.248V17.4993H14.748V17.5H9.25391C8.83969 17.5 8.50391 17.1642 8.50391 16.75V4.25ZM14.748 19H9.25391C8.01126 19 7.00391 17.9926 7.00391 16.75V6.49854H6.24805C5.83383 6.49854 5.49805 6.83432 5.49805 7.24854V19.75C5.49805 20.1642 5.83383 20.5 6.24805 20.5H13.998C14.4123 20.5 14.748 20.1642 14.748 19.75L14.748 19Z"
                                    fill=""
                                />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Курси
                            </span>
                        </a>
                    </li>

                    {{-- Menu Item Students --}}
                    <li>
                        <a
                            href="{{ route('admin.students.index') }}"
                            @click="selected = 'Students'"
                            class="menu-item group"
                            :class="selected === 'Students' || '{{ request()->routeIs('admin.students.*') ? 'true' : 'false' }}' === 'true' ? 'menu-item-active' : 'menu-item-inactive'"
                        >
                            <svg
                                :class="selected === 'Students' || '{{ request()->routeIs('admin.students.*') ? 'true' : 'false' }}' === 'true' ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M12 2.75C9.92893 2.75 8.25 4.42893 8.25 6.5C8.25 8.57107 9.92893 10.25 12 10.25C14.0711 10.25 15.75 8.57107 15.75 6.5C15.75 4.42893 14.0711 2.75 12 2.75ZM6.75 6.5C6.75 3.60051 9.10051 1.25 12 1.25C14.8995 1.25 17.25 3.60051 17.25 6.5C17.25 9.39949 14.8995 11.75 12 11.75C9.10051 11.75 6.75 9.39949 6.75 6.5ZM5.25 15.5C4.42157 15.5 3.75 16.1716 3.75 17V19.5C3.75 19.9142 3.41421 20.25 3 20.25C2.58579 20.25 2.25 19.9142 2.25 19.5V17C2.25 15.3431 3.59315 14 5.25 14H18.75C20.4069 14 21.75 15.3431 21.75 17V19.5C21.75 19.9142 21.4142 20.25 21 20.25C20.5858 20.25 20.25 19.9142 20.25 19.5V17C20.25 16.1716 19.5784 15.5 18.75 15.5H5.25Z"
                                    fill=""
                                />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Студенти
                            </span>
                        </a>
                    </li>

                    {{-- Menu Item UI Kit --}}
{{--                    <li>--}}
{{--                        <a--}}
{{--                            href="{{ route('admin.ui-kit') }}"--}}
{{--                            @click="selected = 'UIKit'"--}}
{{--                            class="menu-item group"--}}
{{--                            :class="selected === 'UIKit' || '{{ request()->routeIs('admin.ui-kit') ? 'true' : 'false' }}' === 'true' ? 'menu-item-active' : 'menu-item-inactive'"--}}
{{--                        >--}}
{{--                            <svg--}}
{{--                                :class="selected === 'UIKit' || '{{ request()->routeIs('admin.ui-kit') ? 'true' : 'false' }}' === 'true' ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"--}}
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
