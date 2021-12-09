<?php
/**
 * Copyright © Experius All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Api\Data;

interface TaskLogInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const ENTITY_ID = 'entity_id';
    const TASKLOG_ID = 'tasklog_id';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const ENTITY_IDENTIFIER = 'entity_identifier';
    const ENTITY_TYPE = 'entity_type';
    const ROUTE = 'route';
    const UPDATED_AT = 'updated_at';

    /**
     * Get entity id
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setEntityId($entityId);

    /**
     * Get tasklog_id
     * @return string|null
     */
    public function getTasklogId();

    /**
     * Set tasklog_id
     * @param string $tasklogId
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setTasklogId($tasklogId);

    /**
     * Get entity_type
     * @return string|null
     */
    public function getEntityType();

    /**
     * Set entity_type
     * @param string $entityType
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setEntityType($entityType);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Experius\AlumioLog\Api\Data\TaskLogExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Experius\AlumioLog\Api\Data\TaskLogExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Experius\AlumioLog\Api\Data\TaskLogExtensionInterface $extensionAttributes
    );

    /**
     * Get entity_identifier
     * @return string|null
     */
    public function getEntityIdentifier();

    /**
     * Set entity_identifier
     * @param string $entityIdentifier
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setEntityIdentifier($entityIdentifier);

    /**
     * Get route
     * @return string|null
     */
    public function getRoute();

    /**
     * Set route
     * @param string $route
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setRoute($route);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setStatus($status);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setUpdatedAt($updatedAt);
}

