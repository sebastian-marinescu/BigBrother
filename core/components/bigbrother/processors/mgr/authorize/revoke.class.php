<?php
require_once dirname(__DIR__) . '/base.class.php';

class BigBrotherRevokeProcessor extends BigBrotherProcessor
{
    public function process()
    {
        $this->bigBrother->setRefreshToken('');
        $this->bigBrother->setProperty('');
        $this->modx->getCacheManager()->delete(BigBrother::$accessTokenCacheKey, BigBrother::$cacheOptions);
        $this->modx->getCacheManager()->clean(BigBrother::$cacheOptions);

        return $this->success($this->modx->lexicon('bigbrother.revoke_authorization.success'));
    }
}

return BigBrotherRevokeProcessor::class;
