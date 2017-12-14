<?php

namespace InetStudio\Pages\Console\Commands;

use Illuminate\Console\Command;

class CreateFoldersCommand extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:pages:folders';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Create package folders';

    /**
     * Запуск команды.
     *
     * @return void
     */
    public function handle(): void
    {
        if (config('filesystems.disks.pages')) {
            $path = config('filesystems.disks.pages.root');

            if (! is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }
    }
}
