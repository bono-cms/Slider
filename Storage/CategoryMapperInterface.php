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

interface CategoryMapperInterface
{
    /**
     * Fetches a list
     * 
     * @return array
     */
    public function fetchList();

    /**
     * Fetches category's id by its associated class name
     * 
     * @param string $class Category's class name
     * @return string
     */
    public function fetchIdByClass($class);

    /**
     * Fetches category's name by its associated id
     * 
     * @param string $id Category id
     * @return string
     */
    public function fetchNameById($id);

    /**
     * Deletes a category by its associated id
     * 
     * @param string $id Category id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * Fetches category data by its associated id
     * 
     * @param string $id Category id
     * @return array
     */
    public function fetchById($id);

    /**
     * Fetches all categories
     * 
     * @return array
     */
    public function fetchAll();
}
