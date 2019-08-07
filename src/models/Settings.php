<?php

namespace bertoost\symlinkedentries\models;

use craft\base\Model;

/**
 * Class Settings
 */
class Settings extends Model
{
    /**
     * @var bool Whether or not the group is created by the plugin
     */
    public $createdGroup = false;

    /**
     * @var bool Whether or not the symlinkTo field is created by the plugin
     */
    public $createdSymlinkTo = false;

    /**
     * @var bool Whether or not the symlinkRedirect field is created by the plugin
     */
    public $createdSymlinkRedirect = false;

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['createdGroup', 'createdSymlinkTo', 'createdSymlinkRedirect'], 'boolean'],
        ];
    }
}
