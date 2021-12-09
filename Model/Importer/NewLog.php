<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\AlumioLog\Model\Importer;

use Experius\AlumioLog\Model\Webservice\Request\RestApi;
use Experius\AlumioLog\Api\TaskLogRepositoryInterface;
use Experius\AlumioLog\Api\Data\TaskLogInterfaceFactory;
use Experius\AlumioLog\Helper\Settings;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class NewLog
 * @package Experius\OculusCustomerPortal\Model\Importer
 */
class NewLog
{

    /**
     * @var SortOrderBuilder
     */
    protected $sortOrderBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var TaskLogInterfaceFactory
     */
    protected $taskLogFactory;

    /**
     * @var TaskLogRepositoryInterface
     */
    protected $taskLogRepository;

    /**
     * @var Settings
     */
    protected $settingsHelper;

    /**
     * @var RestApi
     */
    protected $webservice;

    /**
     * BasicImporter constructor.
     * @param SortOrderBuilder $sortOrderBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Settings $settingsHelper
     * @param TaskLogInterfaceFactory $taskLogInterfaceFactory
     * @param TaskLogRepositoryInterface $taskLogRepository
     * @param RestApi $webservice
     */
    public function __construct(
        SortOrderBuilder $sortOrderBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Settings $settingsHelper,
        TaskLogInterfaceFactory $taskLogInterfaceFactory,
        TaskLogRepositoryInterface $taskLogRepository,
        RestApi $webservice
    ) {
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->taskLogFactory = $taskLogInterfaceFactory;
        $this->settingsHelper = $settingsHelper;
        $this->taskLogRepository = $taskLogRepository;
        $this->webservice = $webservice;
    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws LocalizedException
     */
    public function importNewTaskLogs()
    {
        $postType = "GET";
        $routes = [];
        $url = '/tasks';
        if($this->settingsHelper->getAllowedRoutes()) {
            if($this->settingsHelper->getAllowedRoutes() === 'ALL') {
                $routes[0] = null;
            } else {
                $routes = explode(',', $this->settingsHelper->getAllowedRoutes());
            }
        }

        $lastUpdatedAtTimestamp = $this->getLastUpdatedAtTimestamp();

        foreach($routes as $route) {

            $filterArray = $this->getFilterArray($route, $lastUpdatedAtTimestamp);

            $response = $this->webservice->call($url, 'filter=' . json_encode($filterArray), $postType, 0);

            if (!isset($response['results'])) {
                continue;
            }

            $this->writeNewTaskLogs($response['results']);

        }

    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getLastUpdatedAtTimestamp()
    {
        $sortOrder = $this->sortOrderBuilder
            ->setField('updated_at')
            ->setDescendingDirection()
            ->create();
        $searchCriteria = $this->searchCriteriaBuilder
            ->setSortOrders([$sortOrder])
            ->create();
        $lastTask = $this->taskLogRepository->getList($searchCriteria)->getItems();
        if(isset($lastTask[0])) {
            $lastUpdatedAtTimestamp = $lastTask[0]->getUpdatedAt();
        } else {
            $lastUpdatedAtTimestamp = '2021-01-01 00:00:00';
        }

        return $lastUpdatedAtTimestamp;
    }

    /**
     * @param $route
     * @param $lastUpdatedAtTimestamp
     * @return array
     */
    public function getFilterArray($route, $lastUpdatedAtTimestamp)
    {
        return [
            "limit" => 500,
            "skip" => 0,
            "where" => [
                "updatedAt" => [
                    "gte" => date("Y-m-d\TH:i:s", strtotime($lastUpdatedAtTimestamp)) . '.000Z',
                ],
                "route" => $route,
            ],
            "order" => "updatedAt desc"
        ];
    }

    /**
     * @param $results
     * @throws LocalizedException
     */
    public function writeNewTaskLogs($results)
    {
        foreach($results as $result) {
            $this->writeNewTaskLog($result);
        }
    }

    /**
     * @param $taskLog
     * @throws LocalizedException
     */
    public function writeNewTaskLog($taskLog)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('tasklog_id', $taskLog['identifier'], 'eq')
            ->create();
        $existingTasklog = $this->taskLogRepository->getList($searchCriteria)->getItems();
        if($existingTasklog) {
            $newTaskLog = $existingTasklog[0];
        } else {
            $newTaskLog = $this->taskLogFactory->create();
        }
        $newTaskLog->setTasklogId($taskLog['identifier']);
        $newTaskLog->setEntityType($taskLog['entityType']);
        $newTaskLog->setEntityIdentifier($taskLog['entityIdentifier']);
        $newTaskLog->setRoute($taskLog['route']);
        $newTaskLog->setStatus($taskLog['status']);
        $newTaskLog->setCreatedAt($taskLog['createdAt']);
        $newTaskLog->setUpdatedAt($taskLog['updatedAt']);

        $this->taskLogRepository->save($newTaskLog);
    }

}
