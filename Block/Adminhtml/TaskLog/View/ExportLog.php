<?php

namespace Experius\AlumioLog\Block\Adminhtml\TaskLog\View;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Backend\Block\Template;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Experius\AlumioLog\Model\Webservice\Request\RestApi;
use Experius\AlumioLog\Block\Adminhtml\TaskLog\View\BasicLog;

class ExportLog extends Template
{

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var BasicLog
     */
    protected $basicLog;

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
    public $exportInfo;

    /**
     * BasicLog constructor.
     * @param BasicLog
     * @param RestApi $webservice
     * @param Template\Context $context
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        BasicLog $basicLog,
        RestApi $webservice,
        Template\Context $context,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        $this->basicLog = $basicLog;
        $this->webservice = $webservice;
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    /**
     * @return array
     * @throws GuzzleException
     * @throws LocalizedException
     */
    public function getLogs()
    {
        $exportLogArray = [];
        try {
            foreach($this->getExportInfo() as $exportLog) {
                $exportLogArray[] = [
                    'message' => $exportLog['_source']['@message'],
                    'status' => $exportLog['_source']['@fields']['level'],
                    'timestamp' => $exportLog['_source']['@timestamp']
                ];
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return $exportLogArray;
    }

    /**
     * @return false|mixed
     * @throws GuzzleException
     * @throws LocalizedException
     */
    public function getExportInfo()
    {
        if($this->exportInfo) {
            return $this->exportInfo;
        } else {
            if ($taskInfo = $this->basicLog->getTaskInfo()) {
                $postArray = $this->getPostArray($taskInfo['object']['context']['scope']);
                if ($exportInfo = $this->webservice->call('/elasticsearch/search/result?index=filebeat-%2A', $postArray, 'POST')) {
                    $this->exportInfo = $exportInfo['object']['hits']['hits'];
                    return $this->exportInfo;
                }
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'export';
    }

    /**
     * @param $dataFields
     * @return array
     */
    public function getPostArray($dataFields)
    {
        return [
            "_source" => [
                "@timestamp",
                "@fields",
                "@source",
                "@tags",
                "@type",
                "@message"
            ],
            "query" => [
                "bool" => [
                    "must" => [
                        [
                            "bool" => [
                                "must" => [
                                    [
                                        "match" => [
                                            "@fields.scope.task" => $dataFields['task']
                                        ]
                                    ],
                                    [
                                        "bool" => [
                                            "should" => [
                                                [
                                                    "exists" => [
                                                        "field" => "@fields.scope.export"
                                                    ]
                                                ],
                                                [
                                                    "exists" => [
                                                        "field" => "@fields.scope.publish"
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "range" => [
                                "@fields.level" => [
                                    "gte" => 200
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "sort" => [
                [
                    "@timestamp" => [
                        "order" => "asc"
                    ],
                    "@fields.identifier" => [
                        "order" => "asc"
                    ]
                ]
            ],
            "size" => 100
        ];
    }

}
