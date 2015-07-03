<?php

class GConnect extends CWidget {

    public $gClientId;
    public $gLoginButtonId = "glogin";
    public $gLoginUrl = "google";
    private $_csrfToken;

    public function init() {
        if(app()->user->isGuest()){
            if(!isset($this->_csrfToken))
                $this->_csrfToken = sha1(uniqid(mt_rand(), true));
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'type' => 'danger',
                'label' => 'Login with Google',
                'icon' => 'icon-google-plus',
                'loadingText' => '<i class="icon-spinner icon-spin"></i> Login with Google',
                'htmlOptions' => array('id' => 'google-login'),
            ));
        }
    }

    public function run() {
        if(app()->user->isGuest()){
            cs()->registerScriptFile('https://plus.google.com/js/client:plusone.js', CClientScript::POS_HEAD);
            $this->gLoginUrl = url($this->gLoginUrl);
            $this->gClientId = app()->google->clientId;
            app()->session['google-state'] = $this->_csrfToken;
            $this->renderJavascript();
        }
    }

    private function renderJavascript() {
        $script = <<<EOD
        (function render() {
            gapi.signin.render('google-login', {
                'callback': 'googleCallback',
                'clientid': '{$this->gClientId}',
                'cookiepolicy': 'single_host_origin',
                'requestvisibleactions': 'http://schemas.google.com/AddActivity',
                'scope': 'https://www.googleapis.com/auth/userinfo.email'
            });
        })();
        $('#google-login').click(function() {
            $(this).button('loading');
        });
        var googleCallback = function(authResult) {
            if(authResult['g-oauth-window']){
                if(authResult['code']) {
                    $('#processing').modal({show: true, backdrop: 'static', keyboard: false});
                    gapi.client.load('plus','v1',getId);
                } else {
                    $('#google-login').button('reset');
                }
            }
            function getId() {
                var request = gapi.client.plus.people.get( {'userId' : 'me'} );
                request.execute(function(profile) {
                    $.ajax({
                        url: '{$this->gLoginUrl}?state={$this->_csrfToken}&gplus_id=' + profile.id
                        , type : 'post'
                        , dataType: 'json'
                        , success: function(data){
                            if(data.error == 0){
                                window.location.href = data.success;
                            } else {
                                $('#processing').modal('hide');
                                showError(data.error);
                                $('#google-login').button('reset');
                            }
                        }
                        , data: { code: authResult.code }
                    });
                });
            }
        };
EOD;

        cs()->registerScript('google-connect', $script, CClientScript::POS_END);
    }

}