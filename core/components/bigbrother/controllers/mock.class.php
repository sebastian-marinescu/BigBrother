<?php
/**
 * BigBrother
 *
 *
 * @package bigbrother
 * @subpackage controllers
 */

use Google\Analytics\Admin\V1alpha\AnalyticsAdminServiceClient;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;
use Google\Analytics\Data\V1beta\RunRealtimeReportResponse;
use Google\Analytics\Data\V1beta\RunReportResponse;

require_once dirname(__DIR__) . '/model/bigbrother/bigbrother.class.php';

class BigbrotherMockManagerController extends modExtraManagerController {
    /** @var BigBrother $bigbrother */
    protected $bigbrother;

    public function initialize()
    {
        $this->bigbrother = new BigBrother($this->modx);
    }

    public function process(array $scriptProperties = array())
    {

        echo '<pre>';
//        $this->dumpProperties();
//        $this->dumpPageSpecificReport();
//        $this->dumpRealtimeReport();
        $this->dumpReport();

        exit();
    }

    public function dumpProperties() {
        $oauth = $this->bigbrother->getOAuth2();

        $admin = new AnalyticsAdminServiceClient(['credentials' => $oauth]);

        /** @var \Google\Analytics\Admin\V1alpha\AccountSummary[] $summaries */
        $summaries = $admin->listAccountSummaries();
        foreach ($summaries as $summary) {
            print $summary->getDisplayName() . PHP_EOL;

            /** @var \Google\Analytics\Admin\V1alpha\PropertySummary[] $properties */
            $properties = $summary->getPropertySummaries();
            foreach ($properties as $property) {
                $propNum = substr($property->getProperty(), strlen('properties/'));
                print "\t- " . $property->getDisplayName() . ' [' . $propNum . ']' . PHP_EOL;
            }
        }
    }

    public function dumpPageSpecificReport()
    {
        $property = '268477819'; // @fixme

        $oauth = $this->bigbrother->getOAuth2();
        $client = new BetaAnalyticsDataClient(['credentials' => $oauth]);

        $response = $client->runReport([
            'property' => 'properties/' . $property,
            'dateRanges' => [
                new DateRange([
                    'start_date' => '2020-03-31',
                    'end_date' => 'today',
                ]),
            ],
            'dimensions' => [
//                new Dimension([
//                    'name' => 'date',
//                ]),
//                new Dimension([
//                    'name' => 'hostName',
//                ]),
//                new Dimension([
//                    'name' => 'sessionMedium',
//                ]),
//                new Dimension([
//                    'name' => 'sessionSource',
//                ]),
//                new Dimension([
//                    'name' => 'pagePath',
//                ]),
            ],
            'metrics' => [
                new Metric([
                    'name' => 'sessions',
                ]),
                new Metric([
                    'name' => 'screenPageViews',
                ]),
//                new Metric([
//                    'name' => 'engagementRate',
//                ]),
                new Metric([
                    'name' => 'avgUserEngagementDuration',
                    'expression' => 'userEngagementDuration / totalUsers',
                ]),
            ],

//            'dimensionFilter' => new \Google\Analytics\Data\V1beta\FilterExpression([
//                'filter' => new Google\Analytics\Data\V1beta\Filter([
//                    'field_name' => 'pagePath',
//                    'string_filter' => new Google\Analytics\Data\V1beta\Filter\StringFilter([
//                        'value' => '/contentblocks/'
//                    ])
//                ])
//            ]),

            'orderBys' => [
//                new \Google\Analytics\Data\V1beta\OrderBy([
//                    'dimension' => new Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy([
//                        'dimension_name' => 'date'
//                    ])
//                ])
            ]
        ]);

        var_dump($this->parseReportToArray($response));
    }

    public function dumpRealtimeReport()
    {
        $property = '268477819'; // @fixme

        $oauth = $this->bigbrother->getOAuth2();
        $client = new BetaAnalyticsDataClient(['credentials' => $oauth]);

        $response = $client->runRealtimeReport([
            'property' => 'properties/' . $property,
            'dimensions' => [
                new Dimension([
                    'name' => 'unifiedScreenName',
                ]),
            ],
            'metrics' => [
                new Metric([
                    'name' => 'screenPageViews',
                ]),
            ],
        ]);

        var_dump($this->parseReportToArray($response));
    }

