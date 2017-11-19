<?php

/**
*	APlayer是一个美观大方、实用的HTML5播放器，由DIYgod编写，项目地址：https://github.com/MoePlayer/APlayer
*
*	调用APlayerHandle类并进行初始化，可以很简便的在WordPress使用上APlayer
*	短代码调用方法详见博客文章：https://kn007.net/topics/wordpress-blog-use-new-html5-player-aplayer/
*
*	支持原生[audio]标签，前提是增加title参数，并且使用src参数
*
*	此代码以MIT许可协议授权，作者kn007，写于2017年10月31日。Github地址：https://github.com/kn007/APlayerHandle
**/

class APlayerHandle {
	protected $instance = 0;

	public function init() {
		add_shortcode( 'aplayer', array( $this, 'playlist_shortcode' ) );
		add_shortcode( 'aplayer_trac', array( $this, 'trac_shortcode' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'shortcode_buttons' ), 99999 );
		add_filter( 'wp_audio_shortcode_override' , array( $this, 'override_wp_audio_shortcode' ), 1, 2 );
	}

	public function playlist_shortcode( $atts = array(), $content = '' ) {
		if ( !is_singular() && !is_admin() ) return;

		$this->instance++;
		( 1 === $this->instance ) && wp_enqueue_script( 'aplayer', 'https://unpkg.com/aplayer', array(), "1.6.0", true );

		$atts = shortcode_atts(
			array(
				'mutex'          => 'true',
				'autoplay'       => 'true',
				'theme'          => '#b7daff',
				'preload'        => 'auto',
				'mode'           => 'circulation',
				'maxheight'      => '500',
			), $atts, 'aplayer_shortcode' );

		$atts['mutex']           = wp_validate_boolean( $atts['mutex'] );
		$atts['autoplay']        = wp_validate_boolean( $atts['autoplay'] );
		$atts['theme']           = esc_attr( $atts['theme'] );
		$atts['preload']         = esc_attr( $atts['preload'] );
		$atts['mode']            = esc_attr( $atts['mode'] );
		$atts['maxheight']       = absint( $atts['maxheight'] );

		$content                 = str_replace(PHP_EOL, '', strip_tags( nl2br( do_shortcode( $content ) ) ) );

		if ( empty ( $content ) ) {
			return;
		} elseif ( FALSE !== ( $pos = strrpos( $content, ',' ) ) ) {
			$content = substr_replace( $content, '', $pos, 1 );
		}

		$output = sprintf( '<script>var ap%u=new APlayer({element:document.getElementById("aplayer-%u"),mutex:%b,autoplay:%b,theme:"%s",preload:"%s",mode:"%s",listmaxheight:"%spx",music:[%s]});</script>',
			$this->instance,
			$this->instance,
			$atts['mutex'],
			$atts['autoplay'],
			$atts['theme'],
			$atts['preload'],
			$atts['mode'],
			$atts['maxheight'],
			$content
		);

		$html = sprintf( '<div id="aplayer-%u" class="aplayer"></div>',
			$this->instance
		);

		add_action( 'wp_footer', function () use ( $output ) {
			echo '		' . $output . "\n";
		}, 99999 );

		return $html;
	}

	public function trac_shortcode( $atts = array(), $content = '' ) {
		$atts = shortcode_atts(
			array(
				'title'    => '',
				'author'   => '',
				'src'      => '',
				'pic'      => '',
		), $atts, 'aplayer_trac_shortcode' );

		$atts['title']     = sanitize_text_field( $atts['title'] );
		$atts['author']    = sanitize_text_field( $atts['author'] );
		$atts['src']       = esc_url_raw( $atts['src'] );
		$atts['pic']       = esc_url_raw( $atts['pic'] );

		if ( empty( $atts['title'] ) || empty( $atts['src'] ) ) return;

		if ( empty( $atts['author'] ) ) $atts['author'] = 'Unknown';

		if ( empty( $atts['pic'] ) ) {
			$output = sprintf( '{title:"%s",author:"%s",url:"%s"}',
				$atts['title'],
				$atts['author'],
				$atts['src']
			);
		} else {
			$output = sprintf( '{title:"%s",author:"%s",url:"%s",pic:"%s"}',
				$atts['title'],
				$atts['author'],
				$atts['src'],
				$atts['pic']
			);
		}

		return $output . ',';
	}

	public function shortcode_buttons() {
		if ( wp_script_is( 'quicktags' ) ) {
			echo "<script type=\"text/javascript\">QTags.addButton('add_aplayer', 'aplayer', '[aplayer]','[/aplayer]');QTags.addButton('add_aplayer_trac', 'aplayer_trac', '[aplayer_trac title=\"\" author=\"\" src=\"\"]');</script>";
		}
	}

	public function override_wp_audio_shortcode( $html = '', $atts = array() ) {
		if ( '' !== $html ) return $html;

		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) return $html;

		$atts = shortcode_atts(
			array(
				'mutex'          => '',
				'autoplay'       => '',
				'theme'          => '',
				'preload'        => '',
				'mode'           => '',
				'maxheight'      => '',
				'loop'           => '',
				'title'          => '',
				'author'         => '',
				'src'            => '',
				'pic'            => '',
			), $atts, 'audio' );

		if ( empty( $atts['title'] ) || empty( $atts['src'] ) ) return $html;

		( $atts['loop'] == 'true' || $atts['loop'] == 'on' || $atts['loop'] == '1' ) Or $atts['mode'] = 'order';
		( $atts['autoplay'] == '' || $atts['autoplay'] == 'off' || $atts['autoplay'] == '0' ) AND $atts['autoplay'] = 'false' Or ( ( $atts['autoplay'] == 'true' || $atts['autoplay'] == 'on' || $atts['autoplay'] == '1' ) And $atts['autoplay'] = '' );

		$player_attrs = array(
			'mutex'              => $atts['mutex'],
			'autoplay'           => $atts['autoplay'],
			'theme'              => $atts['theme'],
			'preload'            => $atts['preload'],
			'mode'               => $atts['mode'],
			'maxheight'          => $atts['maxheight'],
		);

		$player_attr_strings = array();
		foreach ( $player_attrs as $k => $v ) {
			if ( $v == '' ) continue;
			$player_attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
		}

		if ( empty( $player_attr_strings ) ) {
			$html .= '[aplayer]';
		} else {
			$html .= sprintf( '[aplayer %s]', join( ' ', $player_attr_strings ) );
		}

		$music_attrs = array(
			'title'          => $atts['title'],
			'author'         => $atts['author'],
			'src'            => $atts['src'],
			'pic'            => $atts['pic'],
		);

		$music_attr_strings = array();
		foreach ( $music_attrs as $k => $v ) {
			if ( $v == '' ) continue;
			$music_attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
		}

		$html .= sprintf( '[aplayer_trac %s]', join( ' ', $music_attr_strings ) );

		$html .= '[/aplayer]';

		return do_shortcode( $html );
	}
}
