<x-app-layout>
    <div class="py-8 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">✏️ Edit & Kirim Ulang Surat</h2>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
            <form action="{{ route('letters.update', $letter) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium">Judul Surat</label>
                    <input type="text" name="title" id="title" value="{{ $letter->title }}"
                        class="w-full p-2 border rounded-lg mt-1 dark:bg-gray-700 dark:text-gray-100" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full p-2 border rounded-lg mt-1 dark:bg-gray-700 dark:text-gray-100">{{ $letter->description }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="drive_link" class="block text-sm font-medium">Link Google Drive</label>
                    <input type="url" name="drive_link" id="drive_link" value="{{ $letter->file_path }}"
                        class="w-full p-2 border rounded-lg mt-1 dark:bg-gray-700 dark:text-gray-100"
                        placeholder="https://drive.google.com/file/d/..." required>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('letters.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Batal</a>
                    <x-primary-button>💌 Kirim Ulang</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>