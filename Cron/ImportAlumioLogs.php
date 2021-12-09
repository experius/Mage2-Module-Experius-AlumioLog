<?php
/**
 * Copyright © Experius All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Cron;

use Experius\AlumioLog\Model\Importer\NewLog;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class ImportAlumioLogs
{

    /**
     * @var NewLog
     */
    protected $newLogImporter;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param NewLog $newLogImporter
     * @param LoggerInterface $logger
     */
    public function __construct(
        NewLog $newLogImporter,
        LoggerInterface $logger
    ) {
        $this->newLogImporter = $newLogImporter;
        $this->logger = $logger;
    }

    /**
     * Execute the cron
     *
     * @return void
     * @throws GuzzleException
     * @throws LocalizedException
     */
    public function execute()
    {
        $this->newLogImporter->importNewTaskLogs();
    }
}

