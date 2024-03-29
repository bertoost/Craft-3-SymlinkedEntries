<?php

namespace bertoost\symlinkedentries\traits;

use bertoost\symlinkedentries\Plugin;
use Craft;
use craft\base\Element;
use craft\elements\Entry;
use craft\events\SetElementRouteEvent;
use yii\base\Event;

/**
 * Trait PluginEventsTrait
 */
trait PluginEventsTrait
{
    /**
     * Registers event listener for Symlinked Entries
     */
    public function registerEventListeners(): void
    {
        if (Craft::$app->getRequest()->getIsSiteRequest()) {

            Event::on(
                Entry::class,
                Element::EVENT_SET_ROUTE,
                static function (SetElementRouteEvent $event) {
                    if (null !== $route = Plugin::getInstance()->getSymlinkService()->handleSymlinkedEntry($event->sender)) {
                        $event->route = $route;
                    }
                }
            );
        }
    }
}
