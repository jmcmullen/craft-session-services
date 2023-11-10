<?php

namespace jmcmullen\sessionservices\controllers;

use craft\web\Controller;
use jmcmullen\sessionservices\services\EventsApi;
use jmcmullen\sessionservices\SessionServices;

class EventsController extends Controller
{
    protected array|bool|int $allowAnonymous = true;
    private EventsApi $_api;


    public function __construct($id, $module, EventsApi $api, $config = [])
    {
        $this->_api = $api;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $plugin = SessionServices::getInstance();
        $settings = $plugin->getSettings();
        $events = $this->_api->getEventsList();

        return $this->renderTemplate('_session-services/events', [
            'baseUrl' => $settings->baseUrl,
            'template' => $settings->template,
            'events' => $events,
        ]);
    }

    public function actionSlug(string $slug)
    {
        $plugin = SessionServices::getInstance();
        $settings = $plugin->getSettings();
        $api = new EventsApi();
        $event = $this->_api->getEvent($slug);

        return $this->renderTemplate('_session-services/events/[slug].twig', [
            'event' => $event,
            'baseUrl' => $settings->baseUrl,
            'template' => $settings->template,
        ]);
    }
}
