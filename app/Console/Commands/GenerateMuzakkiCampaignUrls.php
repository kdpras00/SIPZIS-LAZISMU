<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Muzakki;

class GenerateMuzakkiCampaignUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'muzakki:generate-campaign-urls 
                            {--force : Force regenerate campaign URLs for all muzakki}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate campaign URLs for muzakki that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting campaign URL generation...');
        
        // Get muzakki based on options
        if ($this->option('force')) {
            $muzakkis = Muzakki::whereNotNull('email')->get();
            $this->info('Force mode: Regenerating campaign URLs for ALL muzakki with email...');
        } else {
            $muzakkis = Muzakki::whereNotNull('email')
                ->where(function ($query) {
                    $query->whereNull('campaign_url')
                        ->orWhere('campaign_url', '');
                })
                ->get();
            $this->info('Normal mode: Generating campaign URLs only for muzakki without one...');
        }
        
        if ($muzakkis->isEmpty()) {
            $this->info('No muzakki found that need campaign URL generation.');
            return Command::SUCCESS;
        }
        
        $this->info("Found {$muzakkis->count()} muzakki to process.");
        
        $bar = $this->output->createProgressBar($muzakkis->count());
        $bar->start();
        
        $updated = 0;
        $skipped = 0;
        
        foreach ($muzakkis as $muzakki) {
            try {
                $oldUrl = $muzakki->campaign_url;
                $newUrl = url('/campaigner/' . $muzakki->email);
                
                // Update campaign URL
                $muzakki->campaign_url = $newUrl;
                $muzakki->save();
                
                $updated++;
                
                // Show detailed info if verbose
                if ($this->output->isVerbose()) {
                    $this->newLine();
                    $this->line("Updated: {$muzakki->name} ({$muzakki->email})");
                    if ($oldUrl) {
                        $this->line("  Old: {$oldUrl}");
                    }
                    $this->line("  New: {$newUrl}");
                }
            } catch (\Exception $e) {
                $skipped++;
                $this->newLine();
                $this->error("Failed to update {$muzakki->name}: " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        // Summary
        $this->info('Campaign URL Generation Complete!');
        $this->table(
            ['Status', 'Count'],
            [
                ['Updated', $updated],
                ['Skipped/Failed', $skipped],
                ['Total Processed', $muzakkis->count()],
            ]
        );
        
        return Command::SUCCESS;
    }
}

