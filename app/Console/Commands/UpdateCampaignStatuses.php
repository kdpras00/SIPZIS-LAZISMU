<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateCampaignStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically delete expired campaigns when they reach their end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Deleting expired campaigns...');

        // Find all published campaigns that have ended
        $expiredCampaigns = Campaign::where('status', 'published')
            ->whereNotNull('end_date')
            ->where('end_date', '<', Carbon::now())
            ->get();

        $deletedCount = 0;
        $errorCount = 0;

        foreach ($expiredCampaigns as $campaign) {
            try {
                $campaignTitle = $campaign->title;
                
                // Delete photo if exists
                if ($campaign->photo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($campaign->photo);
                }
                
                // Delete the campaign
                $campaign->delete();
                $deletedCount++;

                $this->info("Deleted expired campaign '{$campaignTitle}'.");
                
                // Log the deletion
                Log::info("Expired campaign automatically deleted", [
                    'campaign_id' => $campaign->id,
                    'campaign_title' => $campaignTitle,
                    'end_date' => $campaign->end_date
                ]);
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("Failed to delete campaign '{$campaign->title}': " . $e->getMessage());
                Log::error("Failed to automatically delete expired campaign", [
                    'campaign_id' => $campaign->id,
                    'campaign_title' => $campaign->title,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Completed! Deleted {$deletedCount} expired campaigns.");
        
        if ($errorCount > 0) {
            $this->warn("Encountered errors with {$errorCount} campaigns.");
        }

        // Also log for monitoring purposes
        Log::info("Expired campaign deletion job completed", [
            'total_expired' => $expiredCampaigns->count(),
            'deleted_count' => $deletedCount,
            'error_count' => $errorCount
        ]);

        return Command::SUCCESS;
    }
}