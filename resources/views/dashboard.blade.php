<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->role === 'Admin')
                <livewire:dashboard.main />
            @elseif(Auth::user()->role === 'Vendor')
                <livewire:dashboard.vendor-dashboard />
            @elseif(Auth::user()->role === 'Customers')
                <livewire:dashboard.customer-dashboard />
            @endif
        </div>
    </div>
</x-app-layout>

{{-- @if(Auth::user()->role === 'Admin')
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Admin') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <livewire:dashboard.main />
            </div>
        </div>
    </x-app-layout>
@elseif(Auth::user()->role === 'Vendor')
    <x-vend-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Vendor') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <livewire:project.project-list-vendor />
            </div>
        </div>
    </x-vend-layout>
@elseif(Auth::user()->role === 'Customers')
    <x-cust-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Customer') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <livewire:project.project-list-customer />
            </div>
        </div>
    </x-cust-layout>
@endif --}}