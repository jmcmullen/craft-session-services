<?php

namespace Craft;

class Sessions_RouteController extends BaseController
{
    protected $allowAnonymous = true;

    public function actionIndex()
    {
        $plugin = craft()->plugins->getPlugin('sessions');
        $settings = $plugin->getSettings();

        $state = craft()->request->getParam('state');
        $city = craft()->request->getParam('city');
        $country = craft()->request->getParam('country');
        $page = craft()->request->getParam('page') !== null ? intval(craft()->request->getParam('page')) : 1;
        $activeFilter = $state ?: $city ?: $country;

        $data = $activeFilter ? craft()->sessions_eventsApi->getPlaceEvents($page, $state, $city, $country) : craft()->sessions_eventsApi->getEventsList($page);
        $placeKeys = craft()->sessions_eventsApi->getPlaceKeys();

        $this->renderTemplate($settings->indexTemplate, [
            'events' => $data['events'],
            'pages' => $data['pages'],
            'page' => $page,
            'placeKeys' => $placeKeys,
            'activeFilter' => $activeFilter,
            'apiUrl' => $settings->apiUrl,
            'baseUrl' => $settings->baseUrl,
        ]);
    }

    public function actionEntry()
    {
        $plugin = craft()->plugins->getPlugin('sessions');
        $settings = $plugin->getSettings();
        $slug = craft()->request->getSegments()[1];
        $event = craft()->sessions_eventsApi->getEvent($slug);

        $this->renderTemplate($settings->entryTemplate, [
            'apiUrl' => $settings->apiUrl,
            'baseUrl' => $settings->baseUrl,
            'event' => $event,
        ]);
    }
}
