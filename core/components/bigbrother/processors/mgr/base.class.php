<?php

abstract class BigBrotherProcessor extends modProcessor
{
    public $permission = 'frames';

    public function getLanguageTopics()
    {
        return ['bigbrother:mgr'];
    }

    /**
     * @var BigBrother
     */
    protected $bigBrother;

    public function initialize()
    {
        $corePath = $this->modx->getOption('bigbrother.core_path', null, $this->modx->getOption('core_path') . 'components/bigbrother/');
        $this->bigBrother = $this->modx->getService('bigbrother', 'BigBrother', $corePath . 'model/');
        if (!$this->bigBrother) {
            return 'BigBrother not found.';
        }
        return parent::initialize();
    }
}