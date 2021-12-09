<?php

namespace Experius\AlumioLog\Block\Adminhtml\TaskLog\View;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Backend\Block\Template;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Experius\AlumioLog\Model\Webservice\Request\RestApi;
use Experius\AlumioLog\Block\Adminhtml\TaskLog\View\BasicLog;

class ImportLog extends Template
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
    public $importInfo;

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
    )
    {
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
        $importLogArray = [];
        try {
            foreach ($this->getImportInfo() as $importLog) {
                $importLogArray[] = [
                    'message' => $importLog['_source']['@message'],
                    'status' => $importLog['_source']['@fields']['level'],
                    'timestamp' => $importLog['_source']['@timestamp']
                ];
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return $importLogArray;
    }

    /**
     * @return false|mixed
     * @throws GuzzleException
     * @throws LocalizedException
     */
    public function getImportInfo()
    {
        if ($this->importInfo) {
            return $this->importInfo;
        } else {
            if ($taskInfo = $this->basicLog->getTaskInfo()) {
                // Added support for Webhooks, since these have a different way of getting the Import Logs
                if (isset($taskInfo['object']['context']['scope']['webhook'])) {
                    $postArray = $this->getWebhookPostArray($taskInfo['object']['context']['scope']);
                } else {
                    $postArray = $this->getNormalPostArray($taskInfo['object']['context']['scope']);
                }
                if ($importInfo = $this->webservice->call('/elasticsearch/search/result?index=filebeat-%2A', $postArray, 'POST')) {
                    $this->importInfo = $importInfo['object']['hits']['hits'];
                    return $this->importInfo;
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
        return 'import';
    }

    /**
     * @param $dataFields
     * @return array
     */
    public function getNormalPostArray($dataFields)
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
                                        "bool" => [
                                            "should" => [
                                                [
                                                    "bool" => [
                                                        "must" => [
                                                            [
                                                                "bool" => [
                                                                    "should" => [
                                                                        [
                                                                            "match" => [
                                                                                "@fields.scope.task" => $dataFields['task']
                                                                            ]
                                                                        ],
                                                                        [
                                                                            "bool" => [
                                                                                "must_not" => [
                                                                                    "exists" => [
                                                                                        "field" => "@fields.scope.task"
                                                                                    ]
                                                                                ]
                                                                            ]
                                                                        ]
                                                                    ]
                                                                ]
                                                            ],
                                                            [
                                                                "bool" => [
                                                                    "should" => [
                                                                        [
                                                                            "match" => [
                                                                                "@fields.scope.route" => $dataFields['route']
                                                                            ]
                                                                        ],
                                                                        [
                                                                            "bool" => [
                                                                                "must_not" => [
                                                                                    "exists" => [
                                                                                        "field" => "@fields.scope.route"
                                                                                    ]
                                                                                ]
                                                                            ]
                                                                        ]
                                                                    ]
                                                                ]
                                                            ],
                                                            [
                                                                "bool" => [
                                                                    "should" => [
                                                                        [
                                                                            "match" => [
                                                                                "@fields.scope.import" => $dataFields['import']
                                                                            ]
                                                                        ],
                                                                        [
                                                                            "bool" => [
                                                                                "must_not" => [
                                                                                    "exists" => [
                                                                                        "field" => "@fields.scope.import"
                                                                                    ]
                                                                                ]
                                                                            ]
                                                                        ]
                                                                    ]
                                                                ]
                                                            ],
                                                            [
                                                                "bool" => [
                                                                    "should" => [
                                                                        [
                                                                            "match" => [
                                                                                "@fields.scope.route_id" => $dataFields['route_id']
                                                                            ]
                                                                        ],
                                                                        [
                                                                            "bool" => [
                                                                                "must_not" => [
                                                                                    "exists" => [
                                                                                        "field" => "@fields.scope.route_id"
                                                                                    ]
                                                                                ]
                                                                            ]
                                                                        ]
                                                                    ]
                                                                ]
                                                            ],
                                                            [
                                                                "bool" => [
                                                                    "should" => [
                                                                        [
                                                                            "match" => [
                                                                                "@fields.scope.incoming_id" => $dataFields['incoming_id']
                                                                            ]
                                                                        ],
                                                                        [
                                                                            "bool" => [
                                                                                "must_not" => [
                                                                                    "exists" => [
                                                                                        "field" => "@fields.scope.incoming_id"
                                                                                    ]
                                                                                ]
                                                                            ]
                                                                        ]
                                                                    ]
                                                                ]
                                                            ],
                                                            [
                                                                "bool" => [
                                                                    "should" => [
                                                                        [
                                                                            "match" => [
                                                                                "@fields.scope.subscriber.entity" => $dataFields['subscriber.entity']
                                                                            ]
                                                                        ],
                                                                        [
                                                                            "bool" => [
                                                                                "must_not" => [
                                                                                    "exists" => [
                                                                                        "field" => "@fields.scope.subscriber.entity"
                                                                                    ]
                                                                                ]
                                                                            ]
                                                                        ]
                                                                    ]
                                                                ]
                                                            ],
                                                            [
                                                                "bool" => [
                                                                    "should" => [
                                                                        [
                                                                            "match" => [
                                                                                "@fields.scope.import" => $dataFields['import']
                                                                            ]
                                                                        ]
                                                                    ],
                                                                    "minimum_should_match" => 1
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ],
                                            "must_not" => [
                                                [
                                                    "wildcard" => [
                                                        "@message" => "Stopped scope*"
                                                    ]
                                                ],
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

    /**
     * @param $dataFields
     * @return array[]
     */
    public function getWebhookPostArray($dataFields)
    {
        return [
                '_source' => [
                    0 => '@timestamp',
                    1 => '@fields',
                    2 => '@source',
                    3 => '@tags',
                    4 => '@type',
                    5 => '@message',
                ],
                'query' => [
                    'bool' => [
                        'must' => [
                            0 => [
                                'bool' => [
                                    'must' => [
                                        0 => [
                                            'bool' => [
                                                'should' => [
                                                    0 => [
                                                        'bool' => [
                                                            'must' => [
                                                                0 => [
                                                                    'bool' => [
                                                                        'should' => [
                                                                            0 => [
                                                                                'match' => [
                                                                                    '@fields.scope.task' => $dataFields['task'],
                                                                                ],
                                                                            ],
                                                                            1 => [
                                                                                'bool' => [
                                                                                    'must_not' => [
                                                                                        'exists' => [
                                                                                            'field' => '@fields.scope.task',
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                1 => [
                                                                    'bool' => [
                                                                        'should' => [
                                                                            0 => [
                                                                                'match' => [
                                                                                    '@fields.scope.route' => $dataFields['route'],
                                                                                ],
                                                                            ],
                                                                            1 => [
                                                                                'bool' => [
                                                                                    'must_not' => [
                                                                                        'exists' => [
                                                                                            'field' => '@fields.scope.route',
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                2 => [
                                                                    'bool' => [
                                                                        'should' => [
                                                                            0 => [
                                                                                'match' => [
                                                                                    '@fields.scope.import' => $dataFields['import'],
                                                                                ],
                                                                            ],
                                                                            1 => [
                                                                                'bool' => [
                                                                                    'must_not' => [
                                                                                        'exists' => [
                                                                                            'field' => '@fields.scope.import',
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                3 => [
                                                                    'bool' => [
                                                                        'should' => [
                                                                            0 => [
                                                                                'match' => [
                                                                                    '@fields.scope.webhook' => $dataFields['webhook'],
                                                                                ],
                                                                            ],
                                                                            1 => [
                                                                                'bool' => [
                                                                                    'must_not' => [
                                                                                        'exists' => [
                                                                                            'field' => '@fields.scope.webhook',
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                4 => [
                                                                    'bool' => [
                                                                        'should' => [
                                                                            0 => [
                                                                                'match' => [
                                                                                    '@fields.scope.route_id' => $dataFields['route_id'],
                                                                                ],
                                                                            ],
                                                                            1 => [
                                                                                'bool' => [
                                                                                    'must_not' => [
                                                                                        'exists' => [
                                                                                            'field' => '@fields.scope.route_id',
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                5 => [
                                                                    'bool' => [
                                                                        'should' => [
                                                                            0 => [
                                                                                'match' => [
                                                                                    '@fields.scope.webhook_id' => $dataFields['webhook_id'],
                                                                                ],
                                                                            ],
                                                                            1 => [
                                                                                'bool' => [
                                                                                    'must_not' => [
                                                                                        'exists' => [
                                                                                            'field' => '@fields.scope.webhook_id',
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                6 => [
                                                                    'bool' => [
                                                                        'should' => [
                                                                            0 => [
                                                                                'match' => [
                                                                                    '@fields.scope.subscriber.entity' => $dataFields['subscriber.entity'],
                                                                                ],
                                                                            ],
                                                                            1 => [
                                                                                'bool' => [
                                                                                    'must_not' => [
                                                                                        'exists' => [
                                                                                            'field' => '@fields.scope.subscriber.entity',
                                                                                        ],
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                                7 => [
                                                                    'bool' => [
                                                                        'should' => [
                                                                            0 => [
                                                                                'match' => [
                                                                                    '@fields.scope.import' => $dataFields['import'],
                                                                                ],
                                                                            ],
                                                                            1 => [
                                                                                'match' => [
                                                                                    '@fields.scope.webhook' => $dataFields['webhook'],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                        'minimum_should_match' => 1,
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'must_not' => [
                                                    0 => [
                                                        'wildcard' => [
                                                            '@message' => 'Stopped scope*',
                                                        ],
                                                    ],
                                                    1 => [
                                                        'exists' => [
                                                            'field' => '@fields.scope.export',
                                                        ],
                                                    ],
                                                    2 => [
                                                        'exists' => [
                                                            'field' => '@fields.scope.publish',
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            1 => [
                                'range' => [
                                    '@fields.level' => [
                                        'gte' => 200,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'from' => 0,
                'sort' => [
                    0 => [
                        '@timestamp' => [
                            'order' => 'asc',
                        ],
                        '@fields.identifier' => [
                            'order' => 'asc',
                        ],
                    ],
                ],
                'size' => 100,
        ];
    }

}
