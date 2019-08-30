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

use Cms\Service\AbstractManager;
use Slider\Storage\CategoryMapperInterface;
use Slider\Storage\AttributeGroupMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;

final class CategoryManager extends AbstractManager
{
    /**
     * Any compliant category mapper 
     *  
     * @var \Slider\Storage\CategoryMapperInterface
     */
    private $categoryMapper;

    /**
     * Any compliant attribute group mapper
     * 
     * @var \Slider\Storage\AttributeGroupMapperInterface
     */
    private $attributeGroupMapper;

    /**
     * State initialization
     * 
     * @param \Slider\Storage\CategoryMapperInterface $categoryMapper
     * @param \Slider\Storage\AttributeGroupMapperInterface $attributeGroupMapper
     * @return void
     */
    public function __construct(CategoryMapperInterface $categoryMapper, AttributeGroupMapperInterface $attributeGroupMapper)
    {
        $this->categoryMapper = $categoryMapper;
        $this->attributeGroupMapper = $attributeGroupMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $category)
    {
        $entity = new VirtualEntity();
        $entity->setId($category['id'], VirtualEntity::FILTER_INT)
               ->setName($category['name'], VirtualEntity::FILTER_HTML)
               ->setClass($category['class'], VirtualEntity::FILTER_HTML)
               ->setWidth($category['width'], VirtualEntity::FILTER_FLOAT)
               ->setHeight($category['height'], VirtualEntity::FILTER_FLOAT)
               ->setQuality($category['quality'], VirtualEntity::FILTER_INT)
               ->setSlidesCount(isset($category['slides_count']) ? $category['slides_count'] : null, VirtualEntity::FILTER_INT);

        return $entity;
    }

    /**
     * Fetches as a list
     * 
     * @return array
     */
    public function fetchList()
    {
        return ArrayUtils::arrayList($this->categoryMapper->fetchList(), 'id', 'name');
    }

    /**
     * Fetch category's entity by associated id
     * 
     * @param string $id Category's id
     * @return array
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->categoryMapper->fetchById($id));
    }

    /**
     * Fetches all category entities
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->prepareResults($this->categoryMapper->fetchAll());
    }

    /**
     * Returns last category id
     * 
     * @return integer
     */
    public function getLastId()
    {
        return $this->categoryMapper->getLastId();
    }

    /**
     * Deletes a category by its associated id
     * 
     * @param string $id Category's id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->categoryMapper->deleteById($id) && $this->attributeGroupMapper->deleteAllByCategoryId($id);
    }

    /**
     * Adds a category
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input)
    {
        return $this->categoryMapper->persist($input);
    }

    /**
     * Updates a category
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        return $this->categoryMapper->persist($input);
    }
}
