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

    public function dumpReport()
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
                new Dimension([
                    'name' => 'sessionMedium',
                ]),
                new Dimension([
                    'name' => 'sessionSource',
                ]),
                new Dimension([
                    'name' => 'pagePath',
                ]),
            ],
            'metrics' => [
//                new Metric([
//                    'name' => 'sessions',
//                ]),
                new Metric([
                    'name' => 'screenPageViews',
                ]),
//                new Metric([
//                    'name' => 'avgUserEngagementDuration',
//                    'expression' => 'userEngagementDuration / totalUsers',
//                ])
            ],

            'dimensionFilter' => new \Google\Analytics\Data\V1beta\FilterExpression([
                'filter' => new Google\Analytics\Data\V1beta\Filter([
                    'field_name' => 'pagePath',
                    'string_filter' => new Google\Analytics\Data\V1beta\Filter\StringFilter([
                        'value' => '/contentblocks/'
                    ])
                ])
            ]),

            'orderBys' => [
//                new \Google\Analytics\Data\V1beta\OrderBy([
//                    'dimension' => new Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy([
//                        'dimension_name' => 'date'
//                    ])
//                ])

            ]
        ]);


        $dataDimensions = [];
        $dataRows = [];

        /** @var \Google\Analytics\Data\V1beta\MetricHeader[] $headers */
        $headers = $response->getMetricHeaders();
        foreach ($headers as $idx => $header) {
            $dataRows[$idx] = [
                'name' => $header->getName(),
                'type' => $header->getType(),
                'rows' => [],
            ];
        }

        /** @var \Google\Analytics\Data\V1beta\Row $row */
        foreach ($response->getRows() as $row) {

            /** @var \Google\Analytics\Data\V1beta\DimensionValue $dimensionValue */
            foreach ($row->getDimensionValues() as $dimensionValue) {
                $dataDimensions[] = $dimensionValue->getValue();
                print $dimensionValue->getValue() . ' => ';
            }

            /** @var \Google\Analytics\Data\V1beta\MetricValue $metricValue */
            
            foreach ($row->getMetricValues() as $idx => $metricValue) {
                $dataRows[$idx]['rows'][] = $metricValue->getValue();
                print $metricValue->getValue() . ' ';
            }
            print PHP_EOL;
        }

        var_dump($dataRows);
        var_dump($dataDimensions);
    }

}

