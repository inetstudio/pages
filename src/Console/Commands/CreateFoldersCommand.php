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
        $folders = [
            'pages',
        ];

        foreach ($folders as $folder) {
            if (config('filesystems.disks.'.$folder)) {
                $path = config('filesystems.disks.'.$folder.'.root');
                $this->createDir($path);
            }
        }
    }

    /**
     * Создание директории.
     *
     * @param $path
     */
    private function createDir($path): void
    {
        if (! is_dir($path)) {
            mkdir($path, 0777, true);
            $this->info($path.' Has been created.');
        } else {
            $this->info($path.' Already created.');
        }
    }
}
