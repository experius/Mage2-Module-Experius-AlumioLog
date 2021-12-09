<?php
/**
 * Copyright © Experius All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Model\Data;

use Experius\AlumioLog\Api\Data\TaskLogInterface;

class TaskLog extends \Magento\Framework\Api\AbstractExtensibleObject implements TaskLogInterface
{

    /**
     * Get entity_id
     * @return string|null
     */
    public function getEntityId()
    {
        return $this->_get(self::ENTITY_ID);
    }

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get tasklog_id
     * @return string|null
     */
    public function getTasklogId()
    {
        return $this->_get(self::TASKLOG_ID);
    }

    /**
     * Set tasklog_id
     * @param string $tasklogId
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setTasklogId($tasklogId)
    {
        return $this->setData(self::TASKLOG_ID, $tasklogId);
    }

    /**
     * Get entity_type
     * @return string|null
     */
    public function getEntityType()
    {
        return $this->_get(self::ENTITY_TYPE);
    }

    /**
     * Set entity_type
     * @param string $entityType
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setEntityType($entityType)
    {
        return $this->setData(self::ENTITY_TYPE, $entityType);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Experius\AlumioLog\Api\Data\TaskLogExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Experius\AlumioLog\Api\Data\TaskLogExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Experius\AlumioLog\Api\Data\TaskLogExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get entity_identifier
     * @return string|null
     */
    public function getEntityIdentifier()
    {
        return $this->_get(self::ENTITY_IDENTIFIER);
    }

    /**
     * Set entity_identifier
     * @param string $entityIdentifier
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setEntityIdentifier($entityIdentifier)
    {
        return $this->setData(self::ENTITY_IDENTIFIER, $entityIdentifier);
    }

    /**
     * Get route
     * @return string|null
     */
    public function getRoute()
    {
        return $this->_get(self::ROUTE);
    }

    /**
     * Set route
     * @param string $route
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setRoute($route)
    {
        return $this->setData(self::ROUTE, $route);
    }

    /**
     * Get status
     * @return string|null
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->_get(self::UPDATED_AT);
    }

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}

