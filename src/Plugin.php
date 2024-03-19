<?php

declare(strict_types=1);

namespace Toolkit\ComposerPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Composer\Util\ProcessExecutor;

/**
 * Toolkit composer plugin.
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'notifications',
            ScriptEvents::POST_UPDATE_CMD => 'notifications',
        ];
    }

    /**
     * Print the Toolkit notifications.
     */
    public function notifications(Event $event): void
    {
        $binDir = $event->getComposer()->getConfig()->get('bin-dir') ?: 'vendor/bin';
        $runner = "$binDir/run";
        if (!file_exists($runner)) {
            return;
        }
        $output = '';
        (new ProcessExecutor($event->getIO()))
            ->execute("$runner toolkit:notifications", $output);
        if (!empty($output)) {
            $event->getIO()->write($output);
        }
    }

    /**
     * Manually execute the notifications command.
     */
    public static function runNotifications(Event $event): void
    {
        (new static())->notifications($event);
    }

}
