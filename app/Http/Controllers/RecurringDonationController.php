<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\RecurringDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecurringDonationController extends Controller
{
    public function create()
    {
        $muzakki = Auth::user()->muzakki;

        if (!$muzakki) {
            return redirect()->route('profile.show')->with('info', 'Silakan lengkapi profil muzakki Anda.');
        }

        $programs = Program::active()->orderBy('name')->get();

        return view('muzakki.dashboard.recurring-create', compact('muzakki', 'programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'nullable|exists:programs,id',
            'amount' => 'required|numeric|min:10000',
            'frequency' => 'required|in:weekly,monthly',
            'start_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ]);

        $muzakki = Auth::user()->muzakki;

        if (!$muzakki) {
            return back()->withErrors(['amount' => 'Profil muzakki belum lengkap.']);
        }

        $muzakki->recurringDonations()->create([
            'program_id' => $request->program_id,
            'amount' => $request->amount,
            'frequency' => $request->frequency,
            'start_date' => $request->start_date,
            'notes' => $request->notes,
            'is_active' => true,
        ]);

        return back()->with('success', 'Donasi rutin berhasil dibuat.');
    }

    public function toggle(RecurringDonation $recurringDonation)
    {
        $this->authorizeAccess($recurringDonation);

        $recurringDonation->update([
            'is_active' => !$recurringDonation->is_active,
        ]);

        return back()->with('success', 'Status donasi rutin diperbarui.');
    }

    public function destroy(RecurringDonation $recurringDonation)
    {
        $this->authorizeAccess($recurringDonation);

        $recurringDonation->delete();

        return back()->with('success', 'Donasi rutin dihapus.');
    }

    protected function authorizeAccess(RecurringDonation $recurringDonation): void
    {
        $muzakki = Auth::user()->muzakki;

        if (!$muzakki || $recurringDonation->muzakki_id !== $muzakki->id) {
            abort(403);
        }
    }
}

