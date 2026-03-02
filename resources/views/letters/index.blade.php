<x-app-layout>
    <div class="py-8 max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">📄 Daftar Surat</h2>

        {{-- ✅ STAFF: Form Upload Surat --}}
        @if(Auth::user()->role === 'staff')
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow mb-10">
                <h3 class="text-lg font-semibold mb-4">Kirim Surat Baru</h3>
                <form action="{{ route('letters.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium">Judul Surat</label>
                        <input type="text" name="title" id="title"
                            class="w-full p-2 border rounded-lg mt-1 dark:bg-gray-700 dark:text-gray-100" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium">Deskripsi</label>
                        <textarea name="description" id="description"
                            class="w-full p-2 border rounded-lg mt-1 dark:bg-gray-700 dark:text-gray-100"
                            rows="3" placeholder="Contoh: Surat permohonan izin acara kampus"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="drive_link" class="block text-sm font-medium">Link Google Drive</label>
                        <input type="url" name="drive_link" id="drive_link"
                            class="w-full p-2 border rounded-lg mt-1 dark:bg-gray-700 dark:text-gray-100"
                            placeholder="https://drive.google.com/file/d/..." required>
                    </div>

                    <x-primary-button>Kirim Surat</x-primary-button>
                </form>
            </div>
        @endif

        {{-- ✅ TABEL SURAT --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-200">
                <thead class="border-b dark:border-gray-600">
                    <tr>
                        <th class="p-3">Judul</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Diupload Oleh</th>
                        <th class="p-3">Catatan</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($letters as $letter)
                        <tr class="border-b dark:border-gray-700">
                            <td class="p-3">{{ $letter->title }}</td>
                            <td class="p-3">
                                @switch($letter->status)
                                    @case('menunggu_kasubag')
                                        <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded">Menunggu Kasubag</span>
                                        @break
                                    @case('menunggu_kabag1')
                                        <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded">Menunggu Kabag1</span>
                                        @break
                                    @case('disetujui')
                                        <span class="bg-green-200 text-green-800 px-2 py-1 rounded">Disetujui</span>
                                        @break
                                    @case('ditolak')
                                        <span class="bg-red-200 text-red-800 px-2 py-1 rounded">Ditolak</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="p-3">{{ $letter->user->name }}</td>
                            <td class="p-3">{{ $letter->notes ?? '-' }}</td>

                            {{-- ✅ AKSI --}}
                            <td class="p-3 text-center">
                                {{-- Lihat Surat (Link Drive) --}}
                                <a href="{{ $letter->file_path }}" target="_blank"
                                    class="text-indigo-600 hover:underline">📎 Lihat</a>

                                {{-- Kasubag bisa ACC/Tolak --}}
                                @if(Auth::user()->role === 'kasubag' && $letter->status === 'menunggu_kasubag')
                                    <form action="{{ route('letters.updateStatus', $letter) }}" method="POST" class="inline-block ml-2">
                                        @csrf
                                        <input type="hidden" name="action" value="acc">
                                        <x-primary-button>ACC</x-primary-button>
                                    </form>

                                    <button onclick="openNotesModal({{ $letter->id }})"
                                        class="ml-1 bg-red-600 text-white px-3 py-1 rounded">Tolak</button>
                                @endif

                                {{-- Kabag1 bisa ACC/Tolak --}}
                                @if(Auth::user()->role === 'kabag1' && $letter->status === 'menunggu_kabag1')
                                    <form action="{{ route('letters.updateStatus', $letter) }}" method="POST" class="inline-block ml-2">
                                        @csrf
                                        <input type="hidden" name="action" value="acc">
                                        <x-primary-button>ACC</x-primary-button>
                                    </form>

                                    <button onclick="openNotesModal({{ $letter->id }})"
                                        class="ml-1 bg-red-600 text-white px-3 py-1 rounded">Tolak</button>
                                @endif

                                {{-- 🟡 Jika surat ditolak, tampilkan tombol Edit & Kirim Ulang (khusus staff) --}}
                                @if(Auth::user()->role === 'staff' && $letter->status === 'ditolak')
                                    <a href="{{ route('letters.edit', $letter) }}"
                                        class="ml-2 bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                        ✏️ Edit & Kirim Ulang
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-3 text-gray-500">Belum ada surat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ✅ MODAL NOTES --}}
    <div id="notesModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Tolak Surat</h3>
            <form id="notesForm" method="POST">
                @csrf
                <input type="hidden" name="action" value="tolak">
                <textarea name="notes" rows="4" placeholder="Masukkan alasan penolakan..."
                    class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100" required></textarea>
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeNotesModal()" class="mr-2 bg-gray-400 text-white px-3 py-1 rounded">Batal</button>
                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openNotesModal(letterId) {
            const form = document.getElementById('notesForm');
            form.action = `/letters/${letterId}/status`;
            document.getElementById('notesModal').classList.remove('hidden');
        }

        function closeNotesModal() {
            document.getElementById('notesModal').classList.add('hidden');
        }
    </script>
</x-app-layout>