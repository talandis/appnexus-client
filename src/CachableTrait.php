<?php

namespace Audiens\AppnexusClient;

trait CachableTrait
{

    /** @var bool */
    protected $cacheEnabled;

    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    public function disableCache()
    {
        $this->cacheEnabled = false;
    }

    public function enableCache()
    {
        $this->cacheEnabled = true;
    }
}
