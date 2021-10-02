<?php

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;

require_once __DIR__ . '/base.class.php';

class BigBrotherReportsProcessor extends BigBrotherProcessor
{
    private $reports = [
        'visits/line' => \modmore\BigBrother\VisitsLineChart::class,
        'key-metrics' => \modmore\BigBrother\KeyMetrics::class,
        'acquisition' => \modmore\BigBrother\Acquisition::class,
        'popular-pages' => \modmore\BigBrother\PopularPages::class,
        'top-countries' => \modmore\BigBrother\TopCountries::class,
        'top-referrers' => \modmore\BigBrother\TopReferrers::class,
    ];

    public function process()
    {
        $keys = $this->getProperty('reports');
        $keys = array_unique(array_filter(array_map('trim', explode(',', $keys))));

        $oauth = $this->bigBrother->getOAuth2();
        $client = new BetaAnalyticsDataClient(['credentials' => $oauth]);
        $property = $this->bigBrother->getPropertyID();

        $params = [];
        $data = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $this->reports)) {
                try {
                    $data[$key] = (new $this->reports[$key]($client, $this->modx, $property))->run($params);
                } catch (Exception $e) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[BigBrother] Received ' . get_class($e) . ' running report "' . $key . '": ' . $e->getMessage());
                } catch (Error $e) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[BigBrother] Received ' . get_class($e) . ' running report "' . $key . '": ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
                }
            }
        }

        return json_encode(['success' => true, 'message' => '', 'data' => $data]);
    }
}

return BigBrotherReportsProcessor::class;
