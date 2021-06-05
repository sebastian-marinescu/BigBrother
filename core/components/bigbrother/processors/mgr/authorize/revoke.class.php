<?php
require_once dirname(__DIR__) . '/base.class.php';

class BigBrotherRevokeProcessor extends BigBrotherProcessor
{
    public function process()
    {
        $this->bigBrother->setRefreshToken('');
        $this->modx->getCacheManager()->delete(BigBrother::$accessTokenCacheKey, BigBrother::$cacheOptions);

        return $this->success($this->modx->lexicon('bigbrother.revoke_authorization.success'));
    }
}

return BigBrotherRevokeProcessor::class;
