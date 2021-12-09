<?php


namespace Experius\AlumioLog\Model\Message;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Experius\AlumioLog\Api\TaskLogRepositoryInterface;

/**
 * Class LogErrors
 * @package Experius\AlumioLog\Model\Message
 */
class LogErrors implements \Magento\Framework\Notification\MessageInterface
{

    /**
     * @var TaskLogRepositoryInterface
     */
    protected $taskLogRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @param TaskLogRepositoryInterface $taskLogRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param UrlInterface $urlBuilder
     * @param DateTime $dateTime
     */
    public function __construct(
        TaskLogRepositoryInterface $taskLogRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        UrlInterface $urlBuilder,
        DateTime $dateTime
    ) {
        $this->taskLogRepository = $taskLogRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->urlBuilder = $urlBuilder;
        $this->dateTime = $dateTime;
    }

    /**
     * Check whether all indices are valid or not
     *
     * @return bool
     * @throws LocalizedException
     */
    public function isDisplayed()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('created_at', $this->dateTime->gmtDate(null, strtotime('-7 days') - 60 * 30), 'gteq')
            ->addFilter('status', 'failed', 'eq')
            ->create();
        $count = $this->taskLogRepository
            ->getList($searchCriteria)
            ->getTotalCount();
        return $count;
    }

    //@codeCoverageIgnoreStart

    /**
     * Retrieve unique message identity
     *
     * @return string
     */
    public function getIdentity()
    {
        // phpcs:ignore
        return md5('ALUMIOLOG_INVALID');
    }

    /**
     * Retrieve message text
     *
     * @return Phrase
     */
    public function getText()
    {
        $url = $this->urlBuilder->getUrl('experius_alumiolog/tasklog/index');
        //@codingStandardsIgnoreStart
        return __(
            '<a href="%1">Alumio Logs</a> - at least one error in the last week!',
            $url
        );
        //@codingStandardsIgnoreEnd
    }

    /**
     * Retrieve message severity
     *
     * @return int
     */
    public function getSeverity()
    {
        return self::SEVERITY_CRITICAL;
    }

    //@codeCoverageIgnoreEnd
}
