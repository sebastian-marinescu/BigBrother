<?php
require_once dirname(__DIR__) . '/base.class.php';

class BigBrotherRevokeProcessor extends BigBrotherProcessor
{
    public function process()
    {
        $this->bigBrother->setRefreshToken('');
        $this->modx->getCacheManager()->delete(BigBrother::$cacheKey, BigBrother::$cacheOptions);

        return $this->success('Authorization revoked. Please sign in with Google to re-authorize Big Brother.');
    }
}

return BigBrotherRevokeProcessor::class;
