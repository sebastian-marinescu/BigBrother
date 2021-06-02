<?php
namespace modmore\BigBrother;

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DimensionHeader;
use Google\Analytics\Data\V1beta\DimensionValue;
use Google\Analytics\Data\V1beta\MetricHeader;
use Google\Analytics\Data\V1beta\MetricValue;
use Google\Analytics\Data\V1beta\Row;
use MODX\Revolution\modCacheManager;
use MODX\Revolution\modX;

abstract class BaseReport
{
    /**
     * @var BetaAnalyticsDataClient
     */
    protected $client;
    /**
     * @var \modX|modX
     */
    protected $modx;
    /**
     * @var \modCacheManager|modCacheManager
     */
    protected $cacheManager;
    /**
     * @var string
     */
    protected $property;

    /**
     * BaseReport constructor.
     * @param BetaAnalyticsDataClient $client
     * @param \modCacheManager|modCacheManager $cacheManager
     * @param string $property
     */
    public function __construct(BetaAnalyticsDataClient $client, $modx, string $property)
    {
        $this->client = $client;
        $this->modx = $modx;
        $this->cacheManager = $modx->cacheManager;
        $this->property = $property;
    }

    public function parseReportToArray($response, $print = false): array
    {
        $metricHeaders = [];
        $dimensionHeaders = [];
        $data = [];

        /** @var MetricHeader $metricHeader */
        foreach ($response->getMetricHeaders() as $metricHeader) {
            $metricHeaders[] = $metricHeader->getName();
        }

        /** @var DimensionHeader $dimensionHeader */
        foreach ($response->getDimensionHeaders() as $dimensionHeader) {
            $dimensionHeaders[] = $dimensionHeader->getName();
        }

        /** @var Row $row */
        foreach ($response->getRows() as $row) {
            $rowData = [];

            /** @var DimensionValue $dimensionValue */
            foreach ($row->getDimensionValues() as $idx => $dimensionValue) {
                $rowData[$dimensionHeaders[$idx]] = $dimensionValue->getValue();
                if ($print) print $dimensionValue->getValue() . ' => ';
            }

            /** @var MetricValue $metricValue */
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

    abstract public function run(array $params = []): array;
}