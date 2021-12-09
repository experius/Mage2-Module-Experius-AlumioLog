<?php
/**
 * Copyright © Experius All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Model;

use Experius\AlumioLog\Api\Data\TaskLogInterfaceFactory;
use Experius\AlumioLog\Api\Data\TaskLogSearchResultsInterfaceFactory;
use Experius\AlumioLog\Api\TaskLogRepositoryInterface;
use Experius\AlumioLog\Model\ResourceModel\TaskLog as ResourceTaskLog;
use Experius\AlumioLog\Model\ResourceModel\TaskLog\CollectionFactory as TaskLogCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class TaskLogRepository implements TaskLogRepositoryInterface
{

    protected $dataTaskLogFactory;

    protected $resource;

    protected $extensibleDataObjectConverter;

    protected $searchResultsFactory;

    protected $taskLogFactory;

    private $storeManager;

    protected $dataObjectHelper;

    protected $taskLogCollectionFactory;

    protected $dataObjectProcessor;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;

    /**
     * @param ResourceTaskLog $resource
     * @param TaskLogFactory $taskLogFactory
     * @param TaskLogInterfaceFactory $dataTaskLogFactory
     * @param TaskLogCollectionFactory $taskLogCollectionFactory
     * @param TaskLogSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceTaskLog $resource,
        TaskLogFactory $taskLogFactory,
        TaskLogInterfaceFactory $dataTaskLogFactory,
        TaskLogCollectionFactory $taskLogCollectionFactory,
        TaskLogSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->taskLogFactory = $taskLogFactory;
        $this->taskLogCollectionFactory = $taskLogCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTaskLogFactory = $dataTaskLogFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Experius\AlumioLog\Api\Data\TaskLogInterface $taskLog
    ) {
        /* if (empty($taskLog->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $taskLog->setStoreId($storeId);
        } */

        $taskLogData = $this->extensibleDataObjectConverter->toNestedArray(
            $taskLog,
            [],
            \Experius\AlumioLog\Api\Data\TaskLogInterface::class
        );

        $taskLogModel = $this->taskLogFactory->create()->setData($taskLogData);

        try {
            $this->resource->save($taskLogModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the taskLog: %1',
                $exception->getMessage()
            ));
        }
        return $taskLogModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($entityId)
    {
        $taskLog = $this->taskLogFactory->create();
        $this->resource->load($taskLog, $entityId);
        if (!$taskLog->getId()) {
            throw new NoSuchEntityException(__('TaskLog with id "%1" does not exist.', $entityId));
        }
        return $taskLog->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->taskLogCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Experius\AlumioLog\Api\Data\TaskLogInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Experius\AlumioLog\Api\Data\TaskLogInterface $taskLog
    ) {
        try {
            $taskLogModel = $this->taskLogFactory->create();
            $this->resource->load($taskLogModel, $taskLog->getEntityId());
            $this->resource->delete($taskLogModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the TaskLog: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($entityId)
    {
        return $this->delete($this->get($entityId));
    }
}

