<?php

/**
*	APlayer是一个美观大方、实用的HTML5播放器，由DIYgod编写，项目地址：https://github.com/MoePlayer/APlayer
*
*	调用APlayerHandle类并进行初始化，可以很简便的在WordPress使用上Aplayer
*	短代码调用方法详见博客文章：
*
*	支持原生[audio]标签，前提是增加title参数，并且使用src参数
*
*	此代码以MIT许可协议授权，作者kn007，写于2017年10月31日
**/

class APlayerHandle {
	protected static $instance = 0;

	public function init() {
	}

	public function playlist_shortcode( $atts = array(), $content = '' ) {
	}

	public function trac_shortcode( $atts = array(), $content = '' ) {
	}

	public function shortcode_buttons() {
		if ( wp_script_is( 'quicktags' ) ) {
			echo "<script type=\"text/javascript\">QTags.addButton('add_aplayer', 'aplayer', '[aplayer]','[/aplayer]');QTags.addButton('add_aplayer_trac', 'aplayer_trac', '[aplayer_trac title=\"\" author=\"\" src=\"\"]');</script>";
		}
	}
}
