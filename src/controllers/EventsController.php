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
        $placeKeys = $this->_api->getPlaceKeys();
        $state = $this->request->getParam('state');
        $city = $this->request->getParam('city');
        $country = $this->request->getParam('country');
        $activeFilter = $state ?? $city ?? $country;
        $events = $activeFilter ? $this->_api->getPlaceEvents($state, $city, $country) : $this->_api->getEventsList();

        return $this->renderTemplate('_session-services/events', [
            'baseUrl' => $settings->baseUrl,
            'template' => $settings->template,
            'events' => $events,
            'placeKeys' => $placeKeys,
            'activeFilter' => $activeFilter,
        ]);
    }

    public function actionSlug(string $slug)
    {
        $plugin = SessionServices::getInstance();
        $settings = $plugin->getSettings();
        $event = $this->_api->getEvent($slug);
        $placeKeys = $this->_api->getPlaceKeys();


        return $this->renderTemplate('_session-services/events/[slug].twig', [
            'baseUrl' => $settings->baseUrl,
            'template' => $settings->template,
            'event' => $event,
        ]);
    }
}
