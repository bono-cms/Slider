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

use Slider\Storage\AttributeGroupMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Cms\Service\AbstractManager;

final class AttributeGroupManager extends AbstractManager implements AttributeGroupManagerInterface
{
    /**
     * Any compliatn attribute group mapper
     * 
     * @var \Slider\Storage\AttributeGroupMapperInterface
     */
    private $attributeGroupMapper;

    /**
     * State initialization
     * 
     * @param \Slider\Storage\AttributeGroupMapperInterface $attributeGroupMapper
     * @return void
     */
    public function __construct(AttributeGroupMapperInterface $attributeGroupMapper)
    {
        $this->attributeGroupMapper = $attributeGroupMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $group = new VirtualEntity();
        $group->setId($row['id'])
              ->setCategoryId($row['category_Id'])
              ->setName($row['name']);

        return $group;
    }

    /**
     * Fetches group entities by its associated ID
     * 
     * @param string $id Group ID
     * @return \Krystal\Stdlib\VirtualEntity
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->attributeGroupMapper->fetchById($id));
    }

    /**
     * Deletes a group by its associated id
     * 
     * @param string $id Group ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->attributeGroupMapper->deleteById($id);
    }

    /**
     * Returns last group ID
     * 
     * @return string
     */
    public function getLastId()
    {
        return $this->attributeGroupMapper->getLastId();
    }

    /**
     * Adds a group
     * 
     * @param array $input
     * @return boolean
     */
    public function add(array $input)
    {
        return $this->attributeGroupMapper->persist($input);
    }

    /**
     * Updates a group
     * 
     * @param array $input
     * @return boolean
     */
    public function update(array $input)
    {
        return $this->attributeGroupMapper->persist($input);
    }

    /**
     * Returns all group entities attached to category
     * 
     * @param string $id Category ID
     * @return array
     */
    public function fetchAll($id)
    {
        return $this->prepareResults($this->attributeGroupMapper->fetchAll($id));
    }
}
