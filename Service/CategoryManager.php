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
use Cms\Service\HistoryManagerInterface;
use Slider\Storage\CategoryMapperInterface;
use Slider\Storage\AttributeGroupMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Security\Filter;

final class CategoryManager extends AbstractManager implements CategoryManagerInterface
{
    /**
     * Any compliant category mapper 
     *  
     * @var \Slider\Storage\CategoryMapperInterface
     */
    private $categoryMapper;

    /**
     * Any compliat attribute group mapper
     * 
     * @var \Slider\Storage\AttributeGroupMapperInterface
     */
    private $attributeGroupMapper;

    /**
     * History manager to keep track
     * 
     * @var \Cms\Service\HistoryManagerInterface
     */
    private $historyManager;

    /**
     * State initialization
     * 
     * @param \Slider\Storage\CategoryMapperInterface $categoryMapper
     * @param \Slider\Storage\AttributeGroupMapperInterface $attributeGroupMapper
     * @param \Cms\Service\HistoryManagerInterface $historyManager
     * @return void
     */
    public function __construct(CategoryMapperInterface $categoryMapper, AttributeGroupMapperInterface $attributeGroupMapper, HistoryManagerInterface $historyManager)
    {
        $this->categoryMapper = $categoryMapper;
        $this->attributeGroupMapper = $attributeGroupMapper;
        $this->historyManager = $historyManager;
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
     * Tracks activity
     * 
     * @param string $message
     * @param string $placeholder
     * @return boolean
     */
    private function track($message, $placeholder)
    {
        return $this->historyManager->write('Slider', $message, $placeholder);
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
     * {@inheritDoc}
     */
    protected function toEntity(array $category)
    {
        $entity = new VirtualEntity();
        $entity->setId($category['id'], VirtualEntity::FILTER_INT)
            ->setName($category['name'], VirtualEntity::FILTER_HTML)
            ->setClass($category['class'], VirtualEntity::FILTER_HTML)
            ->setWidth($category['width'], VirtualEntity::FILTER_FLOAT)
            ->setHeight($category['height'], VirtualEntity::FILTER_FLOAT);

        return $entity;
    }

    /**
     * Fetches category name by its associated ID
     * 
     * @param string $id Category ID
     * @return string
     */
    public function fetchNameById($id)
    {
        return Filter::escape($this->categoryMapper->fetchNameById($id));
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
     * Deletes a category by its associated id
     * 
     * @param string $id Category's id
     * @return boolean
     */ 
    public function deleteById($id)
    {
        $name = $this->fetchNameById($id);

        if ($this->categoryMapper->deleteById($id) && $this->attributeGroupMapper->deleteAllByCategoryId($id)) {
            $this->track('Category "%s" has been removed', $name);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Adds a category
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input)
    {
        $this->track('Category "%s" has been added', $input['name']);
        return $this->categoryMapper->insert($input);
    }

    /**
     * Updates a category
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        $this->track('Category "%s" has been updated', $input['name']);
        return $this->categoryMapper->update($input);
    }
}
