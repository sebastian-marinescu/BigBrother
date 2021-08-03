<?php

abstract class BigBrotherProcessor extends modProcessor
{
    public $permission = 'frames';

    public function getLanguageTopics()
    {
        return ['bigbrother:default'];
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
            return $this->modx->lexicon('bigbrother.not_found');
        }
        try {
            $this->bigBrother->getOAuth2();
        } catch (Exception $e) {
            return $this->modx->lexicon('bigbrother.guzzle_error');
        }

        return parent::initialize();
    }
}