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

interface AttributeGroupManagerInterface
{
    /**
     * Fetches group entities by its associated ID
     * 
     * @param string $id Group ID
     * @return \Krystal\Stdlib\VirtualEntity
     */
    public function fetchById($id);

    /**
     * Deletes a group by its associated id
     * 
     * @param string $id Group ID
     * @return boolean
     */
    public function deleteById($id);
    
    /**
     * Returns last group ID
     * 
     * @return string
     */
    public function getLastId();

    /**
     * Adds a group
     * 
     * @param array $input
     * @return boolean
     */
    public function add(array $input);

    /**
     * Updates a group
     * 
     * @param array $input
     * @return boolean
     */
    public function update(array $input);

    /**
     * Returns all group entities attached to category
     * 
     * @param string $id Category ID
     * @return array
     */
    public function fetchAll($id);
}
