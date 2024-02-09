<?php

namespace Craft;

class Sessions_EventsApiService extends BaseApplicationComponent
{
    private $_client = null;
    private $_apiUrl = '';

    public function init()
    {
        $plugin = craft()->plugins->getPlugin('sessions');
        $settings = $plugin->getSettings();
        $this->_apiUrl = $settings->apiUrl;
        $this->_client = new \Guzzle\Http\Client();
    }

    public function getEventsList($page)
    {
        try {
            $input = $this->encodeURIComponent('{"json":{"filter":"upcoming", "page": ' . $page . '}}');
            $url = $this->_apiUrl . '/api/trpc/event.list?input=' . $input;
            $request = $this->_client->get($url);
            $response = $request->send();
            $data = $response->json();
            return $data['result']['data']['json'];
        } catch (\Exception $e) {
            SessionsPlugin::log('Error fetching events list: ' . $e->getMessage(), LogLevel::Error);
            return [];
        }
    }

    public function getPlaceEvents($page, $state, $city, $country)
    {
        try {
            $input = $this->encodeURIComponent('{"json":{"page":' .  $page . ',"city":"' . $city . '","state":"' . $state . '","country":"' . $country . '"}}');
            $url = $this->_apiUrl . '/api/trpc/event.place?input=' . $input;
            $request = $this->_client->get($url);
            $response = $request->send();
            $data = $response->json();
            return $data['result']['data']['json'];
        } catch (\Exception $e) {
            SessionsPlugin::log('Error fetching events list: ' . $e->getMessage(), LogLevel::Error);
            return [];
        }
    }

    public function getEvent($slug)
    {
        try {
            $input = $this->encodeURIComponent('{"json":{"slug":"' . $slug . '"}}');
            $url = $this->_apiUrl . '/api/trpc/event.retrieve?input=' . $input;
            $request = $this->_client->get($url);
            $response = $request->send();
            $data = $response->json();
            return $data['result']['data']['json']['event'];
        } catch (\Exception $e) {
            SessionsPlugin::log('Error fetching event: ' . $e->getMessage(), LogLevel::Error);
            return null;
        }
    }

    public function getPlaceKeys()
    {
        try {
            $url = $this->_apiUrl . '/api/trpc/event.placeKeys';
            $request = $this->_client->get($url);
            $response = $request->send();
            $data = $response->json();
            return $data['result']['data']['json']['places'];
        } catch (\Exception $e) {
            SessionsPlugin::log('Error fetching place keys: ' . $e->getMessage(), LogLevel::Error);
            return null;
        }
    }

    private function encodeURIComponent($str)
    {
        $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
        return strtr(rawurlencode($str), $revert);
    }
}
