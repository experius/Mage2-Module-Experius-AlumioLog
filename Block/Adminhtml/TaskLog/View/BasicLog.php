<?php

namespace Experius\AlumioLog\Block\Adminhtml\TaskLog\View;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Backend\Block\Template;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Experius\AlumioLog\Model\Webservice\Request\RestApi;
use Experius\AlumioLog\Api\TaskLogRepositoryInterface;

class BasicLog extends Template
{

    /**
     * @var TaskLogRepositoryInterface
     */
    protected $taskLogRepository;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var
     */
    protected $data;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * @var DirectoryHelper
     */
    protected $directoryHelper;

    /**
     * @var RestApi
     */
    protected $webservice;

    /**
     * @var
     */
    public $taskInfo;

    /**
     * BasicLog constructor.
     * @param TaskLogRepositoryInterface $taskLogRepository
     * @param RestApi $webservice
     * @param Template\Context $context
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        TaskLogRepositoryInterface $taskLogRepository,
        RestApi $webservice,
        Template\Context $context,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        $this->taskLogRepository = $taskLogRepository;
        $this->webservice = $webservice;
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    /**
     * @return bool|mixed
     * @throws GuzzleException
     * @throws LocalizedException
     */
    public function getTaskInfo()
    {
        if ($entityId = $this->getRequest()->getParam('entity_id')) {
            $taskId = $this->taskLogRepository->get($entityId)->getTasklogId();
            if ($this->taskInfo) {
                return $this->taskInfo;
            } elseif ($task = $this->webservice->call('tasks/' . $taskId)) {
                $this->taskInfo = $task;
                return $this->taskInfo;
            }
        }

        return false;

    }
}
