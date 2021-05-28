<?php
namespace modmore\BigBrother;


use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;

class VisitsLineChart extends BaseReport
{
    public function run(array $params = []): array
    {
        $cacheKey = 'reports/visits-line/' . date('Ymd');
        if ($data = $this->cacheManager->get($cacheKey, \BigBrother::$cacheOptions)) {
            return $data;
        }

        $response = $this->client->runReport([
            'property' => 'properties/' . $this->property,
            'dateRanges' => [
                new DateRange([
                    'start_date' => '56daysAgo',
                    'end_date' => 'today',
                ]),
            ],
            'dimensions' => [
                new Dimension([
                    'name' => 'date',
                ]),
            ],
            'metrics' => [
                new Metric([
                    'name' => 'screenPageViews',
                ]),
            ],
            'orderBys' => [
                new OrderBy([
                    'dimension' => new DimensionOrderBy([
                        'dimension_name' => 'date'
                    ])
                ])
            ]
        ]);

        $data = $this->parseReportToArray($response);


        $output = [
            'data' => [
                0 => [],
                1 => [],
            ]
        ];

        $halfway = date('Ymd', strtotime('-28 days'));

        foreach ($data as $stream) {
            $dataset = $stream['date'] <= $halfway ? 1 : 0;
            $output['data'][$dataset][] = [
                'x' => $stream['date'],
                'y' => $stream['screenPageViews'],
            ];
        }

        $this->cacheManager->set($cacheKey, $output, 3600, \BigBrother::$cacheOptions);

        return $output;
    }
}