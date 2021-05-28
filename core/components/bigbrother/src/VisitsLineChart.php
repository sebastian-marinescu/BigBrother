<?php
namespace modmore\BigBrother;


use DatePeriod;
use DateTime;
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
//        if ($data = $this->cacheManager->get($cacheKey, \BigBrother::$cacheOptions)) {
//            return $data;
//        }

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
            $dataset = $stream['date'] < $halfway ? 1 : 0;
            $output['data'][$dataset][$stream['date']] = [
                'x' => $stream['date'],
                'y' => (int)$stream['screenPageViews'],
            ];

            // At the exact half-way point, make sure both sides of the chart are filled
            if ($stream['date'] === $halfway) {
                $output['data'][1][$stream['date']] = [
                    'x' => $stream['date'],
                    'y' => (int)$stream['screenPageViews'],
                ];
            }
        }

        $output['data'][0] = $this->fillGaps($output['data'][0], '-28 days');
        $output['data'][1] = $this->fillGaps($output['data'][1], '-56 days', '-27 days');

        $this->cacheManager->set($cacheKey, $output, 3600, \BigBrother::$cacheOptions);

        return $output;
    }

    private function fillGaps(array $data, $startTime, $endTime = '+1 day'): array
    {
        $result = [];
        $start = new DateTime($startTime);
        $end = new DateTime($endTime);

        $range = new DatePeriod($start, new \DateInterval('P1D'), $end);
        foreach ($range as $value) {
            $date = $value->format('Ymd');
            $result[] = $data[$date] ?? [
                'x' => $date,
                'y' => 0,
            ];
        }
        
        return $result;
    }
}
