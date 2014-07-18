<link rel="stylesheet" type="text/css" href="{$url->url_base}include/css/report.css" />
{include file="header_global.tpl"}
<div id="site_content" class="report-content">
    <div class="report-title">
        <h3>住所をインポートします</h3>
    </div>
    <div class="report-filter">
        <form method="POST" enctype="multipart/form-data">
            <table cellpadding="0" cellspacing="0" style="margin-left: 0px;" width="100%">      
                <tbody>
                    <tr>
                        <td class="form1">
                            CSVファイルを選択してください。:
                        </td>
                        <td class="form2">
                            <input type="file" name="csv"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="form1">&nbsp;</td>
                        <td class="form2">
                            <div style="margin-top:10px">
                                <input type="submit" class="btn-signup" value="インポート" id="submit" name="submit" style="width: 100px;" onclick="showloadgif()">&nbsp;  
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
{include file="footer.tpl"}