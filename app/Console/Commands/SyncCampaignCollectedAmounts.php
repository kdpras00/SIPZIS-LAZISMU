<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\ZakatPayment;

class SyncCampaignCollectedAmounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:sync-collected-amounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync collected_amount in campaigns table based on actual payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing collected_amount for all campaigns...');

        $campaigns = Campaign::all();
        $updated = 0;

        foreach ($campaigns as $campaign) {
            $collectedAmount = 0;

            // Calculate collected amount based on campaign's program_id or program_category
            if ($campaign->program_id) {
                $collectedAmount = ZakatPayment::where('program_id', $campaign->program_id)
                    ->where('status', 'completed')
                    ->where('created_at', '>=', $campaign->created_at)
                    ->sum('paid_amount');
            } else {
                $collectedAmount = ZakatPayment::where('program_category', $campaign->program_category)
                    ->whereNotNull('program_category')
                    ->where('status', 'completed')
                    ->where('created_at', '>=', $campaign->created_at)
                    ->sum('paid_amount');
            }

            // Update collected_amount in database
            $campaign->update(['collected_amount' => $collectedAmount]);
            $updated++;

            $this->line("Updated campaign '{$campaign->title}': Rp " . number_format($collectedAmount, 0, ',', '.'));
        }

        $this->info("Successfully synced {$updated} campaigns!");
        return 0;
    }
}
