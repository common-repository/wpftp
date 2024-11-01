<?php

/**
 *  插件设置页面
 */
function wpftp_setting_page()
{
    // 如果当前用户权限不足
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient privileges!');
    }

    $wpftp_options = get_option('wpftp_options');
    if ($wpftp_options && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) && !empty($_POST)) {
        if ($_POST['type'] == 'cos_info_set') {

            $wpftp_options['no_local_file'] = (isset($_POST['no_local_file'])) ? True : False;
            $wpftp_options['ftp_server'] = (isset($_POST['ftp_server'])) ? sanitize_text_field(trim(stripslashes($_POST['ftp_server']))) : '';
            $wpftp_options['ftp_user_name'] = (isset($_POST['ftp_user_name'])) ? sanitize_text_field(trim(stripslashes($_POST['ftp_user_name']))) : '';
            $wpftp_options['ftp_user_pass'] = (isset($_POST['ftp_user_pass'])) ? sanitize_text_field(trim(stripslashes($_POST['ftp_user_pass']))) : '';
            $wpftp_options['ftp_basedir'] = (isset($_POST['ftp_basedir'])) ? sanitize_text_field(trim(stripslashes($_POST['ftp_basedir']))) : '/';

            // 不管结果变没变，有提交则直接以提交的数据 更新wpftp_options
            update_option('wpftp_options', $wpftp_options);

            # 替换 upload_url_path 的值
            update_option('upload_url_path', esc_url_raw(trim(trim(stripslashes($_POST['upload_url_path'])))));

?>
             <div class="notice notice-success settings-error is-dismissible">
                <p>保存完毕</p>
            </div>

    <?php

        }
    }



    ?>
    <style>
     .layui-table tbody tr:hover { background-color: white;}
     .layui-tab .layui-input{max-width: 350px;}
     .layui-tab tbody td{padding: 15px ;}
     .layui-tab tbody td p{padding: 5px 0;}
     .layui-sx p{padding: 5px 0;}
     .layui-text-right{ text-align: right;}
     .laobuluo-wp-hidden { position: relative;}
     .laobuluo-wp-hidden .laobuluo-wp-eyes { padding: 5px;position: absolute;top: 3px;z-index: 999;display: none; cursor:pointer; background-color: #fff; }
     .laobuluo-wp-hidden i { font-size: 20px; color: #666; }
    </style>
    <div class="wrap">
       <h1 class="wp-heading-inline"></h1>
    </div>
    <div class="container-laobuluo-main">
        <div class="laobuluo-wbs-header" style="margin-bottom: 15px;">
            <div class="laobuluo-wbs-logo"><a><img src="<?php echo plugin_dir_url(__FILE__); ?>layui/images/logo.png"></a><span class="wbs-span">WPFTP - 虚拟主机自建FTP专用</span><span class="wbs-free">Free V4.4</span></div>
            <div class="laobuluo-wbs-btn">
                <a class="layui-btn layui-btn-primary" href="https://www.lezaiyun.com/?utm_source=wpftp-setting&utm_media=link&utm_campaign=header" target="_blank"><i class="layui-icon layui-icon-home"></i> 插件主页</a>
                <a class="layui-btn layui-btn-primary" href="https://www.lezaiyun.com/wpftp.html?utm_source=wpftp-setting&utm_media=link&utm_campaign=header" target="_blank"><i class="layui-icon layui-icon-release"></i> 插件教程</a>
            </div>
        </div>
    </div>
    <!-- 内容 -->
    <div class="container-laobuluo-main">
        <div class="layui-container container-m">
            <div class="layui-row layui-col-space15">
                <!-- 左边 -->
                <div class="layui-col-md9">
                    <div class="laobuluo-panel">
                        <div class="laobuluo-controw">
                            <fieldset class="layui-elem-field layui-field-title site-title">
                                <legend><a name="get">设置选项</a></legend>
                            </fieldset>
                            <form class="layui-form" action="<?php echo wp_nonce_url('./admin.php?page=' . WPFTP_BASEFOLDER . '/actions.php'); ?>" name="wpcosform" method="post">

                                <table class="layui-table layui-tab" lay-even>
                                    <colgroup>
                                        <col width="250"><col>
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <td class="layui-text-right">FTP服务器IP地址：</td>
                                            <td>
                                                <input type="text" class="layui-input" name="ftp_server" value="<?php echo esc_attr($wpftp_options['ftp_server']); ?>" >
                                                <p class="mt10">填写服务器/虚拟主机IP地址。示例：123.123.123.123</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="layui-text-right">FTP用户名：</td>
                                            <td>
                                                <input type="text"  class="layui-input" name="ftp_user_name" value="<?php echo esc_attr($wpftp_options['ftp_user_name']); ?>" placeholder="FTP用户名">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="layui-text-right">FTP密码：</td>
                                            <td>
                                                <div class="laobuluo-wp-hidden">
                                                 <input type="password"  class="layui-input" name="ftp_user_pass" value="<?php echo esc_attr($wpftp_options['ftp_user_pass']); ?>" size="50" placeholder="FTP密码">
                                                 <span class="laobuluo-wp-eyes"><i class="dashicons dashicons-hidden"></i></span> 
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="layui-text-right">FTP空间绑定域名:</td>
                                            <td>
                                                <input type="text"  class="layui-input" name="upload_url_path" value="<?php echo esc_url(get_option('upload_url_path')); ?>" placeholder="FTP空间绑定的域名">
                                                <div class="layui-text mt10">
                                                    <b>设置注意事项:</b>
                                                    <p>1. 一般我们是以：<code>http://{FTP空间绑定域名}</code>，同样不要用"/"结尾。</p>
                                                    <p>2. 示范： <code>http(s)://ftp.laobuluo.com</code></p>
                                                </div>
                                                    
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="layui-text-right">FTP存储子目录(非必填,默认为/)：</td>
                                            <td>
                                                <input type="text"  class="layui-input"  name="ftp_basedir" value="<?php echo esc_attr($wpftp_options['ftp_basedir']); ?>"  placeholder="FTP存储子目录(非必填,默认为/)">
                                                <p class="mt10">这个是要相对我们FTP空间根目录的，一般云服务器创建的就按照默认，有些虚拟主机是需要单独设置子目录的，比如/wwwroot/</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="layui-text-right">不在本地保存：</td>
                                            <td>

                                                <input type="checkbox" name="no_local_file" title="保存"
                                                    <?php
                                                        if ($wpftp_options['no_local_file']) {
                                                            echo 'checked="TRUE"';
                                                        }
                                                    ?>
                                                >

                                                <p class="mt10" >如果不想同步在服务器中备份静态文件就 "勾选保存"。我个人喜欢只存储在存储空间中，这样缓解服务器存储量。</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                               <input type="submit" name="submit" value="保存设置" class="layui-btn" >
                                               <input type="hidden" name="type" value="cos_info_set">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            <div class="layui-text mt20 layui-sx">
                                <b>WPFTP插件注意事项：</b>
                                <p>1. 如果我们有多个网站需要使用WPFTP插件，需要给每一个网站独立不同的FTP空间。</p>
                                <p>2. 使用WPFTP插件分离图片、附件文件，存储在WPFTP存储空间根目录，比如：2019、2018、2017这样的直接目录，不会有wp-content这样目录。</p>
                                <p>3. 如果我们已运行网站需要使用WPFTP插件，插件激活之后，需要将本地wp-content目录中的文件对应时间目录上传至WPFTP存储空间中，且需要在数据库替换静态文件路径生效。</p>
                                <p>4. 详细使用教程参考：<a href="https://www.lezaiyun.com/wpftp.html" target="_blank">WPFTP发布页面地址</a>。</p>
                                <p> <font color="red"><b>5. WPFTP PRO版本(云服务器自建FTP专用)扫码右侧公众号获取。 公众号：老蒋玩运营</b></font></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 右边  -->
                <div class="layui-col-md3">
                    <div id="nav">
                         <div class="laobuluo-panel">
                        <div class="laobuluo-panel-title">关注公众号</div>
                        <div class="laobuluo-code">
                            <img src="<?php echo plugin_dir_url(__FILE__); ?>layui/images/qrcode.png">
                            <p>微信扫码关注 <span class="layui-badge layui-bg-blue">老蒋朋友圈</span> 公众号</p>
                            <p><span class="layui-badge">优先</span> 获取插件更新 和 更多 <span class="layui-badge layui-bg-green">免费插件</span> </p>
                        </div>
                    </div>

                   <div class="laobuluo-panel">
                            <div class="laobuluo-panel-title">站长必备资源</div>
                            <div class="laobuluo-shangjia">
                                <a href="https://www.lezaiyun.com/webmaster-tools.html" target="_blank" title="站长必备资源">
                                    <img src="<?php echo plugin_dir_url( __FILE__ );?>layui/images/cloud.jpg"></a>
                                    <p>站长必备的商家、工具资源整理！</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 右边 end -->

            </div>
        </div>
    </div>
    <!-- 内容 -->
    <!-- footer -->
   <div class="container-laobuluo-main">
       <div class="layui-container container-m">
           <div class="layui-row layui-col-space15">
               <div class="layui-col-md12">
                <div class="laobuluo-footer-code">
                     <span class="codeshow"></span>
                </div>
                     <div class="laobuluo-links">
                     
                        <a href="https://www.lezaiyun.com/?utm_source=wpftp-setting&utm_media=link&utm_campaign=footer"  target="_blank">乐在云</a>                       
                        <a href="https://www.lezaiyun.com/wpftp.html?utm_source=wpftp-setting&utm_media=link&utm_campaign=footer"  target="_blank">使用说明</a> 
                        <a href="https://www.lezaiyun.com/about/?utm_source=wpftp-setting&utm_media=link&utm_campaign=footer"  target="_blank">关于我们</a>
                        </div>
               </div>
           </div>
       </div>
   </div>
   <!-- footer -->
    <script>
        layui.use(['jquery', 'form', 'element'], function() {

            var $ = layui.jquery;
            var form = layui.form;

            function menuFixed(id) {
                var obj = document.getElementById(id);
                var _getHeight = obj.offsetTop;
                var _Width = obj.offsetWidth
                window.onscroll = function() {
                    changePos(id, _getHeight, _Width);
                }
            }

            function changePos(id, height, width) {
                var obj = document.getElementById(id);
                obj.style.width = width + 'px';
                var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                var _top = scrollTop - height;
                if (_top < 150) {
                    var o = _top;
                    obj.style.position = 'relative';
                    o = o > 0 ? o : 0;
                    obj.style.top = o + 'px';

                } else {
                    obj.style.position = 'fixed';
                    obj.style.top = 50 + 'px';

                }
            }


                if ($(window).width() > 1024) {

                    menuFixed('nav');
                }
                var laobueys = $('.laobuluo-wp-hidden')

                laobueys.each(function() {

                    var inpu = $(this).find('.layui-input');
                    var eyes = $(this).find('.laobuluo-wp-eyes')
                    $(this).width($(this).find('.layui-input').outerWidth(true))
                    var width = $(this).width() - 35;
                    
                    eyes.css('left', width + 'px').show();
                    eyes.click(function() {
                        if (inpu.attr('type') == "password") {

                            inpu.attr('type', 'text')
                            eyes.html('<i class="dashicons dashicons-visibility"></i>')
                        } else {
                            inpu.attr('type', 'password')
                            eyes.html('<i class="dashicons dashicons-hidden"></i>')
                        }
                    })
                })

        })
    </script>
<?php
}
?>