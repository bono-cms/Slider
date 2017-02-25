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

interface CategoryManagerInterface
{
    /**
     * Fetches as a list
     * 
     * @return array
     */
    public function fetchList();

    /**
     * Returns last category id
     * 
     * @return integer
     */
    public function getLastId();

    /**
     * Fetches category name by its associated ID
     * 
     * @param string $id Category ID
     * @return string
     */
    public function fetchNameById($id);

    /**
     * Fetch a category bag by associated id
     * 
     * @param string $id
     * @return array
     */
    public function fetchById($id);

    /**
     * Fetches all category bags
     * 
     * @return array
     */
    public function fetchAll();

    /**
     * Deletes a category by its associated id
     * 
     * @param string $id
     * @return boolean
     */ 
    public function deleteById($id);

    /**
     * Adds a category
     * 
     * @param array $data Form data
     * @return boolean
     */
    public function add(array $data);

    /**
     * Updates a category
     * 
     * @param array $data Form data
     * @return boolean
     */
    public function update(array $data);
}
