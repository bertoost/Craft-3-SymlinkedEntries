<?php

namespace bertoost\symlinkedentries;

use bertoost\symlinkedentries\models\Settings;
use bertoost\symlinkedentries\traits\PluginComponentsTrait;
use bertoost\symlinkedentries\traits\PluginEventsTrait;
use Craft;
use craft\base\Plugin as BasePlugin;

/**
 * Class Plugin
 */
class Plugin extends BasePlugin
{
    use PluginComponentsTrait;
    use PluginEventsTrait;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Craft::setAlias('@bertoost\symlinkedentries', $this->getBasePath());

        parent::init();

        $this->registerComponents();;
        $this->registerEventListeners();
    }

    /**
     * {@inheritDoc}
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }
}
