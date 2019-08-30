<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Slider\Controller\Admin;

use Slider\Collection\AttributeTypeCollection;
use Cms\Controller\Admin\AbstractController;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Validate\Pattern;

final class Attribute extends AbstractController
{
    /**
     * Lists category attributes
     * 
     * @param string $id Category id
     * @return string
     */
    public function listAction($id)
    {
        $group = new VirtualEntity();
        $group->setCategoryId($id);

        return $this->createForm($group, $id);
    }

    /**
     * Lists category attributes
     * 
     * @param \Krystal\Stdlib\VirtualEntity $group
     * @param string $id Category id
     * @return string
     */
    private function createForm(VirtualEntity $group, $id)
    {
        $aTypeCol = new AttributeTypeCollection();

        // Configure breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Slider', 'Slider:Admin:Browser@indexAction')
                                       ->addOne($this->translator->translate('Attributes of category "%s"', $this->getModuleService('categoryManager')->fetchById($id)->getName()));

        return $this->view->render('attributes', array(
            'groups' => $this->getModuleService('attributeGroupManager')->fetchAll($id),
            'id' => $id,
            'group' => $group,
            'types' => $aTypeCol->getAll()
        ));
    }

    /**
     * Delete a group by associated category ID
     * 
     * @param string $categoryId
     * @param string $groupId
     * @return string
     */
    public function deleteAction($categoryId, $groupId)
    {
        $service = $this->getModuleService('attributeGroupManager');
        $service->deleteById($groupId);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return '1';
    }

    /**
     * Renders edit form
     * 
     * @param string $categoryId
     * @param string $groupId
     * @return string
     */
    public function editAction($categoryId, $groupId)
    {
        $group = $this->getModuleService('attributeGroupManager')->fetchById($groupId);

        if ($group !== false) {
            return $this->createForm($group, $categoryId);
        } else {
            return false;
        }
    }

    /**
     * Saves an attribute
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('group');

        // Create form validator
        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name()
                )
            )
        ));

        if ($formValidator->isValid()) {
            $service = $this->getModuleService('attributeGroupManager');

            if (!$input['id']) {
                if ($service->add($input)) {
                    $this->flashBag->set('success', 'The element has been updated successfully');
                    return $service->getLastId();
                }

            } else {
                if ($service->update($input)) {
                    $this->flashBag->set('success', 'The element has been updated successfully');
                    return '1';
                }
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
