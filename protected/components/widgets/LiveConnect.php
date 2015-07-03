<?php

class LiveConnect extends CWidget {

    public $callback;
    public $liveLoginUrl = "live";
    private $_csrfToken;

    public function init() {
        if(app()->user->isGuest()){
            if(!isset($this->_csrfToken))
                $this->_csrfToken = sha1(uniqid(mt_rand(), true));
            echo CHtml::link('Windows Live', null, array('id'=>'live-login'));
        }
    }

    public function run() {
        if(app()->user->isGuest()){
            cs()->registerScriptFile('//js.live.net/v5.0/wl.js', CClientScript::POS_HEAD);
            $this->callback = app()->getAssetManager()->publish(Yii::getPathOfAlias('application.components.widgets.LiveConnect'), false, 1);
            $this->liveLoginUrl = url($this->liveLoginUrl);
            app()->session['live-state'] = $this->_csrfToken;
            $this->renderJavascript();
        }
    }

    private function renderJavascript() {
        $script = <<<EOD
        WL.Event.subscribe('auth.statusChange', liveButton);
        WL.init({ client_id: 'WINDOWS_LIVE_ID' , redirect_uri: "{$this->callback}/callback.php" , scope: ["wl.signin", "wl.emails"] });

        function liveButton(response) {
            var l = document.getElementById("live-login");
            l.onclick = function(){
                WL.login().then(
                    function(response) {
                        WL.api({path: "me", method: "GET"}).then(
                            function(user) {
                                $.ajax({ type : 'post'
                                    , url: '{$this->liveLoginUrl}?state={$this->_csrfToken}'
                                    , data: ({ user: user })
                                    , dataType: 'json'
                                    , success: function(data){
                                        if(data.error == 0){
                                            window.location.href = data.success;
                                        } else {
                                            showError(data.error);
                                        }
                                    }
                                });
                            }, function(responseFailed) {
                                // failed to get users data from windows live
                            }
                        );
                    }
                );
            }
        }

        WL.getLoginStatus(liveButton);
EOD;

        cs()->registerScript('live-connect', $script, CClientScript::POS_BEGIN);
    }

}