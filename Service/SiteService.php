<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Service;

use Krystal\Cache\CacheEngineInterface;

/**
 * This service automatically gets injected to templates
 */
final class SiteService
{
    /**
     * Cache engine used to cache fetching calls
     * 
     * @var \Krystal\Cache\CacheEngineInterface
     */
    private $cache;

    /**
     * Image manages which provides slide bags
     * 
     * @var \Slider\Service\ImageManager
     */
    private $imageManager;

    /**
     * State initialization
     * 
     * @param \Slider\Service\ImageManager $imageManager
     * @param \Krystal\Cache\CacheEngineInterface $cache
     * @return void
     */
    public function __construct(ImageManager $imageManager, CacheEngineInterface $cache)
    {
        $this->imageManager = $imageManager;
        $this->cache = $cache;
    }

    /**
     * Returns data and caches for the next call
     * 
     * @param string $class Category's class name
     * @return array|boolean
     */
    private function getData($class)
    {
        if ($this->cache->has($class)) {
            $data = $this->cache->get($class);
        } else {
            $data = $this->imageManager->fetchAllPublishedByCategoryClass($class);
            $this->cache->set($class, $data, 0);
        }

        return $data;
    }

    /**
     * Returns random image entity
     * 
     * @param string $class Category class
     * @return \Slider\Service\ImageEntity
     */
    public function getRandom($class)
    {
        return $this->imageManager->fetchRandomPublishedByCategoryClass($class);
    }

    /**
     * Checks whether provided category's class has at least one slide image
     * 
     * @param string $class
     * @return boolean
     */
    public function has($class)
    {
        return (bool) $this->getAll($class);
    }

    /**
     * Returns slide bags from given category class
     * 
     * @param string $class Category's class name
     * @return array
     */
    public function getAll($class)
    {
        return $this->getData($class);
    }
}
