<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
        ]);

        $muzakki = Auth::user()->muzakki;

        if (!$muzakki) {
            return back()->withErrors(['account_number' => 'Profil muzakki belum lengkap.']);
        }

        $isPrimary = !$muzakki->bankAccounts()->exists();

        $muzakki->bankAccounts()->create([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'is_primary' => $isPrimary,
        ]);

        return back()->with('success', 'Akun bank berhasil ditambahkan.');
    }

    public function destroy(BankAccount $bankAccount)
    {
        $this->authorizeAccess($bankAccount);

        $bankAccount->delete();

        // If the deleted account was primary, set another as primary
        if ($bankAccount->is_primary) {
            $next = $bankAccount->muzakki->bankAccounts()->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Akun bank berhasil dihapus.');
    }

    public function setPrimary(BankAccount $bankAccount)
    {
        $this->authorizeAccess($bankAccount);

        $muzakki = $bankAccount->muzakki;

        $muzakki->bankAccounts()->update(['is_primary' => false]);
        $bankAccount->update(['is_primary' => true]);

        return back()->with('success', 'Akun utama diperbarui.');
    }

    protected function authorizeAccess(BankAccount $bankAccount): void
    {
        $muzakki = Auth::user()->muzakki;

        if (!$muzakki || $bankAccount->muzakki_id !== $muzakki->id) {
            abort(403);
        }
    }
}

