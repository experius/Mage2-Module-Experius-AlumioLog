<?php
/**
 * Copyright © Experius All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Model;

use Experius\AlumioLog\Api\Data\TaskLogInterface;
use Experius\AlumioLog\Api\Data\TaskLogInterfaceFactory;
use Experius\AlumioLog\Model\ResourceModel\TaskLog\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class TaskLog extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $_eventPrefix = 'experius_alumiolog_tasklog';

    protected $tasklogDataFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param TaskLogInterfaceFactory $tasklogDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ResourceModel\TaskLog $resource
     * @param Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        TaskLogInterfaceFactory $tasklogDataFactory,
        DataObjectHelper $dataObjectHelper,
        ResourceModel\TaskLog $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        $this->tasklogDataFactory = $tasklogDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve tasklog model with tasklog data
     * @return TaskLogInterface
     */
    public function getDataModel()
    {
        $tasklogData = $this->getData();

        $tasklogDataObject = $this->tasklogDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $tasklogDataObject,
            $tasklogData,
            TaskLogInterface::class
        );

        return $tasklogDataObject;
    }

}

