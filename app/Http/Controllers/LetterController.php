<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LetterController extends Controller
{
    // 📄 Halaman daftar surat
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'staff') {
            // Staff hanya lihat surat yang dia upload
            $letters = Letter::where('uploaded_by', $user->id)->get();
        } elseif ($user->role === 'kasubag') {
            // Kasubag lihat surat yang menunggu persetujuan kasubag
            $letters = Letter::where('status', 'menunggu_kasubag')->get();
        } elseif ($user->role === 'kabag1') {
            // Kabag1 lihat surat yang menunggu persetujuan kabag1
            $letters = Letter::where('status', 'menunggu_kabag1')->get();
        } else {
            $letters = collect(); // kosong untuk role lain
        }

        return view('letters.index', compact('letters'));
    }

    // ✉️ Kirim surat baru (pakai link Drive)
    public function store(Request $request)
    {
        $user = Auth::user();

        // Hanya staff yang boleh upload
        if ($user->role !== 'staff') {
            abort(403, 'Hanya staff yang boleh upload surat.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'drive_link' => 'required|url',
        ]);

        Letter::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $request->drive_link, // Simpan link Google Drive
            'uploaded_by' => $user->id,
            'status' => 'menunggu_kasubag', // default pertama
        ]);

        return redirect()->route('letters.index')->with('success', 'Surat berhasil dikirim!');
    }

    // 🔁 Update status (ACC / Tolak)
    public function updateStatus(Request $request, Letter $letter)
    {
        $request->validate([
            'action' => 'required|in:acc,tolak',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        if ($request->action === 'acc') {
            // Alur ACC
            if ($letter->status === 'menunggu_kasubag' && $user->role === 'kasubag') {
                $letter->status = 'menunggu_kabag1';
            } elseif ($letter->status === 'menunggu_kabag1' && $user->role === 'kabag1') {
                $letter->status = 'disetujui';
            } else {
                abort(403, 'Anda tidak bisa mengubah status surat ini.');
            }
        } else {
            // Alur penolakan
            $letter->status = 'ditolak';
            $letter->notes = $request->notes;
        }

        $letter->save();

        return redirect()->route('letters.index')->with('success', 'Status surat berhasil diperbarui!');
    }

    // 📝 Form Edit Surat
public function edit(Letter $letter)
{
    $user = Auth::user();

    // Pastikan staff hanya bisa edit surat miliknya dan yang ditolak
    if ($user->role !== 'staff' || $letter->uploaded_by !== $user->id || $letter->status !== 'ditolak') {
        abort(403, 'Anda tidak diizinkan mengedit surat ini.');
    }

    return view('letters.edit', compact('letter'));
}

// 🔁 Update Surat & Kirim Ulang
public function update(Request $request, Letter $letter)
{
    $user = Auth::user();

    if ($user->role !== 'staff' || $letter->uploaded_by !== $user->id || $letter->status !== 'ditolak') {
        abort(403, 'Anda tidak diizinkan memperbarui surat ini.');
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'drive_link' => 'required|url',
    ]);

    $letter->update([
        'title' => $request->title,
        'description' => $request->description,
        'file_path' => $request->drive_link,
        'status' => 'menunggu_kasubag', // kirim ulang ke kasubag
        'notes' => null, // reset catatan penolakan
    ]);

    return redirect()->route('letters.index')->with('success', 'Surat berhasil diperbarui dan dikirim ulang!');
}

}