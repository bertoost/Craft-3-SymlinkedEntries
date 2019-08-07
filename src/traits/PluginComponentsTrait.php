<?php

namespace bertoost\symlinkedentries\traits;

use bertoost\symlinkedentries\services\SymlinkService;

/**
 * Trait PluginComponentsTrait
 */
trait PluginComponentsTrait
{
    public function registerComponents(): void
    {
        $this->setComponents([
            'symlink' => SymlinkService::class,
        ]);
    }

    public function getSymlinkService(): SymlinkService
    {
        return $this->get('symlink');
    }
}