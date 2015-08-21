
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
        <script type="text/javascript" src="{$url->url_base}include/js/jquery.ui.datepicker-ja.js"></script>

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
                            <li><a href="index.php">Home</a></li>
                                <li><a href="user_account.php">Registry</a></li>
                            {*<li><a href="#">Create</a>
                                <ul>
                                    <li><a href="create_order.php">Order</a></li>
                                        <li><a href="create_agent.php">Agent</a></li>
                                    <li><a href="create_house.php">Apartment</a></li>
                                    <li><a href="create_source.php">Source</a></li>
                                    <li><a href="create_broker_company.php">Broker Company</a></li>                                   
                                    <li><a href="create_client.php">Customer</a></li>    
                                    <li><a href="create_room.php">Room</a></li>  
                                        <li><a href="create_group.php">Group</a></li>
                                </ul>
                            </li>*}
                            <li><a href="#">Manage</a>
                                <ul> 
                                    <li><a href="manage_order.php">Order</a> </li>
                                    <li><a href="manage_client.php">Customer</a></li>
                                        <li><a href="manage_account.php">Account</a></li>    
                                        <li><a href="manage_agent.php">Agent</a></li>
                                    <li><a href="manage_house.php">Apartment</a></li>
                                    <li><a href="manage_source.php">Source</a></li>
                                    <li><a href="manage_room.php">Room</a></li>
                                    <li><a href="manage_broker.php">Broker Company</a></li> 
                                        <li><a href="manage_group.php">Group</a></li> 
                                </ul>
                            </li>
                            <li><a href="#">Report</a>
                                <ul>
                                    <li><a href="report.php">Daily Report</a></li>
                                    <li><a href="company_report.php">Agent Report</a></li>
                                    <li><a href="group_report.php">Group Report</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Import CSV</a>
                                <ul>
                                    <li><a href="import.php">House</a></li>
                                    <li><a href="import_address.php">Address</a></li>
                                </ul>
                            </li>
                            <li><a href="contact.php">Contact</a></li> 
                            <li><a href="#">Schedules</a>
                                <ul>
                                    <li><a href="google_calendar.php">Private</a></li>
                                    <li><a href="general_calendar.php">Agent</a></li>
                                    <li><a href="fetch_email.php">Get Messages</a></li>
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
