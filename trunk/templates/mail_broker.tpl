<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>MAIL</h3>
    </div>
    {if count($error)}
        {foreach from=$error item=val}
            <div class="error">{$val}</div>
        {/foreach}
    {/if}
    <div class="report-filter">
        <form method="POST" enctype="multipart/form-data">
            <table cellpadding="0" cellspacing="0" style="margin-left: 0px;" width="100%">      
                <tbody>
                    <tr>
                        <td class='form1'>From: <span class="required">*</span></td>
                        <td class='form2'><input type='text' class='text' name='from' id='from' value="{$from}"  style="height:26px; width: 351px;"><div id="from_error"class="error"></div></td>
                    </tr>
                    <tr>
                        <td class='form1'>To: <span class="required">*</span></td>
                        <td class='form2'><input type='text' class='text' name='to' id='to' value="{$to}"  style="height:26px; width: 351px;"><div id="to_error"class="error"></div></td>
                    </tr>
                    <tr>
                        <td class='form1'>Subject: <span class="required">*</span></td>
                        <td class='form2'><input type='subject' class='text' name='subject' id='subject' value="{$subject}"  style="height:26px; width: 351px;"><div id="subject_error"class="error"></div></td>
                    </tr>
                    <tr>
                        <td class='form1'>Body: <span class="required">*</span></td>
                        <td class='form2'><textarea  name='body' id='body' value="{$body}"  style="min-height:250px; width: 351px;">{$body}</textarea><div id="body_error"class="error"></div></td>
                    </tr>
                    <tr>
                        <td class="form1">&nbsp;</td>
                        <td class="form2">
                            <div style="margin-top:10px">
                                <input type="submit" class="btn-signup" value="SEND" id="submit" name="submit" style="width: 100px;" onclick="showloadgif()">&nbsp;  
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
{include file="footer.tpl"}