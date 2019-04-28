<?php

namespace InetStudio\PagesPackage\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

/**
 * Class SetupCommand.
 */
class SetupCommand extends BaseSetupCommand
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:pages-package:setup';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup pages package';

    /**
     * Инициализация команд.
     */
    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Pages setup',
                'command' => 'inetstudio:pages-package:pages:setup',
            ],
        ];
    }
}
