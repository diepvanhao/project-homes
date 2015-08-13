<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/style.min.css" />
{include file="header_global.tpl"}
<script type="text/javascript" src="{$url->url_base}include/js/jquery.bpopup.min.js"></script>
<div style="background-color: #F1F5FE; width: 100%;height:55px; text-align: center;font-size: 1.8em;line-height: 55px; margin: 2% 0% 2% 0%;">クライアント管理</div>
<center>
    <div style="width: 100%;">
        <div>
            <form action="manage_client.php" method="post">
                <table>
                    <tr>
                        {*<td  style='font-size: 13.33px;'>Search</td>*}
                        <td class="form2">
                            <input type="text" id="search" name="search" value="{$search}" placeholder="Type name to search" style="height:26px; width: 190px;"/>
                            <span>
                                <input type='submit' class='btn-search' value='Search' id="submit" name="submit"/>&nbsp;                     
                            </span>
                            <span>
                                <a href="create_client.php" style="text-decoration: none;margin-left: 100px;"><input type='button' class='btn-search' value='Client Registry' id="create_client" name="create_client"/></a>&nbsp;                     
                            </span>
                        </td>
                    </tr>
                </table>
            </form>   
        </div>
        <div>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>N0</th>
                        <th>Name</th>
                        <th>Birthday</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Income</th>
                        <th>Occupation</th>
                        <th>Company</th>
                        <th>Fax</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Reason change</th>
                        <th>Time change</th>
                        <th>Resident name</th>
                        <th>Resident phone</th>
                        <th>Rent</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$clients key=k item=client}
                        {assign var="link" value="edit&{$client.id}"}
                        {assign var="add" value="assign&{$client.id}"}
                        <tr>
                            <td>{$k+1}</td>
                            <td>{$client.client_name}</td>
                            <td>{$client.client_birthday}</td>
                            <td>{$client.client_address}</td>
                            <td>{$client.client_phone}</td>
                            <td>{$client.client_income}</td>
                            <td>{$client.client_occupation}</td>
                            <td>{$client.client_company}</td>
                            <td>{$client.client_fax}</td>
                            <td>{$client.client_gender}</td>
                            <td>{$client.client_email}</td>
                            <td>{$client.client_reason_change}</td>
                            <td>{$client.client_time_change}</td>
                            <td>{$client.client_resident_name}</td>
                            <td>{$client.client_resident_phone}</td>
                            <td>{$client.client_rent}</td>
                            <td>{$client.client_room_type_number}{$client.client_room_type}</td>
                            <td style="width:9%"nowrap>
                                {if $canEdit}
                                    <a href="edit_client.php?url={$link|base64_encode}">Edit</a>
                                    <a href="javascript:void" onclick="deleteItem({$client.id},{$client.client_lock})" style="margin: 0% 10% 0% 10%;">{if $client.client_lock eq 0}Lock{else}Unlock{/if}</a>
                                    <a href="javascript:void" class="send_email" style="margin: 0% 10% 0% 10%;">Send Email
                                        <input type="hidden" id="client_email" value="{$client.client_email}"/>
                                    </a>
                                    
                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <center>
            Page:
            {for $i=1 to $totalPage }
                {if $i eq $page_number}<span style="margin-left: 10px; color: red;">[{$i}]</span>{else}<a href="manage_house.php?search={$search}&page_number={$i}" style='margin-left: 10px;color: black;'>{$i}{/if}</a>
            {/for}
        </center>
    </div>
</center>
<div id="popup" style="left: 710px; position: absolute; top: 127px; z-index: 9999; opacity: 1; display: none;">
    <span class="button b-close"><span>X</span></span>
    <center id="popup_content"></center>
</div>
{literal}
    <script type="text/javascript">
        function deleteItem(id, client_lock) {
            if (confirm("Are you sure?")) {
                $.post("include/function_ajax.php", {id: id, client_lock: client_lock, action: 'deleteClient'},
                function(result) {
                    if (result)
                        window.location.reload(true);
                    else
                        alert('Delete fail :(');
                });
            }
        }
 
        (function($) {
            $(function() {
                
                $('.send_email').bind('click', function(e) {
                    var client_email=$(this).parent().find('#client_email').val();
                    e.preventDefault();
//                        $.get('popup_create_house.php', function(result){
//                            document.getElementById('popup_content').innerHTML = result;
//                            eval($(result)[1].innerHTML);
//                        }, 'html');
                    document.getElementById('popup_content').innerHTML = '';
                    popup = $('#popup').bPopup({
                        contentContainer: '#popup_content',
                        loadUrl: 'popup_send_email.php?client_email='+client_email //Uses jQuery.load()
                    });

                });
               
            });
        })(jQuery);
    </script>

{/literal}

{include file="footer.tpl"}