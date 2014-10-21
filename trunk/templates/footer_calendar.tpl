
<div style="clear: both;"></div>
<div id="scroll">
    <a title="Scroll to the top" class="top" href="#"><img src="{$url->url_base}include/images/top.png" alt="top" /></a>
</div>
<footer>
    <!--<p><img src="{$url->url_base}include/images/twitter.png" alt="twitter" />&nbsp;<img src="{$url->url_base}include/images/facebook.png" alt="facebook" />&nbsp;<img src="{$url->url_base}include/images/rss.png" alt="rss" /></p>-->
    <!--<p><a href="index.php">ホーム</a> | <a href="user_account.php">登録完了致しました</a> | <a href="report.php">日計表</a> | <a href="contact.php">各業者間サイト</a></p>-->
    <p>Copyright &copy; Ambition</p>
</footer>
</div>
{literal}
<script type="text/javascript">
    $(document).ready(function() {
            $('ul.sf-menu').sooperfish();
            $('.top').click(function() {$('html, body').animate({scrollTop:0}, 'fast'); return false;}
        );
        });
</script>
{/literal}
</body>
</html>