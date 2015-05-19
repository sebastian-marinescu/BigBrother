<?php
/**
* BigBrother
*
*
* @package bigbrother
* @subpackage controllers
*/
class BigBrotherDefaultManagerController extends BigBrotherManagerController {

    public function process(array $scriptProperties = array()) {}
    public function getPageTitle() { return $this->modx->lexicon('bigbrother'); }
    public function loadCustomCssJs() {
        $oauth_token = $this->bigbrother->getOption('refresh_token');
        $account = $this->bigbrother->getOption('account');
        
        if(empty($oauth_token)){
            $this->checkOauth();
        } elseif($account == null){
            $this->loadAuthCompletePanel();
        } else {
            $this->loadReportPanel();
        }
    }
    public function getTemplateFile() { return ''; }

    //
    public function checkOauth(){
        $code = isset($_GET['code']) ? $_GET['code'] : false;

        if (!empty($code)) {
            $client = $this->bigbrother->loadOAuth();
            // https://developers.google.com/identity/protocols/OAuth2InstalledApp
            $authParams = array(
                'code' => $code,
                'redirect_uri' => 'http://localhost/release-2.3/manager/?a=16',
                'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
                'grant_type' => 'authorization_code'
            );

            $result = false;
            try {
                $result = $client->getAccessToken($this->bigbrother->oauthTokenEndpoint, 'authorization_code', $authParams);
            } catch (Exception $e) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Exception during getAccessToken: ' . $e->getMessage());
            }

            if (is_array($result) && $result['code'] == 200) {
                $accessToken = $result['result']['access_token'];
                $refreshToken = $result['result']['refresh_token'];
                $expiresIn = $result['result']['expires_in'];

                $this->modx->getCacheManager()->set('access_token', $accessToken, $expiresIn, $this->bigbrother->cacheOptions);
                $this->bigbrother->updateOption('refresh_token', $refreshToken, 'text-password');

                $this->loadAuthCompletePanel();
                return;
            }

            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Unable to complete oAuth2 flow: ' . print_r($result, true));

        }

        //Authorize process
        $this->addJavascript($this->bigbrother->config['assets_url'] . 'mgr/authenticate/panel.js');
        $this->addHtml('<script type="text/javascript">
            MODx.BigBrotherConnectorUrl = "'.$this->bigbrother->config['connector_url'].'";
            Ext.onReady(function(){ MODx.add("bb-authorize-panel"); });
        </script>');
    }
    
    public function loadAuthCompletePanel(){
        $oauth = ( isset($_REQUEST['oauth_verifier'])) ? 'MODx.OAuthVerifier = "'. $_REQUEST['oauth_verifier'] .'";' : null;
        $oauth .= ( isset($_REQUEST['code'])) ? ' MODx.OAuthToken = "'. $_REQUEST['code'] .'";' : null;
        
        $this->addJavascript($this->bigbrother->config['assets_url'] . 'mgr/authenticate/authcomplete.js');
        /** @var $page modAction */
        $page = $this->modx->getObject('modAction', array(
            'namespace' => 'bigbrother',
            'controller' => 'index',
        ));

        $data = $this->modx->toJSON(array(
            'text' => $this->modx->lexicon('bigbrother.authentification_complete'),
            'trail' => array(
                array(
                    'text' => $this->modx->lexicon('bigbrother.bd_authorize')
                ),
                array(
                    'text' => $this->modx->lexicon('bigbrother.bd_choose_an_account')
                ),
            )
        ));

        $url = $this->bigbrother->getManagerLink() . '?a='. $page->get('id');
        $this->addHtml('<script type="text/javascript">
            MODx.BigBrotherRedirect = "'.$url.'";
            MODx.BigBrotherConnectorUrl = "'.$this->bigbrother->config['connector_url'].'"; '. $oauth .'
            MODx.BigBrotherAuthCompleteData = ' . $data . ';
            Ext.onReady(function(){ MODx.add("bb-authcomplete"); });
        </script>');
    }
    
    public function loadReportPanel(){
        //jQuery + charts class
        $this->addJavascript($this->bigbrother->config['assets_url'] . 'mgr/lib/jquery.min.js');
        $this->addJavascript($this->bigbrother->config['assets_url'] . 'mgr/lib/highcharts.js');
        
        //Basic reusable panels
        $this->addJavascript($this->bigbrother->config['assets_url'] . 'mgr/lib/classes.js');
        $this->addJavascript($this->bigbrother->config['assets_url'] . 'mgr/lib/charts.js');
        
        //Main Panels
        $admin = "";        
        $groups = explode(',', $this->modx->getOption('bigbrother.admin_groups', null, 'Administrator'));
        
        //Load the option menu only for specified user group
        if($this->modx->user->isMember($groups)){
            $this->addJavascript($this->bigbrother->config['assets_url'] . 'mgr/cmp/options.js');
            $admin .= 'var pnl = Ext.getCmp("bb-panel");
                Ext.getCmp("bb-panel").actionToolbar.add({
                    text: _("bigbrother.options")
                    ,id: "options-btn"
                    ,iconCls: "icon-options"
                    ,handler: function(b){ this.showOptionsPanel(); b.disable(); }
                });';
        
            $admin .= 'pnl.actionToolbar.add({
                text: _("bigbrother.revoke_authorization")
                ,iconCls: "icon-delete"
                ,handler: function(me){ this.revokeAuthorizationPromptWindow(me) }
            });
            pnl.actionToolbar.doLayout();';
            $admin .= '';
        }
        $this->addJavascript($this->bigbrother->config['assets_url'] . 'mgr/cmp/content.js');    
        $this->addJavascript($this->bigbrother->config['assets_url'] . 'mgr/cmp/audience.js');    
        $this->addJavascript($this->bigbrother->config['assets_url'] . 'mgr/cmp/traffic-sources.js');    
        $this->addJavascript($this->bigbrother->config['assets_url'] . 'mgr/cmp/container.js');
        /** @var $page modAction */
        $page = $this->modx->getObject('modAction', array(
            'namespace' => 'bigbrother',
            'controller' => 'index',
        ));    
        $date = $this->bigbrother->getDates('d M Y');

        $url = $this->bigbrother->getManagerLink() . '?a='. $page->get('id');
        $this->addHtml('<script type="text/javascript">            
            BigBrother.RedirectUrl = "'.$url.'";
            BigBrother.ConnectorUrl = "'.$this->bigbrother->config['connector_url'].'";
            BigBrother.DateBegin = "'.$date['begin'].'";
            BigBrother.DateEnd = "'.$date['end'].'";
            BigBrother.account = "'.$this->bigbrother->getOption('account').'";
            BigBrother.accountName = "'.$this->bigbrother->getOption('account_name').'";
            Ext.onReady(function(){ MODx.add("bb-panel"); '. $admin .' });            
        </script>');
    }
}