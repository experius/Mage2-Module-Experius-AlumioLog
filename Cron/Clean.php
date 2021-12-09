<?php
/**
 * Copyright © Experius All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Cron;

use Magento\Framework\App\ResourceConnection;

class Clean
{

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * Constructor
     *
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        try {
            $fourWeeksAgo = date('Y-m-d h:i:s', time() - 2419200);

            $connection = $this->resource->getConnection(ResourceConnection::DEFAULT_CONNECTION);
            $tableName = $connection->getTableName('experius_alumiolog_tasklog');
            $deleteQuery = "DELETE FROM " . $tableName . " WHERE created_at < '" . $fourWeeksAgo . "'";
            $connection->query($deleteQuery);

        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        return $this;
    }
}
