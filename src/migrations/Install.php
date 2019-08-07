<?php

namespace bertoost\symlinkedentries\migrations;

use bertoost\symlinkedentries\models\Settings;
use bertoost\symlinkedentries\Plugin;
use Craft;
use craft\db\Migration;
use craft\fields\Entries;
use craft\fields\Lightswitch;
use craft\models\FieldGroup;

/**
 * Class Install
 */
class Install extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $pluginSettings = [];
        $fieldsService = Craft::$app->getFields();

        // find if group already exists
        $group = null;
        foreach ($fieldsService->getAllGroups() as $fieldGroup) {
            if ($fieldGroup->name === 'Symlink') {
                $group = $fieldGroup;
                break;
            }
        }

        if (null === $group) {
            $group = new FieldGroup();
            $group->name = 'Symlink';

            $fieldsService->saveGroup($group);
            $pluginSettings['createdGroup'] = true;
        }

        // add-in fields (if not exists)
        $field = $fieldsService->getFieldByHandle('symlinkTo');
        if (null === $field) {
            $field = $fieldsService->createField([
                'type'       => Entries::class,
                'groupId'    => $group->id,
                'handle'     => 'symlinkTo',
                'name'         => Craft::t('symlinked-entries', 'Symlink to'),
                'instructions' => Craft::t('symlinked-entries', 'Select the entry you want to symlink to'),
                'searchable' => false,
                'settings'     => [
                    'source'         => '*',
                    'limit'          => 1,
                    'selectionLabel' => Craft::t('symlinked-entries', 'Select entry'),
                ],
            ]);

            $fieldsService->saveField($field);
            $pluginSettings['createdSymlinkTo'] = true;
        }

        $field = $fieldsService->getFieldByHandle('symlinkRedirect');
        if (null === $field) {
            $field = $fieldsService->createField([
                'type'         => Lightswitch::class,
                'groupId'      => $group->id,
                'handle'       => 'symlinkRedirect',
                'name'         => Craft::t('symlinked-entries', 'Symlink redirect'),
                'instructions' => Craft::t('symlinked-entries', 'Whether or not to rediect to the symlinked entry. Otherwise the content will be used from the symlinked entry.'),
                'searchable'   => false,
                'settings'     => [
                    'default' => '*',
                ],
            ]);

            $fieldsService->saveField($field);
            $pluginSettings['createdSymlinkRedirect'] = true;
        }

        // save some settings
        $plugin = Plugin::getInstance();
        Craft::$app->getPlugins()->savePluginSettings($plugin, $pluginSettings);
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $fieldsService = Craft::$app->getFields();

        /** @var Settings $pluginSettings */
        $pluginSettings = Plugin::getInstance()->getSettings();

        if ($pluginSettings->createdSymlinkTo) {
            $field = $fieldsService->getFieldByHandle('symlinkTo');
            if (null !== $field) {
                $fieldsService->deleteField($field);
            }
        }

        if ($pluginSettings->createdSymlinkRedirect) {
            $field = $fieldsService->getFieldByHandle('symlinkRedirect');
            if (null !== $field) {
                $fieldsService->deleteField($field);
            }
        }

        // remove symlink group if empty
        if ($pluginSettings->createdGroup) {
            foreach ($fieldsService->getAllGroups() as $group) {
                if ($group->name === 'Symlink' && empty($group->getFields())) {
                    $fieldsService->deleteGroup($group);
                    break;
                }
            }
        }
    }
}
