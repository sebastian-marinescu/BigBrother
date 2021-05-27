<?php

require_once __DIR__ . '/base.class.php';

class BigBrotherReportsProcessor extends BigBrotherProcessor
{
    public function process()
    {
        sleep(2);
        $keys = $this->getProperty('reports');
        $keys = array_filter(array_map('trim', explode(',', $keys)));

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $data = [];
        foreach ($keys as $key) {
            shuffle($days);
            $data[$key] = [
                'data' => [
                    [
                        ['x' => 1, 'y' => random_int(0, 30)],
                        ['x' => 2, 'y' => random_int(0, 30)],
                        ['x' => 3, 'y' => random_int(0, 30)],
                        ['x' => 4, 'y' => random_int(0, 30)],
                        ['x' => 5, 'y' => random_int(0, 30)],
                        ['x' => 6, 'y' => random_int(0, 30)],
                        ['x' => 7, 'y' => random_int(0, 30)],
                    ], [
                        ['x' => 1, 'y' => random_int(0, 30)],
                        ['x' => 2, 'y' => random_int(0, 30)],
                        ['x' => 3, 'y' => random_int(0, 30)],
                        ['x' => 4, 'y' => random_int(0, 30)],
                        ['x' => 5, 'y' => random_int(0, 30)],
                        ['x' => 6, 'y' => random_int(0, 30)],
                        ['x' => 7, 'y' => random_int(0, 30)],
                    ],
                ],
                'labels' => $days,
            ];
        }

        return json_encode(['success' => true, 'message' => '', 'data' => $data]);
    }
}

return BigBrotherReportsProcessor::class;
