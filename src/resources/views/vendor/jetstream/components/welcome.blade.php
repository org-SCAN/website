@section('title',"Dashboard")
<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    <div class="flex items-center">
        <x-jet-application-logo class="block h-12 w-auto"/>
        <div class="ml-4 text-2xl text-gray-600">
            {!! __('dashboard.welcome')  !!}
        </div>
    </div>

    <div class="mt-8">
        {!! __('dashboard.description') !!}
    </div>
</div>
