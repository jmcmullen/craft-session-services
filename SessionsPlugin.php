<?php

namespace Craft;

class SessionsPlugin extends BasePlugin
{
    function getName()
    {
        return Craft::t('Session Services');
    }

    function getVersion()
    {
        return '1.0.0';
    }

    function getDeveloper()
    {
        return 'Session Services';
    }

    function getDeveloperUrl()
    {
        return 'https://session.services';
    }

    function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/jmcmullen/craft-session-services/2.x/releases.json';
    }


    public function registerSiteRoutes()
    {
        $plugin = craft()->plugins->getPlugin('sessions');
        $settings = $plugin->getSettings();
        return array(
            $settings->baseUrl => array('action' => 'sessions/route/index'),
            $settings->baseUrl . '/(?P<slug>.+)' => array('action' => 'sessions/route/entry')
        );
    }

    protected function defineSettings()
    {
        return array(
            'indexTemplate' => array(AttributeType::String, 'default' => 'tickets/index.html'),
            'entryTemplate' => array(AttributeType::String, 'default' => 'tickets/_entry.html'),
            'apiUrl' => array(AttributeType::String, 'default' => 'https://mixmag.asia.session.services'),
            'baseUrl' => array(AttributeType::String, 'default' => 'tickets')
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('sessions/settings', [
            'settings' => $this->getSettings()
        ]);
    }
}
