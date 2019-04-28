<?php

namespace InetStudio\PagesPackage\Pages\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class CreateFoldersCommand.
 */
class CreateFoldersCommand extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:pages-package:pages:folders';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Create package folders';

    /**
     * Запуск команды.
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
    protected function createDir($path): void
    {
        if (is_dir($path)) {
            $this->info($path.' Already created.');

            return;
        }

        mkdir($path, 0777, true);
        $this->info($path.' Has been created.');
    }
}
