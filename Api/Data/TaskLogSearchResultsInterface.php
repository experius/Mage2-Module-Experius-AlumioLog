<?php
/**
 * Copyright © Experius All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Api\Data;

interface TaskLogSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get TaskLog list.
     * @return \Experius\AlumioLog\Api\Data\TaskLogInterface[]
     */
    public function getItems();

    /**
     * Set entity_type list.
     * @param \Experius\AlumioLog\Api\Data\TaskLogInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

