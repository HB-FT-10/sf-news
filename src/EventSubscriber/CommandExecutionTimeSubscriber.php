<?php

namespace App\EventSubscriber;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\ConsoleEvents;

class CommandExecutionTimeSubscriber implements EventSubscriberInterface
{
    private float $startTime;

    public static function getSubscribedEvents(): array
    {
        return [
            // console.command
            ConsoleEvents::COMMAND => 'onConsoleCommand',
            // console.terminate
            ConsoleEvents::TERMINATE => 'onConsoleTerminate',
        ];
    }

    /**
     * Au démarrage de la commande, on retient le moment précis en secondes
     **/
    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $this->startTime = microtime(true);
    }

    /**
     * À la fin de la commande, on fait la différence entre le moment du démarrage
     * et le moment de la fin : on a le temps écoulé, en secondes
     **/
    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        $executionTime = microtime(true) - $this->startTime;

        $command = $event->getCommand();
        $output = $event->getOutput();

        $output->writeln(sprintf(
            "\n<info>Command \"%s\" executed in %.2f seconds</info>",
            $command->getName(),
            $executionTime
        ));
    }
}
