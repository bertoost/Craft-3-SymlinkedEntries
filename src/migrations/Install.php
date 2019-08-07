<?php

namespace bertoost\symlinkedentries\migrations;

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
        $fieldsService = Craft::$app->getFields();

        $group = new FieldGroup();
        $group->name = 'Symlink';

        if ($fieldsService->saveGroup($group)) {

            $field = $fieldsService->createField([
                'type'         => Entries::class,
                'groupId'      => $group->id,
                'handle'       => 'symlinkTo',
                'name'         => Craft::t('symlinked-entries', 'Symlink to'),
                'instructions' => Craft::t('symlinked-entries', 'Select the entry you want to symlink to'),
                'searchable'   => false,
                'settings'     => [
                    'source'         => '*',
                    'limit'          => 1,
                    'selectionLabel' => Craft::t('symlinked-entries', 'Select entry'),
                ],
            ]);

            if ($fieldsService->saveField($field)) {

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
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $fieldsService = Craft::$app->getFields();

        $field = $fieldsService->getFieldByHandle('symlinkTo');
        if (null !== $field) {
            $fieldsService->deleteField($field);
        }

        $field = $fieldsService->getFieldByHandle('symlinkRedirect');
        if (null !== $field) {
            $fieldsService->deleteField($field);
        }

        // remove symlink group if empty
        foreach ($fieldsService->getAllGroups() as $group) {
            if ($group->name === 'Symlink' && empty($group->getFields())) {
                $fieldsService->deleteGroup($group);
                break;
            }
        }
    }
}
