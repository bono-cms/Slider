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

use Krystal\Security\Filter;
use Krystal\Stdlib\ArrayUtils;
use Cms\Service\AbstractManager;
use Cms\Service\HistoryManagerInterface;
use Slider\Storage\ImageMapperInterface;
use Slider\Storage\CategoryMapperInterface;
use Slider\Storage\AttributeValueMapperInterface;
use Slider\Service\ImageManagerFactory;

final class ImageManager extends AbstractManager implements ImageManagerInterface
{
    /**
     * Any-compliant image mapper
     * 
     * @var \Slider\Storage\ImageMapperInterface
     */
    private $imageMapper;

    /**
     * Any-compliant category mapper
     *
     * @var \Slider\Storage\CategoryMapperInterface
     */
    private $categoryMapper;

    /**
     * Any compliant attribute value mapper
     * 
     * @var \Slider\Storage\AttributeValueMapperInterface
     */
    private $attributeValueMapper;

    /**
     * Image manager to deal with images
     * 
     * @var \Krystal\Image\Tool\ImageManagerInterface
     */
    private $imageManager;

    /**
     * Factory which can be build image manager with different dimensions
     * 
     * @var \Slider\Service\ImageManagerFactory
     */
    private $imageManagerFactory;

    /**
     * History manager to keep track
     * 
     * @var \Cms\Service\HistoryManager
     */
    private $historyManager;

