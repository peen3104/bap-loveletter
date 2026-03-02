<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard Kepala Bagian 1
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <h3>Selamat datang, {{ Auth::user()->name }} (Kabag 1)!</h3>
                <p>Kamu bisa menyetujui atau memberi catatan atas surat cinta dari Kasubag.</p>
            </div>
        </div>
    </div>
</x-app-layout>
