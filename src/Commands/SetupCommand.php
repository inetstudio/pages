<?php

namespace InetStudio\Pages\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'inetstudio:pages:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup pages package';

    /**
     * Commands to call with their description.
     *
     * @var array
     */
    protected $calls = [
        [
            'description' => 'Publish migrations',
            'command' => 'vendor:publish',
            'params' => [
                '--provider' => 'InetStudio\Pages\PagesServiceProvider',
                '--tag' => 'migrations',
            ],
        ],
        [
            'description' => 'Migration',
            'command' => 'migrate',
            'params' => [],
        ],
        [
            'description' => 'Optimize',
            'command' => 'optimize',
            'params' => [],
        ],
        [
            'description' => 'Create folders',
            'command' => 'inetstudio:pages:folders',
            'params' => [],
        ],
        [
            'description' => 'Publish public',
            'command' => 'vendor:publish',
            'params' => [
                '--provider' => 'InetStudio\Pages\PagesServiceProvider',
                '--tag' => 'public',
                '--force' => true,
            ],
        ],
        [
            'description' => 'Publish config',
            'command' => 'vendor:publish',
            'params' => [
                '--provider' => 'InetStudio\Pages\PagesServiceProvider',
                '--tag' => 'config',
            ],
        ],
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        foreach ($this->calls as $info) {
            $this->line(PHP_EOL.$info['description']);
            $this->call($info['command'], $info['params']);
        }
    }
}