    /**
     * State initialization
     * 
     * @param \Slider\Storage\ImageMapperInterface $imageMapper
     * @param \Slider\Storage\CategoryMapperInterface $categoryMapper
     * @param \Slider\Storage\AttributeValueMapperInterface $attributeValueMapper
     * @param \Slider\Service\Factories\ImageManagerFactory $imageManagerFactory
     * @param \Cms\Service\HistoryManagerInterface $historyManager
     * @return void
     */
    public function __construct(
        ImageMapperInterface $imageMapper, 
        CategoryMapperInterface $categoryMapper, 
        AttributeValueMapperInterface $attributeValueMapper,
        ImageManagerFactory $imageManagerFactory, 
        HistoryManagerInterface $historyManager
    ){
        $this->imageMapper = $imageMapper;
        $this->categoryMapper = $categoryMapper;
        $this->attributeValueMapper = $attributeValueMapper;
        $this->imageManagerFactory = $imageManagerFactory;
        $this->historyManager = $historyManager;
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
     * Update settings
     * 
     * @param array $settings
     * @return boolean
     */
    public function updateSettings(array $settings)
    {
        return $this->imageMapper->updateSettings($settings);
    }

    /**
     * Returns prepared paginator instance
     * 
     * @return \Krystal\Paginate\Paginator
     */
    public function getPaginator()
    {
        return $this->imageMapper->getPaginator();
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $image, $withAttributes = true)
    {
        $imageBag = clone $this->getUploader($image['category_id'])->getImageBag();
        $imageBag->setId($image['id'])
                 ->setCover($image['image']);

        $entity = new ImageEntity(false);
        $entity->setImageBag($imageBag)
            ->setId($image['id'], ImageEntity::FILTER_INT)
            ->setLangId($image['lang_id'], ImageEntity::FILTER_INT)
            ->setCategoryId($image['category_id'], ImageEntity::FILTER_INT)
            ->setCategoryName(isset($image['category_name']) ? $image['category_name'] : null, ImageEntity::FILTER_HTML)
            ->setName($image['name'], ImageEntity::FILTER_HTML)
            ->setDescription($image['description'], ImageEntity::FILTER_HTML)
            ->setOrder($image['order'], ImageEntity::FILTER_INT)
            ->setPublished($image['published'], ImageEntity::FILTER_BOOL)
            ->setLink($image['link'], ImageEntity::FILTER_HTML)
            ->setCover($image['image'], ImageEntity::FILTER_HTML);
        
        if ($withAttributes !== false) {
            $entity->setAttributes($this->attributeValueMapper->fetchAll($entity->getId()));
        }

        return $entity;
    }

    /**
     * Fetch all image entities
     * 
     * @param string $categoryId Optional category ID
     * @param integer $page Page number
     * @param integer $itemsPerPage Per page count
     * @return array
     */
    public function fetchAll($categoryId, $page, $itemsPerPage)
    {
        $images = $this->imageMapper->fetchAll(false, $categoryId, $page, $itemsPerPage);
        return $this->prepareResults($images, false);
    }

    /**
     * Fetches all published slider bags by category id
     * 
     * @param string $id Category id
     * @return array
     */
    public function fetchAllPublishedByCategoryId($id)
    {
        return $this->prepareResults($this->imageMapper->fetchAll(true, $id, null, null));
    }

    /**
     * Fetches random published image by category class
     * 
     * @param string $class Category class
     * @return array
     */
    public function fetchRandomPublishedByCategoryClass($class)
    {
        // Get associated id, first
        $id = $this->categoryMapper->fetchIdByClass($class);
        return $this->prepareResult($this->imageMapper->fetchRandomPublishedByCategoryId($id));
    }

    /**
     * Fetches all published slide images in provided category class
     * 
     * @param string $class Category class
     * @return array
     */
    public function fetchAllPublishedByCategoryClass($class)
    {
        // Get associated id, first
        $id = $this->categoryMapper->fetchIdByClass($class);
        return $this->fetchAllPublishedByCategoryId($id);
    }

    /**
     * Fetches a record by its associated id
     * 
     * @param string $id
     * @return array
     */
    public function fetchById($id)
    {
        return $this->prepareResults($this->imageMapper->fetchById($id));
    }

    /**
     * Returns last id
     * 
     * @return integer
     */
    public function getLastId()
    {
        return $this->imageMapper->getLastId();
    }

    /**
     * Deletes an image
     * Wrapped into this method to avoid duplication
     * 
     * @param string $id Image id
     * @param string $categoryId Can be provided to omit a look up
     * @return boolean
     */
    private function delete($id, $categoryId = null)
    {
        if (is_null($categoryId)) {
            // Grab a category id for uploader instance
            $categoryId = $this->imageMapper->fetchCategoryIdById($id);
        }

        if ($categoryId) {
            if ($this->imageMapper->deleteEntity($id) && $this->attributeValueMapper->deleteAllByImageId($id) && $this->getUploader($categoryId)->delete($id)) {
                return true;
            } else {
                // Failed to remove a file
                return false;
            }
        } else {
            // Invalid id supplied
            return false;
        }
    }

    /**
     * Removes all images associated with given category id
     * 
     * @param string $categoryId
     * @return boolean
     */
    public function deleteAllByCategoryId($categoryId)
    {
        // Grab all ids associated with provided category id
        $ids = $this->imageMapper->fetchIdsByCategoryId($categoryId);

        // Start doing it when at least one id is present
        if (!empty($ids)) {
            foreach ($ids as $id) {
                if (!$this->delete($id, $categoryId)) {
                    return false;
                }
            }
        }

        // @TODO: Now, it's time to remove records themselves
        //$this->imageMapper->deleteAllByCategoryId($categoryId);
        return true;
    }

    /**
     * Deletes an image by its associated id
     * 
     * @param string $id Image's id
     * @return boolean
     */
    public function deleteById($id)
    {
        #$name = Filter::escape($this->imageMapper->fetchNameById($id));

        if ($this->delete($id)) {
            #$this->track('Slider "%s" has been removed', $name);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete bunch of images by their ids
     * 
     * @param array $ids
     * @return boolean
     */
    public function deleteByIds(array $ids)
    {
        foreach ($ids as $id) {
            if (!$this->delete($id)) {
                return false;
            }
        }

        #$this->track('Batch removal of "%s" slides', count($ids));
        return true;
    }

    /**
     * Builds uploader instance for a category
     * 
     * @param string $id Category id
     * @return \Krystal\Image\Tool\ImageManager
     */
    private function getUploader($id)
    {
        if (is_null($this->imageManager)) {
            // Grab dimensions
            $category = $this->categoryMapper->fetchById($id);

            if (!empty($category)) {
                // Define dimensions for this category
                $this->imageManagerFactory->setWidth($category['width'])
                                          ->setHeight($category['height'])
                                          ->setQuality($category['quality']);
            }

            $this->imageManager = $this->imageManagerFactory->build();
        }

        return $this->imageManager;
    }

    /**
     * Prepares a container before sending to a mapper
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    private function prepareInput(array $input)
    {
        // Just a reference
        $data =& $input['data']['image'];
        $file =& $input['files']['file'];

        // Safe type casting
        $data['order'] = (int) $data['order'];

        $this->filterFileInput($file);
        return $input;
    }

    /**
     * Adds a slider
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input)
    {
        if (!empty($input['files'])) {
            $input = $this->prepareInput($input);

            $file =& $input['files']['file'];
            $data =& $input['data']['image'];
            $translations = $input['data']['translation'];

            $data['image'] = $file[0]->getName();

            // Now save the entity
            $this->imageMapper->saveEntity($data, $translations);
            
            #$this->track('Slider "%s" has been uploaded', $data['name']);

            $uploader = $this->getUploader($data['category_id']);
            return $uploader->upload($this->getLastId(), $file);
        }
    }

    /**
     * Update attributes
     * 
     * @param array $image
     * @return void
     */
    private function updateAttributes(array $image)
    {
        // Do only if there are category attribute
        if (isset($image['attributes'])) {
            // Remove all previous meta data if any
            $this->attributeValueMapper->deleteAllByImageId($image['id']);

            // Insert new meta data
            foreach ($image['attributes'] as $groupId => $value) {
                $this->attributeValueMapper->persist(array(
                    'group_id' => $groupId,
                    'image_id' => $image['id'],
                    'value' => $value
                ));
            }
        }
    }

    /**
     * Updates a slider
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        $data =& $input['data']['image'];
        $translations =& $input['data']['translation'];

        // Handle image
        if (!empty($input['files'])) {
            $uploader = $this->getUploader($data['category_id']);

            // First of all, we need to remove old one
            if ($uploader->delete($data['id'], $data['image'])) {
                $input = $this->prepareInput($input);

                $file = $input['files']['file'];

                // Now override old image with a new one and start uploading
                $data['image'] = $file[0]->getName();
                $uploader->upload($data['id'], $file);
            } else {
                return false;
            }
        }

        #$this->track('Slider "%s" has been updated', $data['name']);
        $this->updateAttributes($data);

        return $this->imageMapper->saveEntity(ArrayUtils::arrayWithout($data, array('attributes')), $translations);
    }
}
