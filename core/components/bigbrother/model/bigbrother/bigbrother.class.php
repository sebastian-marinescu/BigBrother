<?php

/**
 * Main class file for bigbrother.
 *
 * @author Stephane Boulard <lossendae@gmail.com>
 * @package bigbrother
 */
class BigBrother {
    /**
     * @access protected
     * @var array A collection of preprocessed chunk values.
     */
    protected $chunks = array();
    /**
     * @access public
     * @var modX A reference to the modX object.
     */
    public $modx = null;
    /**
     * @access public
     * @var array A collection of properties to adjust bigbrother behaviour.
     */
    public $config = array();
    public $report = null;
    public $sideReport = null;
    public $cacheKey = null;
    public $cacheOptions = array(
        xPDO::OPT_CACHE_KEY => 'bigbrother',
    );
    public $baseUrl = 'https://www.googleapis.com/analytics/v3/';
    public $oauthEndpoint = 'https://accounts.google.com/o/oauth2/auth';
    public $oauthTokenEndpoint = 'https://accounts.google.com/o/oauth2/token';
    private $output = null;
    public $http_code;

    /**
     * The bigbrother Constructor.
     *
     * This method is used to create a new BigBrother object.
     *
     * @param modX &$modx A reference to the modX object.
     * @param array $config A collection of properties that modify bigbrother
     * behaviour.
     * @return bigbrother A unique bigbrother instance.
     */
   public function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $core = $this->modx->getOption('bigbrother.core_path',$config,$this->modx->getOption('core_path').'components/bigbrother/');
        $assets_url = $this->modx->getOption('bigbrother.assets_url',$config,$this->modx->getOption('assets_url').'components/bigbrother/');
        $assets_path = $this->modx->getOption('bigbrother.assets_path',$config,$this->modx->getOption('assets_path').'components/bigbrother/');