    public function dumpReport()
    {
        $property = '268477819'; // @fixme

        $oauth = $this->bigbrother->getOAuth2();
        $client = new BetaAnalyticsDataClient(['credentials' => $oauth]);

//        $response = $client->runReport([
//            'property' => 'properties/' . $property,
//            'dateRanges' => [
//                new DateRange([
//                    'start_date' => '28daysAgo',
//                    'end_date' => 'today',
//                ]),
//                new DateRange([
//                    'start_date' => '56daysAgo',
//                    'end_date' => '28daysAgo',
//                ]),
//            ],
//            'dimensions' => [
////                new Dimension([
////                    'name' => 'date',
////                ]),
////                new Dimension([
////                    'name' => 'audienceName',
////                ]),
////                new Dimension([
////                    'name' => 'brandingInterest',
////                ]),
//            ],
//            'metrics' => [
////                new Metric([
////                    'name' => 'sessions',
////                ]),
//                new Metric([
//                    'name' => 'screenPageViews',
//                ]),
//            ],
//            'orderBys' => [
//                new OrderBy([
//                    'dimension' => new DimensionOrderBy([
//                        'dimension_name' => 'date'
//                    ])
//                ])
//            ]
//        ]);



        $response = $client->runReport([
            'property' => 'properties/' . $property,
            'dateRanges' => [
                new DateRange([
                    'start_date' => '28daysAgo',
                    'end_date' => 'today',
                ]),
                new DateRange([
                    'start_date' => '56daysAgo',
                    'end_date' => '28daysAgo',
                ]),
            ],
            'metrics' => [
                new Metric([
                    'name' => 'sessions',
                ]),
                new Metric([
                    'name' => 'screenPageViews',
                ]),
                new Metric([
                    'name' => 'activeUsers',
                ]),
                new Metric([
                    'name' => 'avgUserEngagementDuration',
                    'expression' => 'userEngagementDuration / totalUsers',
                ]),
            ],
        ]);
        var_dump($this->parseReportToArray($response));

//        $response = $client->runReport([
//            'property' => 'properties/' . $property,
//            'dateRanges' => [
//                new DateRange([
//                    'start_date' => '2020-03-31',
//                    'end_date' => 'today',
//                ]),
//            ],
//            'dimensions' => [
////                new Dimension([
////                    'name' => 'date',
////                ]),
//                new Dimension([
//                    'name' => 'sessionMedium',
//                ]),
//                new Dimension([
//                    'name' => 'sessionSource',
//                ]),
//                new Dimension([
//                    'name' => 'pagePath',
//                ]),
//            ],
//            'metrics' => [
////                new Metric([
////                    'name' => 'sessions',
////                ]),
////                new Metric([
////                    'name' => 'screenPageViews',
////                ]),
//                new Metric([
//                    'name' => 'engagementRate',
//                ]),
////                new Metric([
////                    'name' => 'avgUserEngagementDuration',
////                    'expression' => 'userEngagementDuration / totalUsers',
////                ])
//            ],
//
//            'dimensionFilter' => new \Google\Analytics\Data\V1beta\FilterExpression([
//                'filter' => new Google\Analytics\Data\V1beta\Filter([
//                    'field_name' => 'pagePath',
//                    'string_filter' => new Google\Analytics\Data\V1beta\Filter\StringFilter([
//                        'value' => '/contentblocks/'
//                    ])
//                ])
//            ]),
//
//            'orderBys' => [
////                new \Google\Analytics\Data\V1beta\OrderBy([
////                    'dimension' => new Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy([
////                        'dimension_name' => 'date'
////                    ])
////                ])
//
//            ]
//        ]);


    }

    /**
     * @param RunReportResponse|RunRealtimeReportResponse $response
     * @param bool $print
     * @return array
     */
    public function parseReportToArray($response, $print = true): array
    {
        $metricHeaders = [];
        $dimensionHeaders = [];
        $data = [];

        /** @var \Google\Analytics\Data\V1beta\MetricHeader $metricHeader */
        foreach ($response->getMetricHeaders() as $metricHeader) {
            $metricHeaders[] = $metricHeader->getName();
        }

        /** @var \Google\Analytics\Data\V1beta\DimensionHeader $dimensionHeader */
        foreach ($response->getDimensionHeaders() as $dimensionHeader) {
            $dimensionHeaders[] = $dimensionHeader->getName();
        }

        /** @var \Google\Analytics\Data\V1beta\Row $row */
        foreach ($response->getRows() as $row) {
            $rowData = [];

            /** @var \Google\Analytics\Data\V1beta\DimensionValue $dimensionValue */
            foreach ($row->getDimensionValues() as $idx => $dimensionValue) {
                $rowData[$dimensionHeaders[$idx]] = $dimensionValue->getValue();
                if ($print) print $dimensionValue->getValue() . ' => ';
            }

            /** @var \Google\Analytics\Data\V1beta\MetricValue $metricValue */

            foreach ($row->getMetricValues() as $idx => $metricValue) {
                $rowData[$metricHeaders[$idx]] = $metricValue->getValue();
                if ($print) print '[' . $metricHeaders[$idx] . '] ' . $metricValue->getValue() . ' ';
            }

            $data[] = $rowData;
            if ($print) print PHP_EOL;
        }

        if ($print) var_dump($data);

        return $data;
    }
}

