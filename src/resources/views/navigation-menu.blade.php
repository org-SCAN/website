@php use App\Models\Cytoscape; @endphp
@php use App\Models\Place; @endphp
@php use App\Models\Refugee; @endphp
@php use App\Models\Source; @endphp
@php use App\Models\Link; @endphp
@php use App\Models\Duplicate; @endphp
@php use App\Models\Field; @endphp
@php use App\Models\ListControl; @endphp
@php use App\Models\User; @endphp
@php use App\Models\Crew; @endphp
@php use App\Models\Role; @endphp
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto"/>
                    </a>
                </div>

                <!-- Navigation Links -->
                @can('viewMenu', Cytoscape::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('cytoscape.index') }}"
                                    :active="request()->routeIs('cytoscape.*')">
                            {{ __('navigation-menu.network_graph') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('viewMenu', Refugee::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('person.index') }}"
                                    :active="request()->routeIs('person.*')">
                            {{ __('navigation-menu.items') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('viewMenu', \App\Models\Event::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('event.index') }}"
                                    :active="request()->routeIs('event.*')">
                            {{ __('navigation-menu.events') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('viewMenu', Place::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('place.index') }}"
                                    :active="request()->routeIs('place.*')">
                            {{ __('navigation-menu.place') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('viewMenu', Source::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('source.index') }}"
                                    :active="request()->routeIs('source.*')">
                            {{ __('navigation-menu.sources') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('viewMenu', Link::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('links.index') }}" :active="request()->routeIs('links.*')">
                            {{ __('navigation-menu.relations') }}
                        </x-nav-link>
                    </div>
                @endcan

                @can('viewMenu', Duplicate::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('duplicate.index') }}"
                                    :active="request()->routeIs('duplicate.*')">
                            {{ __('navigation-menu.duplicates') }}
                        </x-nav-link>
                    </div>
                @endcan

                <!-- Fields Management Dropdown -->
                @if ( Auth::user()->can('viewMenu', Field::class) || Auth::user()->can('viewMenu',  ListControl::class))
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-dropdown align="right" width="48"
                                    :active="request()->routeIs('fields.*')||request()->routeIs('lists_control.*')">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md pt-4">
                                    <button type="button"
                                            class="inline-flex items-center text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                        {{ __('navigation-menu.field_management')}}

                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Fields Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('navigation-menu.field_management') }}
                                </div>
                                @can('viewMenu', Field::class)
                                    <x-dropdown-link href="{{ route('fields.index') }}"
                                                     :active="request()->routeIs('fields.*')">
                                        {{ __('navigation-menu.fields') }}
                                    </x-dropdown-link>
                                @endcan
                                @can("viewMenu", ListControl::class)
                                    <x-dropdown-link href="{{ route('lists_control.index') }}"
                                                     :active="request()->routeIs('lists_control.*')">
                                        {{ __('navigation-menu.lists') }}
                                    </x-dropdown-link>
                                @endcan

                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif


                <!-- User Management Dropdown -->
                @if ( Auth::user()->can('viewMenu', User::class) || Auth::user()->can('viewMenu',  Crew::class)|| Auth::user()->can('viewMenu',  Role::class) )
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-dropdown align="right" width="48"
                                    :active="request()->routeIs('user.*')||request()->routeIs('crew.*')||request()->routeIs('roles.*')">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md pt-4">
                                    <button
                                        class="inline-flex items-center text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                        {{ __('navigation-menu.user_management')}}

                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('navigation-menu.user_management') }}
                                </div>
                                @can('viewMenu', User::class)
                                    <x-dropdown-link href="{{ route('user.index') }}"
                                                     :active="request()->routeIs('user.*')">
                                        {{ __('navigation-menu.users') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('viewMenu', Crew::class)
                                    <x-dropdown-link href="{{ route('crew.index') }}"
                                                     :active="request()->routeIs('crew.*')">
                                        {{ __('navigation-menu.teams') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('viewMenu', Role::class)
                                    <x-dropdown-link href="{{ route('roles.index') }}"
                                                     :active="request()->routeIs('roles.*')">
                                        {{ __('navigation-menu.roles') }}
                                    </x-dropdown-link>
                                @endcan

                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ml-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('navigation-menu.manage_team') }}
                                    </div>

                                    /*<!-- Team Settings -->
                                    <x-dropdown-link
                                        href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('navigation-menu.team_settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('navigation-menu.create_new_team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <div class="border-t border-gray-100"></div>

                                    <!-- Team Switcher -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('navigation-menu.switch_teams') }}
                                    </div>

                                    @foreach (Auth::user()->allTeams() as $team)
                                        <x-switchable-team :team="$team"/>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover"
                                         src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"/>
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                        {{ Auth::user()->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('navigation-menu.manage_account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('navigation-menu.profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('navigation-menu.api_tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                                 onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('navigation-menu.log_out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('cytoscape.index') }}"
                                   :active="request()->routeIs('cytoscape.*')">
                {{ __('navigation-menu.network_graph') }}
            </x-responsive-nav-link>
            @can('viewMenu',Refugee::class)
                <x-responsive-nav-link href="{{ route('person.index') }}"
                                       :active="request()->routeIs('person.*')">
                    {{ __('navigation-menu.items') }}
                </x-responsive-nav-link>
            @endcan
            @can('viewMenu', \App\Models\Event::class)
                <x-responsive-nav-link href="{{ route('event.index') }}"
                                       :active="request()->routeIs('event.*')">
                    {{ __('navigation-menu.events') }}
                </x-responsive-nav-link>
            @endcan
            @can('viewMenu', Source::class)
                <x-responsive-nav-link href="{{ route('source.index') }}"
                                       :active="request()->routeIs('source.*')">
                    {{ __('navigation-menu.sources') }}
                </x-responsive-nav-link>
            @endcan
            @can('viewMenu',Link::class)
                <x-responsive-nav-link href="{{ route('links.index') }}"
                                       :active="request()->routeIs('links.*')">
                    {{ __('navigation-menu.relations') }}
                </x-responsive-nav-link>
            @endcan
            @can('viewMenu',Duplicate::class)
                <x-responsive-nav-link href="{{ route('duplicate.index') }}"
                                       :active="request()->routeIs('duplicate.*')">
                    {{ __('navigation-menu.duplicates') }}
                </x-responsive-nav-link>
            @endcan
            <div class="pt-2 pb-1 border-t border-gray-200">
                <div class="font-medium text-base text-gray-400 px-2">Field Management</div>
                @can('viewMenu',Field::class)
                    <x-responsive-nav-link href="{{ route('fields.index') }}"
                                           :active="request()->routeIs('fields.*')">
                        {{ __('navigation-menu.fields') }}
                    </x-responsive-nav-link>
                @endcan
                @can('viewMenu',ListControl::class)
                    <x-responsive-nav-link href="{{ route('lists_control.index') }}"
                                           :active="request()->routeIs('lists_control.*')">
                        {{ __('navigation-menu.lists') }}
                    </x-responsive-nav-link>
                @endcan
            </div>
            <div class="pt-2 pb-1 border-t border-gray-200">
                <div class="font-medium text-base text-gray-400 px-2">User Management</div>
                @can('viewMenu',User::class)
                    <x-responsive-nav-link href="{{ route('user.index') }}"
                                           :active="request()->routeIs('user.*')">
                        {{ __('navigation-menu.users') }}
                    </x-responsive-nav-link>
                @endcan
                @can('viewMenu',Crew::class)
                    <x-responsive-nav-link href="{{ route('crew.index') }}"
                                           :active="request()->routeIs('crew.*')">
                        {{ __('navigation-menu.teams') }}
                    </x-responsive-nav-link>
                @endcan
                @can('viewMenu',Role::class)
                    <x-responsive-nav-link href="{{ route('roles.index') }}"
                                           :active="request()->routeIs('roles.*')">
                        {{ __('navigation-menu.roles') }}
                    </x-responsive-nav-link>
                @endcan
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="flex-shrink-0 mr-3">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                                 alt="{{ Auth::user()->name }}"/>
                        </div>
                    @endif

                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}"
                                           :active="request()->routeIs('profile.show')">
                        {{ __('navigation-menu.profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('navigation-menu.log_out') }}
                        </x-responsive-nav-link>
                    </form>

                    <!-- Team Management -->
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('navigation-menu.manage_team') }}
                        </div>

                        <!-- Team Settings -->
                        <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                                               :active="request()->routeIs('teams.show')">
                            {{ __('navigation-menu.team_settings') }}
                        </x-responsive-nav-link>

                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                            <x-responsive-nav-link href="{{ route('teams.create') }}"
                                                   :active="request()->routeIs('teams.create')">
                                {{ __('navigation-menu.create_new_team') }}
                            </x-responsive-nav-link>
                        @endcan

                        <div class="border-t border-gray-200"></div>

                        <!-- Team Switcher -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('navigation-menu.switch_teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="jet-responsive-nav-link"/>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>

