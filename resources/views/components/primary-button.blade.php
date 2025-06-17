<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-custom-cyan border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-custom-cyan-dark active:bg-custom-cyan-dark focus:outline-none focus:ring-2 focus:ring-custom-cyan focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
