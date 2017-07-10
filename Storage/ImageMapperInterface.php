<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Storage;

interface ImageMapperInterface
{
    /**
     * Fetches image's name by its associated id
     * 
     * @param string $id Image id
     * @return string
     */
    public function fetchNameById($id);

    /**
     * Update settings
     * 
     * @param array $settings
     * @return boolean
     */
    public function updateSettings(array $settings);

    /**
     * Fetches all associated image ids with their associated category id
     * 
     * @param string $categoryId
     * @return array
     */
    public function fetchIdsByCategoryId($categoryId);

    /**
     * Fetches image id by its associated category id
     * 
     * @param string $id Image id
     * @return string
     */
    public function fetchCategoryIdById($id);

    /**
     * Fetches random published slide image associated with provided category id
     * 
     * @param string $categoryId
     * @return array
     */
    public function fetchRandomPublishedByCategoryId($categoryId);

    /**
     * Fetch all images
     * 
     * @param boolean $published Whether to fetch only published records
     * @param string $categoryId Optional category ID filter
     * @param integer $page Current page number
     * @param integer $itemsPerPage Per page count
     * @return array
     */
    public function fetchAll($published, $categoryId, $page, $itemsPerPage);

    /**
     * Fetches an image by its associated id
     * 
     * @param string $id Image id
     * @return array
     */
    public function fetchById($id);
}
