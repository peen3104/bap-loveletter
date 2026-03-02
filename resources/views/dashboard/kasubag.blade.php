<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard Kepala Sub Bagian
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <h3>Halo {{ Auth::user()->name }} (Kasubag) 👋</h3>
                <p>Di sini kamu bisa meninjau surat yang dikirim oleh staff.</p>
            </div>
        </div>
    </div>
</x-app-layout>
