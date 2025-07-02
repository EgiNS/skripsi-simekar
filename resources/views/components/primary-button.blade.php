<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-3 py-2 bg-[#CB0C9F] font-medium hover:bg-[#b42f95] text-white rounded']) }}>
    {{ $slot }}
</button>
