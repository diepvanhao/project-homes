{include file='header.tpl'}
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; ">Create Order</div>
{literal}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#search').keyup(function(e) {
                var search = $('#search').val();
                $.post("include/function_ajax.php", {search: search, action: 'create_order', task: 'getHouseSearch'},
                function(result) {
                    $('#house_id').empty();
                    $('#house_id').html(result);
                    $('#step').click();
                });
            });
            $('#step').click(function() {
                var house_id = $('#house_id').val();
                $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                function(result) {
                    $('#house_description').html(result);
                });
            });
            $('#house_id').change(function() {
                var house_id = $('#house_id').val();
                $.post('include/function_ajax.php', {house_id: house_id, action: 'create_order', task: 'getContentHouse'},
                function(result) {
                    $('#house_description').html(result);
                });
            });
            $('#back').click(function() {
                var broker_id = $('#broker_id').val();
                window.location.href = "create_order.php?step=1&broker_id=" + broker_id;
            });
        });
    </script>
{/literal}
{if $error|@count gt 0}
    {foreach from=$error item=val}
        <div class="error">{$val}</div>
    {/foreach}
{/if}
{*step1   *}
{if $step eq 1}
    <form action="create_order.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">      
            <tr>
                <td class="form1">
                    Select Sources:
                </td>
                <td class="form2">
                    <select id="broker_id" name="broker_id" style="height:26px; width: 251px;">
                        <option value=""></option>
                        {foreach from=$brokers key=k item=val}
                            <option value="{$val.id}"{if $val.id eq $broker_id}selected{/if} >{$val.broker_company_name}</option>                  
                        {/foreach}                                                   
                    </select>
                </td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-signup' value='Next' id="submit" name="submit" style="width: 100px;"onclick="showloadgif()"/>&nbsp;  
                        <input type="hidden" id="step" name="step" value="2"/>                        
                    </div>
                </td>
            </tr>
        </table>
    </form>
{/if}
{*step2*}                       
{if $step eq 2}    

    <form action="create_order.php" method="post">
        <div class="title"><label >Input house information</label></div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">      
            <tr>
                <td class="form1">
                    Assign
                </td>

                <td class='form2'>
                    <select id="staff_id" name="staff_id" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$users item=user}
                            <option value="{$user.id}">{$user.user_fname} {$user.user_lname}</option>        
                        {/foreach}
                    </select><span id="error_staff" class="error"></span>

                </td>
            </tr>
            <tr>
                <td class="form1">Filter House</td>
                <td class="form2"><input type="text" id="search" name="search" value="" placeholder="Enter house name to filter for selection house" style="height:26px; width: 351px;"/>

                </td>
            </tr>
            <tr>            
                <td class='form1'>Select House: </td>
                <td class='form2'>
                    <select id="house_id" name="house_id" style="height:26px; width: 351px;">
                        <option value=""></option>
                        {foreach from=$houses item=house}
                            <option value="{$house.id}">{$house.house_name}</option>        
                        {/foreach}
                    </select><span id="error_house" class="error"></span>
                </td>
            </tr>
            <tr>            
                <td class='form1'>Description House: </td>
                <td class='form2'><textarea style="width: 340px;height: 129px;" disabled="1" id="house_description"></textarea></td>
            </tr>
            <tr>            
                <td colspan="2"><div>If not house that you want. You can add new house by link <a href="./create_house.php">Create House</a></div></td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type='submit' class='btn-signup' value='Next' id="submit" name="submit" style="width: 100px;"/>&nbsp;  
                        <input type='button' class='btn-signup' value='Back' id="back" name="back" style="width: 100px;"/>&nbsp; 
                        <input type="hidden" id="step" name="step" value="verify"/>      
                        <input type="hidden" id="broker_id" name="broker_id" value="{$broker_id}"/>   
                    </div>
                </td>
            </tr>
        </table>
    </form>
    {literal}
        <script type="text/javascript">
            $(document).ready(function() {
                $('#submit').click(function(e) {
                    $('#error_staff').html("");
                    $('#error_house').html("");
                    var staff_id = $('#staff_id').val();
                    var house_id = $('#house_id').val();
                    if (staff_id == "") {
                        $('#error_staff').html('Please choose assign.');
                        e.preventDefault();
                        return false;
                    } else if (house_id == "") {
                        $('#error_house').html('Please choose house.');
                        e.preventDefault();
                        return false;
                    } else {
                        showloadgif()
                        $('#submit').submit();
                    }

                });
            });
        </script>
    {/literal}
{/if}

