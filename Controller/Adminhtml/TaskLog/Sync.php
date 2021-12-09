<?php
/**
 * Copyright © Experius All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Controller\Adminhtml\TaskLog;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Backend\App\Action\Context;
use Experius\AlumioLog\Model\Importer\NewLog;

class Sync extends Action
{

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var NewLog
     */
    private $newLog;


    /**
     * Sync constructor.
     * @param Context $context
     * @param ManagerInterface $messageManager
     * @param RedirectFactory $redirectFactory
     * @param NewLog $newLog
     */
    public function __construct(
        Context $context,
        ManagerInterface $messageManager,
        RedirectFactory $redirectFactory,
        NewLog $newLog
    ) {
        $this->newLog = $newLog;
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     * @throws GuzzleException|LocalizedException
     */
    public function execute()
    {
        try {
            $this->newLog->importNewTaskLogs();
            $this->messageManager->addSuccessMessage('Logs were retrieved successfully');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage("Sync did not work: {$e}");
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }

}

