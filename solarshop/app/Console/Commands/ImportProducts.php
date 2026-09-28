<?php

namespace App\Console\Commands;

use App\Services\CatalogImporter;
use Illuminate\Console\Command;

class ImportProducts extends Command
{
    protected $signature = 'products:import
        {--thumbs : Regenerate thumbnails only, without touching the products table}';

    protected $description = 'Wipe and re-import the full product catalog from the seed JSON';

    public function handle(CatalogImporter $importer): int
    {
        if ($this->option('thumbs')) {
            $importer->generateThumbnails();
            $this->info("Thumbnails checked/generated: {$importer->thumbsGenerated}.");

            return self::SUCCESS;
        }

        $start = microtime(true);
        $importer->run();
        $secs = round(microtime(true) - $start, 1);

        $this->info("Imported {$importer->imported} products ({$importer->featured} featured), {$importer->thumbsGenerated} thumbnails in {$secs}s.");

        return self::SUCCESS;
    }
}