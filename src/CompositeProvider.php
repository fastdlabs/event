<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Event;


use Psr\EventDispatcher\ListenerProviderInterface;

class CompositeProvider implements ListenerProviderInterface
{
    /**
     * @var ListenerProviderInterface[]
     */
    private array $providers = [];

    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->providers as $provider) {
            yield from $provider->getListenersForEvent($event);
        }
    }

    /**
     * Adds provider as a source for event listeners
     *
     * @param ListenerProviderInterface $provider
     */
    public function attach(ListenerProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }
}