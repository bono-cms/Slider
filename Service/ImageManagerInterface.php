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

interface ImageManagerInterface
{
    /**
     * Update settings
     * 
     * @param array $settings
     * @return boolean
     */
    public function updateSettings(array $settings);

    /**
     * Returns prepared paginator instance
     * 
     * @return \Krystal\Paginate\Paginator
     */
    public function getPaginator();

    /**
     * Fetch all image entities
     * 
     * @param string $categoryId Optional category ID
     * @param integer $page Page number
     * @param integer $itemsPerPage Per page count
     * @return array
     */
    public function fetchAll($categoryId, $page, $itemsPerPage);

    /**
     * Fetches all published slider bags by category id
     * 
     * @param string $id Category id
     * @return array
     */
    public function fetchAllPublishedByCategoryId($id);

    /**
     * Fetches random published image by category class
     * 
     * @param string $class Category class
     * @return array
     */
    public function fetchRandomPublishedByCategoryClass($class);

    /**
     * Fetches all published slide images in provided category class
     * 
     * @param string $class Category class
     * @return array
     */
    public function fetchAllPublishedByCategoryClass($class);

    /**
     * Fetches a record by its associated id
     * 
     * @param string $id
     * @return array
     */
    public function fetchById($id);

    /**
     * Returns last id
     * 
     * @return integer
     */
    public function getLastId();

    /**
     * Removes all images associated with given category id
     * 
     * @param string $categoryId
     * @return boolean
     */
    public function deleteAllByCategoryId($categoryId);

    /**
     * Deletes an image by its associated id
     * 
     * @param string $id Image's id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * Delete bunch of images by their ids
     * 
     * @param array $ids
     * @return boolean
     */
    public function deleteByIds(array $ids);

    /**
     * Adds a slider
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input);

    /**
     * Updates a slider
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input);
}
