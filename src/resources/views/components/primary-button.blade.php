<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-blue-300 rounded-md font-semibold text-xs text-blue-700 uppercase tracking-widest shadow-sm hover:text-blue-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-blue-800 active:bg-blue-50 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
