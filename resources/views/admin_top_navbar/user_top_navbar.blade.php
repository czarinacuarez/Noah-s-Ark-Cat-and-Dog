<nav x-data="{ open: false }" class="  bg-white lg:border-transparent dark:bg-gray-800  dark:border-gray-700
@if(  Route::is('chat.index') || Route::is('user.notifications') ||  Route::is('interview.user') || Route::is('user.volunteerprogress') || Route::is('user.volunteer') ||Route::is('user.adoptionprogress') ||Route::is('user.adoption') || Route::is('user.pet') || Route::is('admin.adoptions') || Route::is('admin.volunteers') || Route::is('admin.schedule') || Route::is('profile.edit') || Route::is('user.dashboard') ||  Route::is('user.applications'))
lg:bg-transparent
@else
lg:bg-red-800
@endif">
    <!-- Primary Navigation Menu -->
    {{-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> --}}
        <div class=" lg:flex lg:justify-end">

        <div class="flex justify-between mx-auto lg:mx-1 max-w-screen-2xl px-4 lg:px-12 h-16">
            <!-- Layout with sidebar on left, space in the middle, and hamburger on right -->
            <div class="flex items-center  sm:hidden">
                <!-- Sidebar button on the left -->
                <button
                    data-drawer-target="default-sidebar"
                    data-drawer-toggle="default-sidebar"
                    aria-controls="default-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 sm:hidden"
                >
                    <span class="sr-only">Open sidebar</span> 
                    <svg
                        class="w-6 h-6"
                        aria-hidden="true"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            clip-rule="evenodd"
                            fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                        ></path>
                    </svg>
                </button>
            </div>
        
            <div class="flex items-center  sm:hidden">

                <!-- Huge space in the middle -->
                <div class="flex-grow"></div>

                <div>
                    <div class = "text-red-800 lg:hidden px-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z" clip-rule="evenodd" />
                          </svg>
                        
                        </div>
                </div>
                <!-- Hamburger button on the right -->
                <button
                @click="open = !open"
                class="flex items-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out lg:justify-end">
                <!-- Image and profile indication -->
                <div class="flex items-center ">
                    <!-- Check the image path and make sure it's correct -->
                    <img class="h-8 w-8 rounded-full mr-2" src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
                    
                    <!-- Profile indication icon -->
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path
                            :class="{'hidden': open, 'inline-flex': !open}"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            :class="{'hidden': !open, 'inline-flex': open}"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </div>
            </button>
            
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdownnotif align="right" width="80">
                    <x-slot name="trigger">
                        <div class="relative inline-block text-left">
                            <div class="flex items-center relative">
                                <div class="@if($unreadNotificationsCount == 0 )
                                hidden
                                @else
                                absolute top-0 right-0 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs pointer-events-none
                                @endif">
                                    {{$unreadNotificationsCount}}
                                </div>
                                <button id="notificationBellTrigger" class="flex items-center p-1 
                                @if(  Route::is('chat.index') || Route::is('user.notifications') ||  Route::is('interview.user') || Route::is('user.volunteerprogress') ||Route::is('user.volunteer') || Route::is('user.adoptionprogress') ||Route::is('user.adoption') || Route::is('user.pet') || Route::is('admin.adoptions') || Route::is('admin.volunteers') || Route::is('admin.schedule') ||  Route::is('profile.edit') || Route::is('user.dashboard') ||  Route::is('user.applications'))
                                text-red-700
                                @else
                                text-white
                                @endif">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                        <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                            </svg>
                        </button>
                    </x-slot>
                
                    <x-slot name="content">
                        <div class="max-h-60 overflow-y-auto">
                            @if($userNotifications)
                                @foreach($userNotifications as $notification)
                                    @if ($notification->concern == 'Adoption Application')
                                        <x-dropdownapply-link :href="route('user.adoptionprogress', ['userId' => auth()->id(), 'applicationId' => $notification->application->id])" :image-source="'/images/logo.png'" :name="$notification->user->firstname . ' ' .$notification->user->name" :currentDate="$notification->created_at->diffForHumans()" :markAsRead="$notification->mark_as_read" :notificationId="$notification->id">
                                            {{ $notification->message }}
                                        </x-dropdownapply-link>
                                    @elseif ($notification->concern == 'Volunteer Application')
                                        <x-dropdownvapply-link :href="route('user.volunteerprogress', ['userId' => auth()->user()->id, 'applicationId' => $notification->application->id])" :image-source="'/images/logo.png'" :name="$notification->user->firstname . ' ' .$notification->user->name" :currentDate="$notification->created_at->diffForHumans()" :markAsRead="$notification->mark_as_read" :notificationId="$notification->id">
                                            {{ $notification->message }}
                                        </x-dropdownvapply-link>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                         <!-- "Show More" button is now outside the max-h-60 container -->
                        <a href= "{{ route('user.notifications') }}" class="cursor-pointer block w-full px-4 py-2 text-center text-sm font-bold text-red-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 inline-block align-middle">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm0 8.625a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25ZM15.375 12a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0ZM7.5 10.875a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25Z" clip-rule="evenodd" />
                            </svg>
                            <span class="inline-block align-middle">{{ __('Show More') }}</span>
                        </a>
                    </x-slot>
                </x-dropdownnotif>
             
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <div class="relative inline-block text-left">
                            <div class="flex items-center relative">
                                <!-- Dropdown button with image and icon -->
                                <button class="flex items-center bg-white p-1 rounded-full shadow-lg">
                                    <!-- Check the image path and make sure it's correct -->
                                    <img class="h-8 w-8 rounded-full" src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
                                    
                                    <!-- Dropdown icon -->
                                    <svg class="fill-current h-4 w-4 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                            </svg>
                         </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('user.profile', ['id' => auth()->id()])">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form class="mb-0" method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>         
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        
        {{-- <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div> --}}

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->firstname }} {{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }} </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('user.profile', ['id' => auth()->id()])">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownButton = document.getElementById('notificationBellTrigger');
        if (dropdownButton) {
            dropdownButton.addEventListener('click', function() {
                console.log('bell is triggered');
                markNotificationsAsRead();
            });
        } else {
            console.error('Dropdown button not found');
        }
    });

    function markNotificationsAsRead() {
        fetch('/mark-notifications-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({
                
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to mark notifications as read');
                }
                
            })
            .catch(error => {
                console.error('Error marking notifications as read:', error);
            });
        event.preventDefault(); 
    }
</script>

