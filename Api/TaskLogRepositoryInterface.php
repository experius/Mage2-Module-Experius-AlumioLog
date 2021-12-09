<?php
/**
 * Copyright © Experius All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface TaskLogRepositoryInterface
{

    /**
     * Save TaskLog
     * @param \Experius\AlumioLog\Api\Data\TaskLogInterface $taskLog
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Experius\AlumioLog\Api\Data\TaskLogInterface $taskLog
    );

    /**
     * Retrieve TaskLog
     * @param string $entityId
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($entityId);

    /**
     * Retrieve TaskLog matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Experius\AlumioLog\Api\Data\TaskLogSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete TaskLog
     * @param \Experius\AlumioLog\Api\Data\TaskLogInterface $taskLog
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Experius\AlumioLog\Api\Data\TaskLogInterface $taskLog
    );

    /**
     * Delete TaskLog by ID
     * @param string $tasklogId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($tasklogId);
}

