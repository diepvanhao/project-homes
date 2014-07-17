
<!DOCTYPE HTML>
<html>

    <head>
        <title>ARP</title>
        <meta name="description" content="website description" />
        <meta name="keywords" content="website keywords, website keywords" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="{$url->url_base}/include/css/style.css" />
        <link rel="stylesheet" type="text/css" href="{$url->url_base}/include/css/jquery-ui.css" />
        {* <link rel="stylesheet" type="text/css" href="{$url->url_base}/include/css/demos.css" />*}
        <script type="text/javascript" src="{$url->url_base}include/js/home.js"></script>

        <!-- javascript at the bottom for fast page loading -->
        <script type="text/javascript" src="{$url->url_base}include/js/jquery.js"></script>
        <script type="text/javascript" src="{$url->url_base}include/js/jquery.easing-sooper.js"></script>
        <script type="text/javascript" src="{$url->url_base}include/js/jquery.sooperfish.js"></script>

        <script type="text/javascript" src="{$url->url_base}include/js/jquery.ui.datepicker.js"></script>
        <script type="text/javascript" src="{$url->url_base}include/js/plugins.js"></script>        
        <script type="text/javascript" src="{$url->url_base}include/js/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="{$url->url_base}include/js/jquery.ui.core.js"></script>
        <!-- modernizr enables HTML5 elements and feature detects -->          
        <script type="text/javascript" src="{$url->url_base}include/js/modernizr-1.5.min.js"></script>
        <!--calendar-->
        
    </head>

    <body>
        <div id="main">
            <header>
                <div id="logo">
                    <div id="logo_text">
                        <!-- class="logo_colour", allows you to change the colour of the text -->
                        <h1><a href="{$url->url_base}"><span class="logo_colour"><img src="{$url->url_base}include/images/logo.png" alt="AMBITION" width=""height="133px;"/></span></a></h1>
                                    {*<h2>Simple. Contemporary. Website Template.</h2>*}
                    </div>
                </div>
                <nav>
                    <div id="menu_container">
                        <ul class="sf-menu" id="nav">
                            <li><a href="index.php">ホーム</a></li>
                            <li><a href="user_account.php">サインアップ</a></li>
                            <li><a href="#">クリエート</a>
                                <ul>
                                    <li><a href="create_order.php">オーダー</a></li>
                                    <li><a href="create_agent.php">店舗</a></li>
                                    <li><a href="create_house.php">物件情報</a></li>
                                    <li><a href="create_source.php">媒体</a></li>
                                    <li><a href="create_broker_company.php">管理会社</a></li>                                   
                                    <li><a href="create_client.php">お客情報</a></li>    
                                    <li><a href="create_room.php">部屋情報</a></li>    
                                </ul>
                            </li>
                            <li><a href="#">管理</a>
                                <ul> 
                                    <li><a href="manage_order.php">オーダー</a> </li>
                                    <li><a href="manage_client.php">お客情報</a></li>
                                    <li><a href="manage_account.php">アカウント</a></li>                                    
                                    <li><a href="manage_agent.php">店舗</a></li>
                                    <li><a href="manage_house.php">物件情報</a></li>
                                    <li><a href="manage_source.php">媒体</a></li>
                                    <li><a href="manage_room.php">部屋情報</a></li>
                                    <li><a href="manage_broker.php">管理会社</a></li>                                    
                                </ul>
                            </li>
                            <li><a href="#">日計表</a>
                                <ul>
                                    <li><a href="report.php">店舗 日計表</a></li>
                                    <li><a href="company_report.php">会社名 日計表</a></li>
                                </ul>
                            </li>
                            <li><a href="#">インポート CSV</a>
                                <ul>
                                    <li><a href="import.php">インポート 物件情報</a></li>
                                    <li><a href="import_address.php">インポート 住所</a></li>
                                </ul>
                            </li>
                            <li><a href="#">連絡先</a></li> 
                            <li><a href="#">Schedule</a>
                                <ul>
                                    <li><a href="google_calendar.php">Individual</a></li>
                                    <li><a href="general_calendar.php">General</a></li>                                                                                                            
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>

            <script type="text/javascript">
                var j = jQuery.noConflict();
                j(document).ready(function() {
                    datepicker();
                });
            </script>