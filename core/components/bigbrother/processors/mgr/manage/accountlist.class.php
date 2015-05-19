<?php
/**
 * Grid default processor
 *
 * @package bigbrother
 * @subpackage processors
 */
class getAccountList extends modProcessor {
    /** @var BigBrother $ga */
    public $ga = null;
    public $error = null;

    public function initialize() {
        $this->ga =& $this->modx->bigbrother;
        return true;
    }

    public function process() {
        if( !$this->ga->loadOAuth() ){
            return $this->failure( $this->modx->lexicon('bigbrother.err_load_oauth') );
        }
        $result = $this->callAPI( $this->ga->baseUrl . 'management/accounts/~all/webproperties/~all/profiles' );
        if( !empty( $this->error ) ){
            return $this->failure( $this->error );
        }

        $output = $account = array();
        $assign = $this->getProperty('assign', false);
        if( $assign ){
            $account['name'] = $this->modx->lexicon('bigbrother.user_account_default');
            $account['id'] = $this->modx->lexicon('bigbrother.user_account_default');
            $output[] = $account;
        }
        $total = 0;
        // Get account list
        foreach( $result['items'] as $value ){
            $account['id'] = $value['id'];
            $account['name'] = $value['name'];
            $account['websiteUrl'] = $value['websiteUrl'];
            $account['webPropertyId'] = $value['webPropertyId'];
            $output[] = $account;
            $total += 1;
        }

        //$this->ga->updateOption('total_account', $result['totalResults']);
        $this->ga->updateOption('total_account', $total);
        return $this->success( '', $output );
    }

    /**
     * Call the GA API via curl
     * @param string $url
     * @return string
     */
    public function callAPI( $url ){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( $this->ga->createAuthHeader($url, 'GET') ) );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        if( curl_errno( $ch ) ){
            $this->error = curl_error($ch);
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'cURL Error on API call to ' . $url . ': ' . $this->error);
            return false;
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if( $http_code !== 200 ){
            $this->error = $result;
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Non-200 HTTP Code returned from calling ' . $url . ': ' . $http_code . ' Result: ' . $this->error);
            return false;
        }
        curl_close($ch);
        return $this->modx->fromJSON( $result );
    }

    /**
     * Return a success message from the processor.
     * @param array $output
     * @return string
     */
    public function success( $msg = '', $output = null ){
        $response = array(
            'success' => true,
            'results' => $output,
        );
        return $this->modx->toJSON( $response );
    }
}
return 'getAccountList';
