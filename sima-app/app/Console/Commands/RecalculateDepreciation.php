<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;

class RecalculateDepreciation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:recalculate-depreciation {--id= : Specific asset ID to recalculate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate depreciation values for all assets or a specific asset';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $assetId = $this->option('id');
        
        if ($assetId) {
            $asset = Asset::find($assetId);
            if (!$asset) {
                $this->error("Asset with ID {$assetId} not found.");
                return 1;
            }
            
            $this->recalculateAsset($asset);
            $this->info("Asset {$asset->code} recalculated successfully.");
            return 0;
        }
        
        $assets = Asset::with('category')->get();
        $count = $assets->count();
        
        if ($count === 0) {
            $this->info('No assets found to recalculate.');
            return 0;
        }
        
        $this->info("Recalculating depreciation for {$count} assets...");
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        $updated = 0;
        foreach ($assets as $asset) {
            $oldValue = $asset->current_value;
            $this->recalculateAsset($asset);
            
            if ($oldValue != $asset->fresh()->current_value) {
                $updated++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info("Completed! {$updated} assets had their values updated.");
        
        return 0;
    }
    
    private function recalculateAsset(Asset $asset): void
    {
        $asset->updateCurrentValue();
    }
}
