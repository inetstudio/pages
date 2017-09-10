<?php

namespace InetStudio\Pages\Commands;

use Illuminate\Console\Command;

class CreateFoldersCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'inetstudio:pages:folders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create package folders';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (config('filesystems.disks.pages')) {
            $path = config('filesystems.disks.pages.root');

            if (! is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }
    }
}