        $this->config = array_merge(array(
            'core_path' => $core,
            'model_path' => $core.'model/',
            'processors_path' => $core.'processors/',
            'controllers_path' => $core.'controllers/',
            'chunks_path' => $core.'elements/chunks/',
            'assets_url' => $assets_url,
            'css_url' => $assets_url.'css/',
            'connector_url' => $assets_url.'connector.php',
            'debug' => false,
        ),$config);

        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('bigbrother:default');
        }
        $this->initDebug();
    }

    /**
    * Load debugging settings
    */
    public function initDebug() {
        if ($this->modx->getOption('debug',$this->config,false)) {
            error_reporting(E_ALL); ini_set('display_errors',true);
            $this->modx->setLogTarget('HTML');
            $this->modx->setLogLevel(modX::LOG_LEVEL_ERROR);

            $debugUser = $this->config['debugUser'] == '' ? $this->modx->user->get('username') : 'anonymous';
            $user = $this->modx->getObject('modUser',array('username' => $debugUser));
            if ($user == null) {
                $this->modx->user->set('id',$this->modx->getOption('debugUserId',$this->config,1));
                $this->modx->user->set('username',$debugUser);
            } else {
                $this->modx->user = $user;
            }
        }
    }

    /**
     * Load the OAuth2 Service
     *
     * @access public
     * @return boolean|OAuth2\Client
     */
    public function loadOAuth() {
        $basePath = dirname(dirname(__FILE__)) . '/OAuth2/';
        require_once $basePath . 'Client.php';
        require_once $basePath . 'GrantType/IGrantType.php';
        require_once $basePath . 'GrantType/AuthorizationCode.php';
        require_once $basePath . 'GrantType/RefreshToken.php';

        $clientId = $this->getOption('native_app_client_id');
        $clientSecret = $this->getOption('native_app_client_secret');

        try {
            $client = new OAuth2\Client($clientId, $clientSecret);
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
            return false;
        }
        return $client;
    }

    /**
     * Extract the cURL response params and create an associative array with the paired values
     *
     * @access public
     * @param string $response The cURL string response
     * @return array The params associative array
     */
    public function splitParams($response) {
        $params = array();
        $pairs = explode('&', $response);
        foreach($pairs as $pair){
            if (trim($pair) == '') { continue; }
            list($key, $value) = explode('=', $pair);
            $params[$key] = urldecode($value);
        }
        return $params;
    }

    /**
     * Get dates for the requested report
     *
     * @access public
     * @param string $format The date format to return
     * @param boolean $delay Wheter to calculate date delayed for comparison
     * @return array The begin and end dates for the requested period
     */
    public function getDates($format = 'Y-m-d', $delay = false){
        $end = $this->getOption('date_end');
        $begin = $end .' - '. $this->getOption('date_begin') .' days';
        if(!$delay){
            $date['begin'] = date($format, strtotime($begin));
            $date['end'] = date($format, strtotime($end));
        } else {
            $dateBegin = $this->getOption('date_begin') + 1;
            $end = $end  .' - 35 days';
            $begin = $end .' - '. $this->getOption('date_begin') .' days';
            $date['begin'] = date($format, strtotime($begin));
            $date['end'] = date($format, strtotime($end));
        }
        return $date;
    }

    /**
     * Wrapper method to get total visits for specified period
     *
     * @access public
     * @param string $dateStart The starting date
     * @param string $dateEnd The End date
     * @return string The total vistis for specified period
     */
    public function getTotalVisits($dateStart, $dateEnd){
        $url = $this->buildUrl($dateStart, $dateEnd, null, array('ga:visits'));
        $report = $this->getReport($url, true);
        return $report['rows'][0][0];
    }

    /**
     * Get a translated name for the specified metric
     *
     * @access public
     * @param string $key The metric to translate
     * @return string The translated metric
     */
    public function getName($key) {
        $metrics = array(
            'ga:pageviewsPerVisit',
            'ga:pageviews',
            'ga:visits',
            'ga:visitors',
            'ga:avgSessionDuration',
            'ga:visitBounceRate',
            'ga:percentNewVisits',
            'ga:newVisits',
            'ga:uniquePageviews',
            'ga:exitRate',
        );
        $replacements = array(
            $this->modx->lexicon('bigbrother.pageviews_per_visit'),
            $this->modx->lexicon('bigbrother.pageviews'),
            $this->modx->lexicon('bigbrother.visits'),
            $this->modx->lexicon('bigbrother.visitors'),
            $this->modx->lexicon('bigbrother.avg_time_on_site'),
            $this->modx->lexicon('bigbrother.bounce_rate'),
            $this->modx->lexicon('bigbrother.new_visits_in_percent'),
            $this->modx->lexicon('bigbrother.new_visits'),
            $this->modx->lexicon('bigbrother.unique_pageviews'),
            $this->modx->lexicon('bigbrother.exit_rate'),
        );
        $name = str_replace($metrics, $replacements, $key);
        return $name;
    }

    /**
     * Get a translated name for the specified dimension
     *
     * @access public
     * @param string $key The dimension to translate
     * @return string The translated dimension
     */
    public function getDimensionName($key){
        $dimensions = array(
            $this->modx->lexicon('bigbrother.search_traffic'),
            $this->modx->lexicon('bigbrother.referral_traffic'),
        );
        $replacements = array(
            $this->modx->lexicon('bigbrother.search_traffic_replace_with'),
            $this->modx->lexicon('bigbrother.referral_traffic_replace_with'),
        );
        $name = str_replace($dimensions, $replacements, $key);
        return $name;
    }

    /**
     * Format metric value for front end display
     *
     * @access public
     * @param string $name The metric name
     * @param string $value The metric value
     * @return string The formatted metric value
     */
    public function formatValue($name, $value){
        switch($name){
            case 'ga:avgTimeOnSite':
                $value = sprintf("%02u:%02u:%02u", $value/3600, $value%3600/60, $value%60);
                break;
            case 'ga:percentNewVisits':
            case 'ga:visitBounceRate':
            case 'ga:exitRate':
                $value = round($value, 2) .' %';
                break;
            case 'ga:pageviewsPerVisit':
                $value = round($value, 2);
                break;
            default:
                break;
        }
        return $value;
    }

    /**
     * Helper method to create a new MODx system setting
     *
     * @param string $key The setting key
     * @param mixed $value The setting value
     * @param string $type The setting type (Optionnal) default to textfield
     * @access public
     * @return boolean
     */
    public function addOption($key, $value, $type = 'textfield') {
        $setting = $this->modx->newObject('modSystemSetting');
        $setting->fromArray(array(
            'key' => 'bigbrother.'. $key,
            'value' => $value,
            'xtype' => $type,
            'namespace' => 'bigbrother',
            'area' => 'Google Analytics for MODx Revolution',
        ),'',true);
        return $setting->save();
    }

    /**
     * Helper method to update/create a new MODx system setting
     *
     * @param string $key The setting key
     * @param mixed $value The setting value
     * @param string $type The setting type (Optionnal) default to textfield
     * @access public
     * @return boolean
     */
    public function updateOption($key, $value, $type = 'textfield') {
        $setting = $this->modx->getObject('modSystemSetting', array(
            'key' => 'bigbrother.'. $key,
        ));
        if(!$setting){ $setting = $this->modx->newObject('modSystemSetting'); }
        $setting->fromArray(array(
            'key' => 'bigbrother.'. $key,
            'value' => $value,
            'xtype' => $type,
            'namespace' => 'bigbrother',
            'area' => 'Google Analytics for MODx Revolution',
        ),'',true);
        return $setting->save();
    }

    /**
     * Helper method to retreive a bigbrother related system setting
     *
     * @access public
     * @param string $key The setting name
     * @param boolean $key Whether to check the user setting used to override an account for reports
     * @return mixed The system requested setting
     */
    public function getOption($key, $checkUser = true) {
        // Allow user Settings to override global system_settings when searching for a specific account
        if(($key == "account" && $checkUser) || ($key == "account_name" && $checkUser)){
            $setting = $this->modx->getObject('modUserSetting', array(
                'key' => 'bigbrother.'. $key,
                'user' => $this->modx->user->id,
            ));
            if($setting){ return $setting->value; }
        }
        $setting = $this->modx->getObject('modSystemSetting', array(
            'key' => 'bigbrother.'. $key,
        ));
        if($setting){ return $setting->value; }
        return null;
    }

    /**
     * Helper method to remove a bigbrother related system setting
     *
     * @param string $key The setting name
     * @access public
     * @return boolean Whether the setting has been removed or not
     */
    public function deleteOption($key) {
        $setting = $this->modx->getObject('modSystemSetting', array('key' => 'bigbrother.'. $key));
        if($setting){ return $setting->remove(); }
    }

    /**
     * Create the http header for cURL
     *
     * @param string $url The url to retreive the data from
     * @param string $method The HTTP method to use
     * @access protected
     * @return mixed The requested header or false if no url is provided
     */
    public function createAuthHeader($url = null, $method = null) {
        $accessToken = $this->modx->getCacheManager()->get('access_token', $this->cacheOptions);
        if (empty($accessToken)) {
            $accessToken = $this->refreshAccessToken();
        }

        return 'Authorization: Bearer ' . $accessToken;
    }

    public function refreshAccessToken() {
        $client = $this->loadOAuth();

        $params = array(
            'refresh_token' => $this->getOption('refresh_token'),
            'grant_type' => 'refresh_token',
        );

        $result = false;
        try {
            $result = $client->getAccessToken($this->oauthTokenEndpoint, 'refresh_token', $params);
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error refreshing oAuth2 token: ' . $e->getMessage());
        }

        if (is_array($result) && $result['code'] == 200) {
            $accessToken = $result['result']['access_token'];
            $expiresIn = $result['result']['expires_in'];
            $this->modx->getCacheManager()->set('access_token', $accessToken, $expiresIn, $this->cacheOptions);
            return $accessToken;
        }

        $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not refresh access token!');
        return false;
    }

    /**
     * Build the report url
     *
     * @param string $dateStart The beginning date
     * @param string $dateEnd The end date
     * @param array $dimensions
     * @param array $metrics
     * @param array $sort
     * @param array $filters
     * @param array $limit
     * @access public
     * @return string $url The url to retreive reports from or to build the cached result set
     */
    public function buildUrl($dateStart, $dateEnd, $dimensions = array(), $metrics = array(), $sort = array(), $filters = array(), $limit = null){
        $queryString = array();
        $url  = $this->baseUrl . 'data/ga?';

        $queryString[] = 'ids=ga:' . $this->getOption('account');
        if (is_array($dimensions) && count($dimensions) > 0) {
            $queryString[] = ('&dimensions=' . implode(',', array_reverse($dimensions)));
        }
        if (is_array($metrics) && count($metrics) > 0) {
            $queryString[] = ('&metrics=' . implode(',', $metrics));
        }
        if (is_array($sort) && count($sort) > 0) {
            $queryString[] = '&sort=' . implode(',', $sort);
        }
        if (is_array($filters) && count($filters) > 0) {
            $queryString[] = '&filters=' . urlencode(implode(',', $filters));
        }
        $queryString[] = '&start-date=' . $dateStart;
        $queryString[] = '&end-date=' .$dateEnd;
        if (!empty($limit)) {
            $queryString[] = '&max-results=' . (int)$limit;
        }

        $url .= implode('', $queryString);
        $this->cacheKey = md5(urlencode($url));

        return $url;
    }

    /**
     * Get a report from Google
     *
     * @param string $url The google url to retreive reports from
     * @param boolean $returnArray Whether the result array should be returned directly
     * @return boolean true if the report is successfully fetched, false if any error
     */
    public function getReport($url, $returnArray = false){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($this->createAuthHeader($url, 'GET')));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $return = curl_exec($ch);

        if(curl_errno($ch)){
            $this->output = curl_error($ch);
            return false;
        }

        $this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($this->http_code != 200){
            $this->output = $return;
            return false;
        } else {
            $arr = $this->modx->fromJSON( $return );
            curl_close($ch);
            if( $returnArray ){
                return $arr;
            }
            $this->report = $arr;
        }
        return true;
    }

    /**
     * Get the report result or any error message if any
     *
     * @access public
     * @return string The requested output
     */
    public function getOutput(){
        return $this->output;
    }

    /**
     * Returns the full URL or the manager
     *
     * @return string The full URL of the manager
     */
    public function getManagerLink() {
        $site_url = $this->modx->getOption('site_url');
        $base_url =  $this->modx->getOption('base_url');
        $manager_url = $this->modx->getOption('manager_url');
        if($base_url == '/'){
            $url = preg_replace('{/$}','', $site_url ) . $manager_url;
        } else {
            $url = str_replace( $base_url, '' , $site_url ) . $manager_url;
        }
        return $url;
    }
}
