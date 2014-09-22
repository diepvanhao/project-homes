<?php

class Autologin {

    var $brokers = array();

    function Autologin() {
        //init array
        $this->brokers = array(
            array('name' => "CIC", 'action' => "https://cic.bukkaku.jp/agent/login/login", 'username' => "06011x", 'password' => "uiuf", 'idlogname' => "account", 'passlogname' => "password", 'submitname' => ""),
            //array('name' => "ＦＪ", 'action' => "http://www.fjg.jp/", 'username' => "C370HL001", 'password' => "ms320", 'idlogname' => "", 'passlogname' => "", 'submitname' => ""),
            array('name' => "ＲＡアセット", 'action' => "http://www.ra-asset.co.jp/article/member/Login.php", 'username' => "iijima@roompia.jp", 'password' => "T4aGNVZ5", 'idlogname' => "UserId", 'passlogname' => "UserPw", 'submitname' => "LOGIN"),
            array('name' => "Rise", 'action' => "http://www.risecorporation.co.jp/class/user.php", 'username' => "ambition1", 'password' => "51146200", 'idlogname' => "uname", 'passlogname' => "pass", 'submitname' => "", 'inputhidden' => '<input id="legacy_xoopsform_op" type="hidden" value="login" name="op"><input id="legacy_xoopsform_xoops_redirect" type="hidden" value="/class/user.php" name="xoops_redirect">'),
            array('name' => "ＴＦＤ", 'action' => "https://tfd.es-b2b.com/?wicket:interface=:0:signInPanel:signInForm::IFormSubmitListener", 'username' => "19764RC", 'password' => "QU3F792MGQ", 'idlogname' => "username", 'passlogname' => "password"),
            //array('name' => "アートアベニュー", 'action' => "http://www.artavenue.co.jp/artproperty/", 'username' => "artp", 'password' => "5010", 'idlogname' => "", 'passlogname' => ""),
            //array('name' => "青山メイン企画", 'action' => "http://www.amknet.jp/", 'username' => "4871", 'password' => "4871", 'idlogname' => "", 'passlogname' => ""),
            //array('name' => "青山メインランド", 'action' => "https://www.c-estate.com/gyosya_hp/oem/main/index.asp?g=113083", 'username' => "amib", 'password' => "6686", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "アソシア・プロパティ", 'action' => "https://www.associa-p.co.jp/member/login_exe.php", 'username' => "aki", 'password' => "35686506", 'idlogname' => "id", 'passlogname' => "password"),
            array('name' => "アムス", 'action' => "http://map.cyber-estate.jp/mediation/login.asp?ggid=113055", 'username' => "5114620000", 'password' => "8733161639", 'idlogname' => "txtLoginId", 'passlogname' => "txtLoginPass"),
            //array('name' => "ウェル・エステート", 'action' => "http://www.wellestate.com/kanri/index.html", 'username' => "", 'password' => "", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "エイブル保証", 'action' => "http://heya.a-hosho.co.jp/", 'username' => "1000", 'password' => "8126", 'idlogname' => "login_id", 'passlogname' => "password"),
           // array('name' => "エステートナビ", 'action' => "http://www.room-get.jp/submit/", 'username' => "aebx2", 'password' => "9999", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "エスフィット", 'action' => "https://sfit-pm.es-b2b.com/?wicket:interface=:0:signInPanel:signInForm::IFormSubmitListener", 'username' => "41BC47Z", 'password' => "7E4T9D2MGR", 'idlogname' => "username", 'passlogname' => "password"),
            array('name' => "エムズコミュニケーション", 'action' => "http://www.c-estate.com/gyosya_hp/oem/main/login.asp?g=113121", 'username' => "00564", 'password' => "6200", 'idlogname' => "txtLoginId", 'passlogname' => "txtLoginPass"),
            array('name' => "グランヴァン", 'action' => "https://grandvan.bukkaku.jp/agent/login/login", 'username' => "abikebukuro", 'password' => "6686", 'idlogname' => "account", 'passlogname' => "password"),
           // array('name' => "クレアスレント", 'action' => "http://www.clearth-rent.co.jp", 'username' => "iijima@roompia.jp", 'password' => "hXbu8R", 'idlogname' => "", 'passlogname' => ""),
           // array('name' => "国土信和", 'action' => "http://www.kokudoshinwa.co.jp/list/view.cgi", 'username' => "N0906190126", 'password' => "883794", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "シノケン・ファシリティーズ", 'action' => "http://map.cyber-estate.jp/mediation/login.asp?ggid=140042", 'username' => "1409565838", 'password' => "1409565838", 'idlogname' => "txtLoginId", 'passlogname' => "txtLoginPass"),
           // array('name' => "シマダハウス", 'action' => "http://www.shimadahouse.co.jp", 'username' => "iijima@roompia.jp", 'password' => "IZ8TOB", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "ジョイントプロパティ", 'action' => "https://joint-property.bukkaku.jp/agent/login/login", 'username' => "02137r", 'password' => "y74r", 'idlogname' => "account", 'passlogname' => "password"),
           // array('name' => "スカイコート", 'action' => "https://skycourt.es-b2b.com/?wicket:interface=:0:signInPanel:signInForm::IFormSubmitListener", 'username' => "29494XS", 'password' => "Z84FR34MGT", 'idlogname' => "username", 'passlogname' => "password"),
            array('name' => "ステージプランナー", 'action' => "https://stageplan.es-b2b.com/?wicket:interface=:0:signInPanel:signInForm::IFormSubmitListener", 'username' => "38KR3HQ", 'password' => "FK4ZYL2MGQ", 'idlogname' => "username", 'passlogname' => "password"),
           // array('name' => "生和コーポレーション", 'action' => "http://www.seiwa-dss.net/seiwa_net/index.php", 'username' => "iijima@roompia.jp", 'password' => "ambad001", 'idlogname' => "", 'passlogname' => ""),
           // array('name' => "セレコーポレーション", 'action' => "http://www.cel-co.com/index_c.html", 'username' => "cel", 'password' => "a3085", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "大京", 'action' => "https://rent.daikyo-realdo.co.jp/B2B/login.asp", 'username' => "k-sato@roompia.jp", 'password' => "ueno6686", 'idlogname' => "id", 'passlogname' => "pass"),
            array('name' => "タイセイハウジー", 'action' => "http://www.newcityrent.com/rentapp/brokerLogin.do;jsessionid=7f00000130d5a98de3c156bb4a678b21e260e8e6ca1a.e38Lb3eNbheMb40TahyQbNuQaNj0", 'username' => "h-sato@am-bition.jp", 'password' => "8077", 'idlogname' => "userId", 'passlogname' => "password"),
            array('name' => "トーシンコミュニティー", 'action' => "http://www.toshin-rent.com/bb/login/login.jsp", 'username' => "rent", 'password' => "9100", 'idlogname' => "account", 'passlogname' => "password",'inputhidden' =>'<input type="hidden" name="submit.x" value="0" /><input type="hidden" name="submit.y" value="0" />'),
            array('name' => "トラストアドバイザーズ", 'action' => "https://trust-advisers.es-b2b.com/?wicket:interface=:0:signInPanel:signInForm::IFormSubmitListener", 'username' => "40CE498", 'password' => "Q343ZP2MGQ", 'idlogname' => "username", 'passlogname' => "password"),
            array('name' => "パシフィック・ディベロップメント", 'action' => "https://pdm.es-b2b.com/?wicket:interface=:0:signInPanel:signInForm::IFormSubmitListener", 'username' => "32HD43L", 'password' => "EB4Z973MGJ", 'idlogname' => "username", 'passlogname' => "password"),
            array('name' => "早成設計", 'action' => "http://goinghome.nostrum.co.jp/pm-lm/lm/login", 'username' => "g34646100", 'password' => "g34646336", 'idlogname' => "data[Lm][login]", 'passlogname' => "data[Lm][password]"),
            array('name' => "ヒロ・コーポレーション", 'action' => "https://hiro-corporation.es-b2b.com/?wicket:interface=:0:signInPanel:signInForm::IFormSubmitListener", 'username' => "47J74P9", 'password' => "43434E3MGJ", 'idlogname' => "username", 'passlogname' => "password"),
            //array('name' => "プリズミック", 'action' => "http://www.prismic-pro.com/prism2i/", 'username' => "", 'password' => "", 'idlogname' => "", 'passlogname' => ""),
           // array('name' => "プロパティーエージェント", 'action' => "http://pa-pmg.jp/", 'username' => "aranciacracia", 'password' => "pa1874", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "ベストプロパティ", 'action' => "http://bestproperty.co.jp/apply/login.html", 'username' => "ooishi@roompia.jp", 'password' => "ro6550", 'idlogname' => "id", 'passlogname' => "password",'inputhidden'=>'<input type="hidden" name="mode" value="login" />'),
            //array('name' => "ホーミングライフ", 'action' => "http://www.homing.co.jp/chukai/index.html", 'username' => "hl", 'password' => "421967", 'idlogname' => "", 'passlogname' => ""),
            //array('name' => "明豊プロパティーズ", 'action' => "http://area.meiho-prop.jp/search/", 'username' => "", 'password' => "", 'idlogname' => "", 'passlogname' => ""),
           // array('name' => "モリモト", 'action' => "http://morimoto.es-b2b.com/signIn;jsessionid=h2juO+EeHRs7fJ9bGvA7aQ**.aquarium-1", 'username' => "316R3XH", 'password' => "344XQ34MGP", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "ライフギャラリー", 'action' => "http://www.hkg.co.jp/lifebb/", 'username' => "sakamoto@am-bition.jp", 'password' => "874o33nb", 'idlogname' => "username", 'passlogname' => "password"),
            array('name' => "リアルワン", 'action' => "https://realone.bukkaku.jp/agent/login/login", 'username' => "05339u", 'password' => "3duf", 'idlogname' => "account", 'passlogname' => "password"),
            array('name' => "リオソリューション", 'action' => "https://rio.bukkaku.jp/agent/login/login", 'username' => "364K4M6", 'password' => "KW3P655MGS", 'idlogname' => "account", 'passlogname' => "password"),
          //  array('name' => "リブ・マックス", 'action' => "http://chuukai.good-chintai.net/#", 'username' => "chintai", 'password' => "goodcom", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "レオパレス", 'action' => "https://www.leopalace21.com/apps/tradeCondition/logonAction.do", 'username' => "okamura@am-bition.jp", 'password' => "6200", 'idlogname' => "id", 'passlogname' => "password",'inputhidden'=>'<input type="hidden" name="actionType" value="LOGON" /><input type="hidden" name="memory" value="true" />'),
            array('name' => "伊藤忠", 'action' => "https://itc-uc.es-b2b.com/signIn", 'username' => "52T84BB", 'password' => "444KR23MGP", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "環境ステーション", 'action' => "https://kankyo-station.bukkaku.jp/agent/login/login", 'username' => "05142s", 'password' => "902n", 'idlogname' => "account", 'passlogname' => "password"),
            array('name' => "三井不動産", 'action' => "http://mfhl.mitsui-chintai.co.jp/partners/A0509Login.do", 'username' => "toudou@am-bition.jp", 'password' => "YB4XfgtnwY", 'idlogname' => "input.MAIL_1", 'passlogname' => "input.PWD",'inputhidden'=>'<input type="hidden" name="MODE" value="login" />'),
            array('name' => "三菱地所", 'action' => "http://www.mec-h.jp/bb/login.do", 'username' => "toudou@am-bition.jp", 'password' => "19824111", 'idlogname' => "userid", 'passlogname' => "password",'inputhidden'=>'<input type="hidden" name="command" value="login" />'),
            array('name' => "綜合地所", 'action' => "http://bbhp.rentersnet.jp/login/login/ccd/142", 'username' => "y-saitou@roompia.jp", 'password' => "kniWov", 'idlogname' => "uid", 'passlogname' => "pass",'inputhidden'=>'<input type="hidden" name="exec" value="login" />'),
            array('name' => "長谷工ライブネット", 'action' => "http://www.haseko-hln.co.jp/g_halo/bin/login.asp", 'username' => "5901", 'password' => "51146200", 'idlogname' => "UID", 'passlogname' => "PWD",'inputhidden'=>'<input type="hidden" name="THFLG" value="10" />'),
            array('name' => "東京建物", 'action' => "https://ttf.es-b2b.com/?wicket:interface=:0:signInPanel:signInForm::IFormSubmitListener", 'username' => "4S84TY", 'password' => "TF4FCC2MGP", 'idlogname' => "username", 'passlogname' => "password"),
            //array('name' => "日神住宅", 'action' => "http://bbhp.rentersnet.jp/?ccd=140", 'username' => "iijima@roompia.jp", 'password' => "6TPIns", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "日本財宅管理サービス", 'action' => "http://www.zaitakukanri.co.jp/index.php/auth/login", 'username' => "chukai", 'password' => "dptf8f42", 'idlogname' => "username", 'passlogname' => "password"),
           // array('name' => "明和管財", 'action' => "http://www.meiwakanzai.co.jp/chukai.html", 'username' => "516E4PJ", 'password' => "D647Z92MGN", 'idlogname' => "", 'passlogname' => ""),
            array('name' => "木下の賃貸", 'action' => "http://map.cyber-estate.jp/mediation/login.asp?ggid=813013", 'username' => "0101018", 'password' => "6100", 'idlogname' => "txtLoginId", 'passlogname' => "txtLoginPass"),
            array('name' => "東急リロケーション", 'action' => "https://www.tokyu-relocation.co.jp/agency/login/create", 'username' => "ueno_info@roompia.jp", 'password' => "ee6bd9b9", 'idlogname' => "user", 'passlogname' => "password"),
            //array('name' => "at bb", 'action' => "https://atbb.athome.jp/index.html", 'username' => "1023260002", 'password' => "ambad8511", 'idlogname' => "", 'passlogname' => "")
        );
    }
    function getBrokerLogin($broker_name=""){
        for($i=0;$i<count($this->brokers);$i++){
            if(trim($broker_name)==trim($this->brokers[$i]['name'])){
                return $this->brokers[$i];
            }
        }
        return "";
    }
    function getBrokerLoginContact(){
        return $this->brokers;
    }

}

?>