<?php
/**
Plugin Name: WPFTP（虚拟主机自建FTP空间）
Plugin URI: https://www.lezaiyun.com/wpftp.html
Description: WordPress同步附件内容远程至自建FTP空间存储中，实现网站数据与静态资源分离，提高网站加载速度。公众号：老蒋朋友圈。
Version: 4.4
Author: 老蒋和他的小伙伴
Author URI: https://www.lezaiyun.com/
*/

require_once 'actions.php';

# 插件 activation 函数当一个插件在 WordPress 中”activated(启用)”时被触发。
register_activation_hook(__FILE__, 'wpftp_set_options');
register_deactivation_hook(__FILE__, 'wpftp_restore_options');  # 禁用时触发钩子


# 避免上传插件/主题被同步到对象存储
if (substr_count($_SERVER['REQUEST_URI'], '/update.php') <= 0) {
	add_filter('wp_handle_upload', 'wpftp_upload_attachments');
	# add_filter('wp_generate_attachment_metadata', 'wpftp_upload_and_thumbs');  # 貌似会和wp_update_attachment_metadata钩子重复提交。先注释！
}

# 附件更新后触发
add_filter( 'wp_update_attachment_metadata', 'wpftp_upload_and_thumbs' );

# 检测不重复的文件名
add_filter('wp_unique_filename', 'wpftp_unique_filename');

# 删除文件时触发删除远端文件，该删除会默认删除缩略图
add_action('delete_attachment', 'wpftp_delete_remote_attachment');

# 添加插件设置菜单
add_action('admin_menu', 'wpftp_add_setting_page');
add_filter('plugin_action_links', 'wpftp_plugin_action_links', 10, 2);