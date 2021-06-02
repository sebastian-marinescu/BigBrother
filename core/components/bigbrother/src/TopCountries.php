<?php
namespace modmore\BigBrother;


use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;

class TopCountries extends BaseReport
{
    public function run(array $params = []): array
    {
        $cacheKey = 'reports/top-countries';
//        if ($data = $this->cacheManager->get($cacheKey, \BigBrother::$cacheOptions)) {
//            return $data;
//        }

        $response = $this->client->runReport([
            'property' => 'properties/' . $this->property,
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
            'dimensions' => [
                new Dimension([
                    'name' => 'country',
                ]),
            ],
            'metrics' => [
                new Metric([
                    'name' => 'activeUsers',
                ]),
            ],
            'orderBys' => [
                new OrderBy([
                    'dimension' => new DimensionOrderBy([
                        'dimension_name' => 'country',
                        'order_type' => DimensionOrderBy\OrderType::ALPHANUMERIC
                    ]),
                    'desc' => true,
                ])
            ],
            'limit' => 50
        ]);

        $data = $this->parseReportToArray($response);
        //return $data;

        $output = [];
        foreach ($data as $value) {
            $isLatest = $value['dateRange'] === 'date_range_0' ? true : false;
            $output['country'] = [
                'title' => $value['country'],
                'value' => 0,
                'previous' => 0,
            ];

            $output['country'][$isLatest ? 'previous' : 'value'] += (int)$value['country'];
        }

        foreach ($output as $key => $values) {
            $output[$key]['improved'] = $values['value'] > $values['previous'];
        }

        uasort($output, static function ($a, $b) {
            return $a['value'] > $b['value'] ? -1 : 1;
        });
        $output = array_values($output);

        //$this->cacheManager->set($cacheKey, $output, 3600, \BigBrother::$cacheOptions);

        return $output;
    }

    private function processMetrics(array $metrics)
    {
        $output = [];
        foreach ($metrics as $key => $metric) {
            $mn = 'process' . ucfirst($key);
            if (method_exists($this, $mn)) {
                $output[] = $this->{$mn}($metric);
            }
        }
        return $output;
    }

    private function processSessions(array $metric)
    {
        return [
            'label' => 'Sessions',
            'value' => number_format($metric['value']),
            'previous' => number_format($metric['previous']),
            'improved' => $metric['value'] > $metric['previous'],
        ];
    }

    private function processScreenPageViews(array $metric)
    {
        return [
            'label' => 'Pageviews',
            'value' => number_format($metric['value']),
            'previous' => number_format($metric['previous']),
            'improved' => $metric['value'] > $metric['previous'],
        ];
    }
}
