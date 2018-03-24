<?php
/**
 * Contains the ConfiguresBackend trait.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-24
 *
 */

namespace Konekt\Gears\Tests;

use Illuminate\Contracts\Cache\Repository;
use Konekt\Gears\Backend\Drivers\CachedDatabase;
use Konekt\Gears\Backend\Drivers\Database;
use ReflectionMethod;

trait ConfiguresBackend
{
    /**
     * @inheritdoc
     */
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('gears.driver', self::DRIVER);
    }

    /**
     * Returns the cache key for a given setting key.
     *
     * @param $key
     * @return mixed
     * @throws \ReflectionException
     */
    private function getSettingCacheKey($key)
    {
        $cachedDbBackend = new CachedDatabase(
            $this->app->make(Repository::class),
            new Database()
        );

        $skey = new ReflectionMethod(CachedDatabase::class, 'skey');
        $skey->setAccessible(true);

        return $skey->invoke($cachedDbBackend, $key);
    }
}