{*step verify*}
{if $step eq "verify"}
    <form action="create_order.php" method="post">
        <div class="title"><label ><h2>Verify information</h2></label></div>
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
            <tr>
                <td class='form1'>Source:</td>
                <td class='form2'>{$brokers.broker_company_name}</td>
            </tr>
            <tr>
                <td class='form1' nowrap>Staff assigned:</td>
                <td class='form2'> {$staffs.user_fname} {$staffs.user_lname}</td>
            </tr>
            <tr>
                <td class='form1' nowrap>House name:</td>
                <td class='form2'>{$houses.house_name}</td>
            </tr>
            <tr>
                <td class='form1'>Desctiption:</td>
                <td class='form2'>{$houses.house_description}</td>
            </tr>
            <tr>
                <td class='form1'>&nbsp;</td>
                <td class='form2'>
                    <div style="margin-top:10px">
                        <input type="submit" class='btn-signup' value="Registry" id="registry" name="registry" style="width: 100px;"/>&nbsp; 
                        <input type="button" class='btn-signup' value="Later" id="later" name="later"style="width: 100px;margin: 0px 10px 0px 10px;"/>
                        <input type="button" class='btn-signup' value="Cancel" id="cancel" name="cancel"style="width: 100px;"/>
                        <input type="hidden" id="create_id" name="create_id" value="{$staffs.id}"/>
                        <input type="hidden" id="house_id" name="house_id" value="{$houses.id}"/>    
                        <input type="hidden" id="broker_id" name="broker_id" value="{$brokers.id}"/>
                        <input type="hidden" id="step" name="step" value="registry"/>
                    </div>
                </td>
            </tr>
        </table>
    </form>
    {literal}
        <script type="text/javascript">
            $(document).ready(function() {
                $('#sidebar_container').css('display', 'none');
                $('#later').click(function() {
                    window.location.href = "create_order.php";
                });
            });
        </script>
    {/literal}
{/if}

