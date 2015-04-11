<?php
namespace WoohooLabs\Harmony;

use WoohooLabs\Harmony\Event\EventDispatcherInterface;
use WoohooLabs\Harmony\Event\SymfonyEventDispatcher;

class EventHarmony extends Harmony
{
    /**
     * @var \WoohooLabs\Harmony\Event\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param \WoohooLabs\Harmony\EventConfig $config
     */
    public function __construct(EventConfig $config)
    {
        $this->config = $config;
    }

    protected function initializeTopComponents()
    {
        if ($this->eventDispatcher === null) {
            $this->eventDispatcher= new SymfonyEventDispatcher($this->container);
        }

        if ($this->config->getEvents() !== null) {
            call_user_func($this->config->getEvents(), $this->eventDispatcher);
        }

        $this->eventDispatcher->dispatchBeforeReceivingRequest();

        parent::initializeTopComponents();

        $this->eventDispatcher->dispatchAfterReceivingRequest($this->request, $this->response);
    }

    protected function discover()
    {
        parent::discover();
        $this->eventDispatcher->dispatchAfterDiscovery($this->request, $this->response);
    }

    protected function route()
    {
        parent::route();
        $this->eventDispatcher->dispatchAfterRouting($this->request, $this->response);
    }

    protected function respond()
    {
        $this->eventDispatcher->dispatchBeforeSendingResponse($this->request, $this->response);
        parent::respond();
    }

    /**
     * @param \WoohooLabs\Harmony\Event\EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
