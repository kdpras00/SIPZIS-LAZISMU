<?php

namespace App\Observers;

use App\Models\Muzakki;

class MuzakkiObserver
{
    /**
     * Handle the Muzakki "creating" event.
     * This runs before a new muzakki is saved to the database.
     */
    public function creating(Muzakki $muzakki): void
    {
        // Auto-generate campaign_url if email exists and campaign_url is not set
        if ($muzakki->email && empty($muzakki->campaign_url)) {
            $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
        }
    }

    /**
     * Handle the Muzakki "updating" event.
     * This runs before an existing muzakki is updated.
     */
    public function updating(Muzakki $muzakki): void
    {
        // If email is being changed, update campaign_url
        if ($muzakki->isDirty('email') && $muzakki->email) {
            $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
        }
        
        // If campaign_url is empty but email exists, generate it
        if (empty($muzakki->campaign_url) && $muzakki->email) {
            $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
        }
    }

    /**
     * Handle the Muzakki "created" event.
     */
    public function created(Muzakki $muzakki): void
    {
        //
    }

    /**
     * Handle the Muzakki "updated" event.
     */
    public function updated(Muzakki $muzakki): void
    {
        //
    }

    /**
     * Handle the Muzakki "deleted" event.
     */
    public function deleted(Muzakki $muzakki): void
    {
        //
    }

    /**
     * Handle the Muzakki "restored" event.
     */
    public function restored(Muzakki $muzakki): void
    {
        //
    }

    /**
     * Handle the Muzakki "force deleted" event.
     */
    public function forceDeleted(Muzakki $muzakki): void
    {
        //
    }
}

