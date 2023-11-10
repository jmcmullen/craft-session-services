<?php

namespace jmcmullen\sessionservices\services;

use Craft;
use craft\base\Component;
use jmcmullen\sessionservices\SessionServices;
use Craft\services\Api;

class EventsApi extends Component
{
    private ?Api $_client = null;
    private string $_baseUrl = '';

    public function __construct()
    {
        $plugin = SessionServices::getInstance();
        $this->_baseUrl = $plugin->getSettings()->baseUrl;
        $this->_client = Craft::$app->getApi();
    }

    public function getEventsList()
    {
        try {
            $input = $this->encodeURIComponent('{"json":{"filter":"upcoming"}}');
            $url = $this->_baseUrl . '/api/trpc/event.list?input=' . $input;
            $response = $this->_client->request('GET', $url);
            $data = json_decode($response->getBody(), true);
            return $data['result']['data']['json']['events'];
        } catch (\Exception $e) {
            Craft::error('Error fetching events list: ' . $e->getMessage(), __METHOD__);
            return [];
        }
    }

    public function getPlaceEvents(?string $state, ?string $city, ?string $country)
    {
        try {
            $input = $this->encodeURIComponent('{"json":{"city":"' . $city . '","state":"' . $state . '","country":"' . $country . '"}}');
            $url = $this->_baseUrl . '/api/trpc/event.place?input=' . $input;
            $response = $this->_client->request('GET', $url);
            $data = json_decode($response->getBody(), true);
            return $data['result']['data']['json']['events'];
        } catch (\Exception $e) {
            Craft::error('Error fetching events list: ' . $e->getMessage(), __METHOD__);
            return [];
        }
    }

    public function getEvent(string $slug)
    {
        try {
            $input = $this->encodeURIComponent('{"json":{"slug":"' . $slug . '"}}');
            $url = $this->_baseUrl . '/api/trpc/event.retrieve?input=' . $input;
            $response = $this->_client->request('GET', $url);
            $data = json_decode($response->getBody(), true);
            return $data['result']['data']['json']['event'];
        } catch (\Exception $e) {
            Craft::error('Error fetching event: ' . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    public function getPlaceKeys()
    {
        try {
            $url = $this->_baseUrl . '/api/trpc/event.placeKeys';
            $response = $this->_client->request('GET', $url);
            $data = json_decode($response->getBody(), true);
            return $data['result']['data']['json']['places'];
        } catch (\Exception $e) {
            Craft::error('Error fetching place keys: ' . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    private function encodeURIComponent($str)
    {
        $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
        return strtr(rawurlencode($str), $revert);
    }
}