{if $step eq "registry"}
    <form action="create_order.php" method="post">
        <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="60%">
            <tr>
                <td>Filter customer</td>
                <td><input type="text" id="filter" name="filter"value="{$filter}" style="height: 26px; width: 315px;" placeholder="Type name of customer"/>
                    <span>
                        <input type='submit' class='btn-search' value='Submit' id="submit" name="submit"/>&nbsp;                     
                    </span>
                </td>
            <input type="hidden" id="step" name="step" value="registry"/>
            </tr>
        </table>
    </form>
    <div style="margin-bottom:10px;">
        <center>
            Page:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="create_order.php?step='registry'&filter={$filter}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
    <div id="customer">
        <ul>
            <li class="even">Id</li>
            <li class="even">Name</li>
            <li class="even">Birthday</li>
            <li class="even">Address</li>
            <li class="even">Phone Number</li>
        </ul>

        {foreach from=$customers key=k item=item}
            <ul>
                <li {if $item@iteration is div by 2}class="odd"{/if}>{$item.id}</li>
                <li {if $item@iteration is div by 2}class="odd"{/if}>{$item.client_name}</li>
                <li {if $item@iteration is div by 2}class="odd"{/if}>{$item.client_birthday}</li>
                <li {if $item@iteration is div by 2}class="odd"{/if}>{$item.client_address}</li>
                <li {if $item@iteration is div by 2}class="odd"{/if}>{$item.client_phone}</li>
            </ul>
        {/foreach}

    </div>
    <div style="background-color: #F1F5FE; width: 100%;height:25px; text-align: center;font-size: 1.8em;line-height: 25px; margin-top: 50px; ">Customer Information</div>
    <div id="client_info">
        <ul>
            <li>Basic Info</li>
            <li>Detail</li>
            <li>History</li>
            <li>Aspirations</li>
            <li>Contract</li>
        </ul>
    </div>
    <div id="basic" style="float: left;width: 100%;display: none;">
        <form action="create_order.php" method="post">        
            <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                <tr>
                    <td class='form1'>Name:</td>
                    <td class='form2'><input type="text" id="client_name" name="client_name" style="height: 26px; width: 315px;"/></td>
                    <td class='form1' nowrap>Birthday:</td>
                    <td class='form2'> <input type='text' id="client_birthday" name="client_birthday" style="height: 26px; width: 315px;"/></td>
                </tr>
                <tr>
                    <td class='form1'>Email:</td>
                    <td class='form2'><input type="text" id="client_email" name="client_email" style="height: 26px; width: 315px;"/></td>
                    <td class='form1' nowrap>Fax:</td>
                    <td class='form2'> <input type='text' id="client_birthday" name="client_birthday" style="height: 26px; width: 315px;"/></td>
                </tr>
                <tr>
                    <td class='form1'>&nbsp;</td>
                    <td class='form2' colspan="3">
                        <div style="margin-top:10px;text-align: center;">
                            <input type="submit" class='btn-signup' value="Save" id="save" name="save" style="width: 100px;"/>&nbsp; 

                        </div>                        
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div id="detail" style="float: left;width: 100%;">
        <form action="create_order.php" method="post">        
            <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                <tr>
                    <td class='form1'>Gender: </td>
                    <td class='form2'>
                        <select id="gender"name="gender" style="height:26px; width: 315px;">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </td>
                    <td class='form1' nowrap>Address Current:</td>
                    <td class='form2'> <input type='text' id="client_address" name="client_address" style="height: 26px; width: 315px;"/></td>
                </tr>
                <tr>
                    <td class='form1'>Occupation:</td>
                    <td class='form2'><input type="text" id="client_occupation" name="client_occupation" style="height: 26px; width: 315px;"/></td>
                    <td class='form1' nowrap>Company:</td>
                    <td class='form2'> <input type='text' id="client_company" name="client_company" style="height: 26px; width: 315px;"/></td>
                </tr>
                <tr>
                    <td class='form1'>Income:</td>
                    <td class='form2'><input type="text" id="client_income" name="client_income" style="height: 26px; width: 315px;"/></td>
                    <td class='form1' nowrap>Room type:</td>
                    <td class='form2'> <input type='text' id="client_room_type" name="client_room_type" style="height: 26px; width: 315px;"/></td>
                </tr>
                <tr>
                    <td class='form1'>Rent current :</td>
                    <td class='form2'><input type="text" id="client_rent" name="client_rent" style="height: 26px; width: 315px;"/></td>
                    <td class='form1' nowrap>Reason change:</td>
                    <td class='form2'> <input type='text' id="client_reason_change" name="client_reason_change" style="height: 26px; width: 315px;"/></td>
                </tr>
                <tr>
                    <td class='form1'>Date change :</td>
                    <td class='form2'><input type="text" id="client_time_change" name="client_time_change" style="height: 26px; width: 315px;"/></td>
                    <td class='form1' nowrap></td>
                    <td class='form2'> </td>
                </tr>
                <tr>
                    <td class='form1'>&nbsp;</td>
                    <td class='form2' colspan="3">
                        <div style="margin-top:10px;text-align: center;">
                            <input type="submit" class='btn-signup' value="Save" id="save" name="save" style="width: 100px;"/>&nbsp; 

                        </div>                        
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div id="history" style="float: left;width: 100%;">
        <form action="create_order.php" method="post">        
            <table cellpadding='0' cellspacing='0' style='margin-left: 0px;' width="100%">
                <tr>
                    <td class='form1'>Gender: </td>
                    <td class='form2'>
                        <select id="gender"name="gender" style="height:26px; width: 315px;">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </td>
                    <td class='form1' nowrap>Address Current:</td>
                    <td class='form2'> <input type='text' id="client_address" name="client_address" style="height: 26px; width: 315px;"/></td>
                </tr>
                <tr>
                    <td class='form1'>Occupation:</td>
                    <td class='form2'><input type="text" id="client_occupation" name="client_occupation" style="height: 26px; width: 315px;"/></td>
                    <td class='form1' nowrap>Company:</td>
                    <td class='form2'> <input type='text' id="client_company" name="client_company" style="height: 26px; width: 315px;"/></td>
                </tr>
                <tr>
                    <td class='form1'>Income:</td>
                    <td class='form2'><input type="text" id="client_income" name="client_income" style="height: 26px; width: 315px;"/></td>
                    <td class='form1' nowrap>Room type:</td>
                    <td class='form2'> <input type='text' id="client_room_type" name="client_room_type" style="height: 26px; width: 315px;"/></td>
                </tr>
                <tr>
                    <td class='form1'>Rent current :</td>
                    <td class='form2'><input type="text" id="client_rent" name="client_rent" style="height: 26px; width: 315px;"/></td>
                    <td class='form1' nowrap>Reason change:</td>
                    <td class='form2'> <input type='text' id="client_reason_change" name="client_reason_change" style="height: 26px; width: 315px;"/></td>
                </tr>
                <tr>
                    <td class='form1'>Date change :</td>
                    <td class='form2'><input type="text" id="client_time_change" name="client_time_change" style="height: 26px; width: 315px;"/></td>
                    <td class='form1' nowrap></td>
                    <td class='form2'> </td>
                </tr>
                <tr>
                    <td class='form1'>&nbsp;</td>
                    <td class='form2' colspan="3">
                        <div style="margin-top:10px;text-align: center;">
                            <input type="submit" class='btn-signup' value="Save" id="save" name="save" style="width: 100px;"/>&nbsp; 

                        </div>                        
                    </td>
                </tr>
            </table>
        </form>
    </div>
    {literal}
        <style type="text/css">
            .content{
                width:100%;
                margin: 0;
                padding: 0;
            }

            #customer ul{
                width: 100%;
                border: 1px solid white;

            }

            #customer ul li{
                float: left;
                background: transparent;
                padding: 0px;
                width: 20%;
                cursor: pointer;
            }
            #customer ul li.odd{
                background: silver;
            }
            #customer ul li.even{
                background:#D9D5CF; 
                border-bottom: 1px solid #617AAC;
            }
            #customer ul li:first-child{
                margin-left: 0;
            }
            #customer ul li:last-child{
                float: right;
            }
            #customer{
                width: 100%;
            }
            /////////////////////////client info//////////////////////////
            #client_info{
                width: 100%;
            }
            #client_info ul li{
                float: left;
                background: beige;
                padding: 0px;
                width: 20%;
                cursor: pointer;
                text-align: center;
            }
            #client_info ul li:hover{

                background: url(include/images/bg-btn-forget.gif) repeat-x;

            }

        </style>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#sidebar_container').css('display', 'none');

            });
        </script>
    {/literal}

{/if}
<div id="loadgif">Loading...</div>



{include file='footer.tpl'}