<?php

namespace Bigecko\Larapp\Composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class PostInstallUpdate
{
    public static function run(Event $event)
    {
        $command = $event->getName();

        if ($event->isDevMode()) {
            if ($command == 'post-install-cmd') {
                static::generateDevFiles($event);
            }

            static::generateIDEHelper($event);
        }
        else if ($command == 'post-install-cmd') {
            static::generateProdFiles($event);
        }
    }

    public static function generateDevFiles(Event $event)
    {
        $dir = getcwd();
        $fs = new Filesystem();

        // 生成 bootstrap/env.php
        $envFile = $dir . '/bootstrap/env.php';
        if (!$fs->exists($envFile)) {
            $event->getIO()->write('生成 bootstrap/env.php');
            $fs->copy($dir . '/bootstrap/env.php.dist', $envFile);
        }

        // 生成 app/config/dev/database.php
        $dbConfigFile = $dir . '/app/config/dev/database.php';
        if (!$fs->exists($dbConfigFile)) {
            $event->getIO()->write('生成 app/config/dev/database.php');
            $fs->copy($dir . '/app/config/dev/database.php.dist', $dbConfigFile);
        }
    }

    public static function generateIDEHelper(Event $event)
    {
        static::cmd($event,
            'php artisan ide-helper:generate',
            'Generating ide helper'
        );

        static::cmd($event,
            'php artisan ide-helper:models --nowrite',
            'Generating ide model helper'
        );
    }

    protected static function cmd(Event $event, $cmd, $description)
    {
        $event->getIO()->write($description);
        $process = new Process($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            \Log::error($process->getErrorOutput());
        }

        $event->getIO()->write($process->getOutput());
    }

    public static function generateProdFiles(Event $event)
    {
        $dir = getcwd();
        $fs = new Filesystem();

        // 生成 app/config/production/database.php
        $dbConfigFile = $dir . '/app/config/production/database.php';
        if (!$fs->exists($dbConfigFile)) {
            $event->getIO()->write('生成 app/config/production/database.php');
            $fs->copy($dir . '/app/config/production/database.php.dist', $dbConfigFile);
        }
    }
}