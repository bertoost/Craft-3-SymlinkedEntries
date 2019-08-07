<?php

namespace bertoost\symlinkedentries\services;

use Craft;
use craft\base\Component;
use craft\elements\Entry;
use craft\errors\SiteNotFoundException;
use yii\base\InvalidConfigException;

/**
 * Class SymlinkService
 */
class SymlinkService extends Component
{
    /**
     * @param Entry $entry
     *
     * @return array|null
     * @throws SiteNotFoundException
     * @throws InvalidConfigException
     */
    public function handleSymlinkedEntry(Entry $entry): ?array
    {
        if (Craft::$app->getRequest()->getIsCpRequest()) {

            return null;
        }

        /** @var Entry $symlinkEntry */
        if (null !== $entry->symlinkTo && null !== ($symlinkEntry = $entry->symlinkTo->one())) {

            if ($entry->symlinkRedirect) {
                Craft::$app->getResponse()->redirect($symlinkEntry->getUrl(), 301);
            }

            // Make sure the section is set to have URLs for this site
            $siteId = Craft::$app->getSites()->getCurrentSite()->id;
            $sectionSiteSettings = $symlinkEntry->getSection()->getSiteSettings();

            if (!isset($sectionSiteSettings[$siteId]) || !$sectionSiteSettings[$siteId]->hasUrls) {

                return null;
            }

            // override the route to render
            return [
                'templates/render', [
                    'template' => $sectionSiteSettings[$siteId]->template,
                    'variables' => [
                        'entry' => $symlinkEntry,
                        'symlinkEntry' => $entry,
                    ]
                ]
            ];
        }

        return null;
    }
}
