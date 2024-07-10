<?php
/**
 * Copyright © Experius All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Cron;

use Experius\AlumioLog\Helper\Settings;
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
     * @var Settings
     */
    protected $settingsHelper;

    /**
     * Constructor
     *
     * @param NewLog $newLogImporter
     * @param LoggerInterface $logger
     * @param Settings $settingsHelper
     */
    public function __construct(
        NewLog $newLogImporter,
        LoggerInterface $logger,
        Settings $settingsHelper,
    ) {
        $this->newLogImporter = $newLogImporter;
        $this->logger = $logger;
        $this->settingsHelper = $settingsHelper;
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
        if($this->settingsHelper->getIsEnabled()) {
            $this->newLogImporter->importNewTaskLogs();
        }
    }
}

