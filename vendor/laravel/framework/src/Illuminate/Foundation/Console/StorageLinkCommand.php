<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\Command;

class StorageLinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from "public/storage" to "storage/app/public"';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (file_exists(public_path('storage')) && file_exists(public_path('img'))) {
            return $this->error('The "app/public" and the "app/img" directory already exists.');
        }

        $this->laravel->make('files')->link(
            storage_path('app/public'), public_path('storage')
        );

        $this->laravel->make('files')->link(
            storage_path('app/img'), public_path('img')
        );

        $this->info('The [public/storage] and The [app/img] directory has been linked.');
    }
}
