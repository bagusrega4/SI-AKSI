<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateTables extends Command
{
    protected $signature = 'db:truncate';
    protected $description = 'Kosongkan semua tabel tanpa menghapus strukturnya';

    public function handle()
    {
        Schema::disableForeignKeyConstraints();

        $tables = DB::select('SHOW TABLES');
        $keyName = 'Tables_in_' . DB::getDatabaseName();

        foreach ($tables as $table) {
            if ($table->$keyName !== 'migrations') {
                DB::table($table->$keyName)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();

        $this->info('Semua tabel berhasil dikosongkan.');
    }
}