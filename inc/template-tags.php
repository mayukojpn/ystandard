<?php
/* ***************************************************************************
 *
 *	テンプレート出力する類の関数
 *
 * ************************************************************************ */


/**
 *	**********************************************
 *	html~head関連
 *	**********************************************
 */
/**
 *	html-head要素の出力
 */
if(!function_exists( 'ys_template_the_head_tag')) {
	function ys_template_the_head_tag(){

		// ------------------------
		// html,head開始タグ,charset,viewport
		// ------------------------
		if(ys_is_amp()):
			// AMPページ用
			ys_amp_the_head_amp();
		else :
			// 通常ページ
			ys_template_the_head_normal();
		endif;
		echo "</head>";
	}
}




//-----------------------------------------------
//	通常ページのHTML-head要素
//-----------------------------------------------
if(!function_exists( 'ys_template_the_head_normal')) {
	function ys_template_the_head_normal(){
		?>
<html <?php language_attributes(); ?>>
<?php if(ys_is_ogp_enable()): ?>
<?php 	if(is_single() || is_page()): ?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
<?php 	else: ?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blog: http://ogp.me/ns/blog#">
<?php 	endif; ?>
<?php else: ?>
<head>
<?php endif; ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="referrer" content="origin-when-crossorigin">
<meta name="format-detection" content="telephone=no" />
<?php
// インラインCSS読み込み
	ys_template_the_inline_css(
						array(
							TEMPLATEPATH.'/css/ys-firstview.min.css',
							ys_customizer_inline_css(),
							locate_template('style-firstview.css')
						),
						false
			);
?>
<?php
	if ( is_singular() && pings_open( get_queried_object() ) ) :
?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php
	endif;
	// wp_head呼び出し
	wp_head();
	}
}




//-----------------------------------------------
//	CSS読み込み
//-----------------------------------------------
if(!function_exists( 'ys_template_the_inline_css')) {
	function ys_template_the_inline_css($csslist,$amp=false){

		if($amp) {

			$csslist = apply_filters('ys_the_inline_css_amp',$csslist);

			echo '<style amp-custom>';
			foreach($csslist as $css){
				if(false === strrpos($css,TEMPLATEPATH) && false === strrpos($css,STYLESHEETPATH)){
					// インラインCSSはそのままecho
					echo $css;
				} else {
					if(file_exists($css)){
						$inline = file_get_contents($css);
						echo preg_replace( '/\/\*.*?\*\//is', '', str_replace('@charset "UTF-8";','',$inline));
					}
				}
			}//foreach

		} else {

			$csslist = apply_filters('ys_the_inline_css',$csslist);

			echo '<style type="text/css">';
			foreach($csslist as $css){

				if(false === strrpos($css,TEMPLATEPATH) && false === strrpos($css,STYLESHEETPATH)){
					// インラインCSSはそのままecho
					echo $css;
				} else {
					if(file_exists($css)){
						include($css);
					}
				}
			}//foreach
		}
		echo '</style>';
	}
}


//------------------------------------------------------------------------------
//
//	ヘッダー関連
//
//------------------------------------------------------------------------------

//-----------------------------------------------
//	サイトタイトル・ヘッダーロゴ
//-----------------------------------------------
if(!function_exists( 'ys_template_the_header_site_title_logo')) {
	function ys_template_the_header_site_title_logo(){

		$html = '';
		$logo = null;
		$logo_meta = '';
		// ロゴ取得
		if(has_custom_logo()) {
			$logo = ys_utilities_get_custom_logo_image_src();
			$logo = apply_filters('ys_custom_logo',$logo);
			$logo_meta .= '<meta itemprop="name" content="'.get_bloginfo('name').'">';
			$logo_meta .= '<meta itemprop="url" content="'.$logo[0].'">';
			$logo_meta .= '<meta itemprop="width" content="'.$logo[1].'">';
			$logo_meta .= '<meta itemprop="height" content="'.$logo[2].'">';
		}

		if(has_custom_logo() && 0 == get_option('ys_logo_hidden',0)) {
			// カスタムロゴを表示する

			$logo_html = '';
			$logo_html .= '<meta itemprop="name" content="'.get_bloginfo('name').'">';
			$logo_html .= '<a href="'.esc_url( home_url( '/' ) ).'" rel="home" itemscope itemtype="https://schema.org/ImageObject" itemprop="logo">';
			$logo_html .= '{logo_image}';
			$logo_html .= $logo_meta;
			$logo_html .= '</a>';

			$logo_image = '<img src="'.$logo[0].'" alt="'.get_bloginfo( 'name' ).'"  class="custom-logo" width="'.$logo[1].'" height="'.$logo[2].'" />';
			$logo_image = apply_filters('ys_custom_logo_img_tag',$logo_image,$logo);

			$logo_image = ys_utilities_get_the_convert_amp_img($logo_image);

			$html = str_replace('{logo_image}',$logo_image,$logo_html);

		} else {
			$itemscope = '' != $logo_meta ? ' itemscope itemtype="https://schema.org/ImageObject" itemprop="logo"' : '';
			$html = '<a class="color-site-title" href="'.esc_url( home_url( '/' ) ).'" rel="home"'.$itemscope.'>';
			$html .= get_bloginfo( 'name' );
			$html .= $logo_meta;
			$html .= '</a>';

		}

		if ( !is_singular() || is_front_page() ) {
			echo '<h1 class="site-title" itemprop="name">'.$html.'</h1>';
		} else {
			echo '<p class="site-title" itemprop="name">'.$html.'</p>';
		}

		$description = get_bloginfo( 'description', 'display' );
		$description = apply_filters('ys_header_description',$description);
		if ( $description != '' || is_customize_preview() ) {
			echo '<p class="site-description color-site-dscr">'.$description.'</p>';
		}
	}//ys_template_the_header_site_title_logo
}




//-----------------------------------------------
//	グローバルメニュー
//-----------------------------------------------
if(!function_exists( 'ys_template_the_header_global_menu')) {
	function ys_template_the_header_global_menu(){

		if ( has_nav_menu( 'gloval' )){

			if(ys_is_amp()){ ?>

				<button class="menu-toggle-label" on='tap:sidebar.toggle'>
					<span class="top"></span>
					<span class="middle"></span>
					<span class="bottom"></span>
				</button>

				<?php
			} else { ?>

				<input type="checkbox" id="menu-toggle" class="menu-toggle" hidden />
				<label  class="menu-toggle-label" for="menu-toggle">
					<span class="top"></span>
					<span class="middle"></span>
					<span class="bottom"></span>
				</label>
				<label class="menu-toggle-cover" for="menu-toggle"></label>
				<div id="site-header-menu" class="site-header-menu">
					<nav id="site-navigation" class="main-navigation">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'gloval',
								'menu_class'		 => 'gloval-menu clearfix',
								'container_class' => 'menu-global-container',
								'depth'          => 2
							 ) );
						?>
					</nav><!-- .main-navigation -->
				</div><!-- .site-header-menu -->
				<?php
			}

		}//if ( has_nav_menu( 'gloval' )){

	}//ys_template_the_header_global_menu
}




//-----------------------------------------------
//	サイト ヒーローエリア
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_site_hero' ) ) {
	function ys_template_the_site_hero() {

		// ヒーローエリア
		// 	※要カスタマイズ

		$html = '';
		echo apply_filters('ys_site_hero',$html);
	}
}





/**
 *
 *	front-page
 *
 */
if( ! function_exists( 'ys_template_get_front_page_template_part' ) ) {
	function ys_template_get_front_page_template_part() {

		$type = get_option('show_on_front');

		if('page' == $type){
			$template = 'page';
		} else {
			$template = 'home';
		}

		return apply_filters('ys_get_front_page_template_part',$template);
	}
}






//------------------------------------------------------------------------------
//
//	投稿の表示関連
//
//------------------------------------------------------------------------------

//-----------------------------------------------
//	個別記事 ヒーローエリア
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_post_hero' ) ) {
	function ys_template_the_post_hero() {

		// ヒーローエリア
		// 	※要カスタマイズ

		$html = '';
		echo apply_filters('ys_post_hero',$html);
	}
}





//-----------------------------------------------
//	投稿・更新日取得
//-----------------------------------------------
if (!function_exists( 'ys_template_the_entry_date')) {
	function ys_template_the_entry_date($show_update = true) {

		$format = get_option( 'date_format' );
		// $pubdate = 'pubdate="pubdate"';
		$pubdate = '';
		$update_content = 'content="'.get_the_modified_time('Y-m-d').'"';

		$entry_date_class = 'entry-date entry-published published';
		$update_date_class = 'entry-updated updated';

		$ico_calendar = '<svg class="entry-meta-ico" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 20 20"><path d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z"/></svg>';

		$ico_update = '<svg class="entry-meta-ico" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 20 20"><path d="M14.66 15.66A8 8 0 1 1 17 10h-2a6 6 0 1 0-1.76 4.24l1.42 1.42zM12 10h8l-4 4-4-4z"/></svg>';

		$ico_calendar = apply_filters('ys_ico_calendar',$ico_calendar);
		$ico_update = apply_filters('ys_ico_update',$ico_update);

		if(ys_is_amp()){
			$pubdate = '';
			$update_content = 'content="'.get_the_modified_time('Y-m-d').'"';
		}

		$html_pubdate = '';
		$html_update = '';

		//公開直後に微調整はよくあること。日付で判断 予約投稿すると更新日が公開日以前になる
		if(get_the_time('Ymd') >= get_the_modified_time('Ymd') || $show_update === false) {
			$entry_date_class .= ' updated';

			$html_pubdate = '<span class="entry-date-published">'.$ico_calendar.'<time class="'.$entry_date_class.'" itemprop="dateCreated datePublished dateModified" datetime="'.get_post_time('Y-m-d').'" '.$pubdate.'>'.get_the_time($format).'</time></span>';

		} else {
			$html_pubdate = '<span class="entry-date-published">'.$ico_calendar.'<time class="'.$entry_date_class.'" itemprop="dateCreated datePublished" datetime="'.get_post_time('Y-m-d').'" '.$pubdate.'>'.get_the_time($format).'</time></span>';
			$html_update = '<span class="entry-date-update">'.$ico_update.'<span class="'.$update_date_class.'" itemprop="dateModified" '.$update_content.'>'.get_the_modified_time($format).'</span></span>';
		}

		echo apply_filters('ys_entry_date_published',$html_pubdate);
		echo apply_filters('ys_entry_date_update',$html_update);

	}
}




//-----------------------------------------------
//	この記事を読む
//-----------------------------------------------
if (!function_exists( 'ys_template_the_entry_more_text')) {
	function ys_template_the_entry_more_text() {

		$read_more = 'この記事を読む';

		echo apply_filters('ys_entry_more_text',$read_more);
	}
}




//-----------------------------------------------
//	投稿者情報
//-----------------------------------------------
if (!function_exists( 'ys_template_get_the_biography')) {
	function ys_template_get_the_biography( $user_id = false,$shortcode=false,$tag='aside') {

		$author_link = esc_url( get_author_posts_url( get_the_author_meta( 'ID',$user_id ) ) );
		$author_name = ys_template_get_the_entry_author(true,$user_id,$shortcode);
		$author_sns = ys_template_get_the_author_sns($user_id);
		$author_dscr = wpautop(str_replace(array("\r\n", "\r", "\n"),"\n\n",get_the_author_meta( 'description' ,$user_id)));

		$avatar_args = $shortcode == false ? array('itemprop'=>true) : array();
		$avatar = ys_utilities_get_the_user_avatar_img($user_id,96,$avatar_args);
		if($avatar !== ''){
			$avatar = '<figure class="author-avatar"><a class="author-link" href="'.$author_link.'" rel="author">'.$avatar.'</a></figure>';
		}
		$class_dscr = $avatar !== '' ? ' show-avatar' : '';
		$class_wrap = 'author-info clearfix';
		$id = 'id="biography"';
		$itemscope = ' itemscope itemtype="http://schema.org/Person" itemprop="author editor creator copyrightHolder"';
		$itemprop = ' itemprop="description"';

		if($shortcode){
			$class_wrap = 'author-info clearfix';
			$id = '';
			$itemscope = '';
			$itemprop = '';
		}


		$template = ys_template_get_the_biography_template();

		return sprintf(
						$template,
						$tag,
						$id,
						$class_wrap,
						$itemscope,
						$avatar,
						$class_dscr,
						$author_name,
						$author_sns,
						$itemprop,
						$author_dscr,
						$tag);

	}
}




//-----------------------------------------------
//	投稿者情報
//-----------------------------------------------
if (!function_exists( 'ys_template_the_biography')) {
	function ys_template_the_biography($user_id = false) {
		echo ys_template_get_the_biography($user_id);
	}
}




//-----------------------------------------------
//	投稿者情報表示テンプレート取得
//-----------------------------------------------
if (!function_exists( 'ys_template_get_the_biography_template')) {
	function ys_template_get_the_biography_template() {

		$template = <<<EOD
		<%s %s class="%s" %s>
			%s
			<div class="author-description%s">
				<h2 class="author-title">
					%s
				</h2>
				%s
				<div class="author-bio" %s>%s</div>
			</div>
		</%s>
EOD;

	return apply_filters('ys_biography_template',$template);

	}
}




//-----------------------------------------------
//	投稿者取得
//-----------------------------------------------
if (!function_exists( 'ys_template_get_the_entry_author')) {
	function ys_template_get_the_entry_author( $link = true, $user_id = false, $hidestracture=false, $ico=false ) {

		$author_name = esc_html( get_the_author_meta( 'display_name',$user_id ) );
		$author_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID',$user_id ) ) );

		$stracture = '';
		$itempropname = '';

		if(!$hidestracture){
			$itempropname = ' itemprop="name"';

			if(!is_singular()){
				$stracture = ' itemscope itemtype="http://schema.org/Person" itemprop="author editor creator"';
			}
		}



		if($link) {
			$tag = 'a';
			$href = 'href="'.$author_url.'" rel="author"';
		} else {
			$tag = 'span';
			$href = '';
		}

		$author = '<span class="vcard author"'.$stracture.'><'.$tag.' class="url fn" '.$href.'><span'.$itempropname.'>'.$author_name.'</span></'.$tag.'></span>';

		if( $ico ){
			$author = '<svg class="entry-meta-ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/></svg>'.$author;
		}

		return apply_filters( 'ys_get_the_entry_author', $author, $user_id );
	}
}




//-----------------------------------------------
//	投稿者表示
//-----------------------------------------------
if (!function_exists( 'ys_template_the_entry_author')) {
	function ys_template_the_entry_author( $link = true, $user_id = false, $hidestracture=false, $ico=false ) {

		echo ys_template_get_the_entry_author( $link, $user_id, $hidestracture, $ico );
	}
}


//-----------------------------------------------
//	筆者のSNSプロフィール
//-----------------------------------------------
if (!function_exists( 'ys_template_get_the_author_sns')) {
	function ys_template_get_the_author_sns($user_id = false) {

		if(ys_is_amp()) {
			return;
		}

		$sns_list ='';

		// キーがmeta key,valueがfontawesomeのクラス
		$sns_arr = array(
			'ys_twitter' => 'twitter',
			'ys_facebook' => 'facebook',
			'ys_googleplus' => 'google-plus',
			'ys_instagram' => 'instagram',
			'ys_tumblr' => 'tumblr',
			'ys_youtube' => 'youtube-play',
			'ys_github' => 'github',
			'ys_pinterest' => 'pinterest',
			'ys_linkedin' => 'linkedin'
		);

		foreach ($sns_arr as $key => $val) {

			$author_sns = get_the_author_meta( $key,$user_id );
			if($author_sns != '') {
				$sns_list .= '<li class="color-'.$val.'"><a href="'.esc_url( $author_sns ).'" target="_blank" rel="nofollow" ><i class="fa fa-fw fa-'.$val.'" aria-hidden="true"></i></a></li>'.PHP_EOL;
			}
		}

		if($sns_list !== ''){
			$sns_list = '<ul class="author-sns">'.$sns_list.'</ul>';
		}
		return $sns_list;
	}
}




//-----------------------------------------------
//	ページング
//-----------------------------------------------
if (!function_exists( 'ys_template_the_link_pages')) {
	function ys_template_the_link_pages() {
		wp_link_pages( array(
					'before'      => '<div class="page-links">',
					'after'       => '</div>',
					'link_before' => '<span class="page-text">',
					'link_after'  => '</span>',
					'pagelink'    => '%',
					'separator'   => '',
				) );
	}
}




//-----------------------------------------------
//	投稿抜粋文を作成
//-----------------------------------------------
if( ! function_exists( 'ys_template_get_the_custom_excerpt' ) ) {
	function ys_template_get_the_custom_excerpt($content,$length,$sep=' ...'){
		//HTMLタグ削除
		$content = wp_strip_all_tags($content,true);
		//ショートコード削除
		$content = strip_shortcodes($content);
		if(mb_strlen($content) > $length) {
			$content =  mb_substr($content,0,$length - mb_strlen($sep)).$sep;
		}
		return $content;
	}
}




/**
 *	CTA
 */
if( ! function_exists( 'ys_template_the_entry_foot_cta' ) ) {
	function ys_template_the_entry_foot_cta() {

		// ウィジェット
		ys_template_the_entry_foot_widget();

		// 広告
		$ad = ys_template_the_advertisement_under_content();

		// シェアボタン
		$sns_share = ys_template_get_the_sns_share();

		// 購読ボタン
		$subscribe = ys_template_get_the_subscribe_buttons();

		// つなげる
		$html = $ad.$sns_share.$subscribe;

		echo apply_filters('ys_the_entry_foot_cta',$html,$ad,$sns_share,$subscribe);

	}
}




/**
 *	記事下ウィジェットの表示
 */
if( ! function_exists( 'ys_template_the_entry_foot_widget' ) ) {
	function ys_template_the_entry_foot_widget() {

		$show_widget = true;
		if( ys_is_amp() && 0 == ys_get_setting('ys_show_entry_footer_widget_amp') ){
			$show_widget = false;
		}

		$show_widget = apply_filters( 'ys_show_entry_foot_widget', $show_widget );

		if ( $show_widget && is_active_sidebar( 'entry-footer' ) ) {
			echo '<aside class="widget-entry-footer">';
			dynamic_sidebar( 'entry-footer' );
			echo '</aside>';
		}
	}
}



//-----------------------------------------------
//	シェアボタン
//-----------------------------------------------
if( ! function_exists( 'ys_template_get_the_sns_share' ) ) {
	function ys_template_get_the_sns_share(){

		global $post;

		$html = '';
		$html .= '<aside id="sns-share" class="sns-share">';
		$share_buttons_title = '\ みんなとシェアする /';
		$share_buttons_title = apply_filters('ys_share_buttons_title','<p class="sns-share-title">'.$share_buttons_title.'</p>');

		$html .= $share_buttons_title;

		if(!ys_is_amp()){
			// AMP以外
			$html .= ys_template_get_the_sns_share_buttons();

		} else {
			// AMP記事
			$html .= ys_template_get_the_amp_sns_share_buttons();

		}
		$html .= '</aside>';

		// シェアボタンが非表示なら表示消す
		if( "1" === get_post_meta($post->ID,'ys_hide_share',true) ){
			$html = '';
		}

		return apply_filters('ys_get_the_sns_share',$html);

	}
}




//-----------------------------------------------
//	通常のシェアボタン
//-----------------------------------------------
if( ! function_exists( 'ys_template_get_the_sns_share_buttons' ) ) {
	function ys_template_get_the_sns_share_buttons(){

		$share_buttons = '';
		$share_url = urlencode(get_permalink());
		$share_title = urlencode(wp_get_document_title());

		$share_buttons .= '<ul class="sns-share-button">';

		// Twitter
		$tweet_via = '';
		if(ys_get_setting('ys_sns_share_tweet_via') == 1 && ys_get_setting('ys_sns_share_tweet_via_account') != ''){
			$tweet_via = '&via='.ys_get_setting('ys_sns_share_tweet_via_account');
		}
		$twitter_share_text = apply_filters('ys_share_twitter_text',$share_title);
		$twitter_share_url = apply_filters('ys_share_twitter_url',$share_url);
		$twitter_button_text = apply_filters('ys_twitter_button_text','Twitter');

		$twitter_share_html = '<li class="twitter bg-twitter"><a href="http://twitter.com/share?text='.$twitter_share_text.'&url='.$twitter_share_url.$tweet_via.'"  target="_blank" rel="nofollow">'.$twitter_button_text.'</a></li>';
		$twitter_share_html = apply_filters('ys_the_sns_share_buttons_twitter',$twitter_share_html);

		// Facebook
		$facebook_button_text = apply_filters('ys_facebook_button_text','Facebook');
		$facebook_share_html = '<li class="facebook bg-facebook"><a href="http://www.facebook.com/sharer.php?src=bm&u='.$share_url.'&t='.$share_title.'" target="_blank"  rel="nofollow">'.$facebook_button_text.'</a></li>';
		$facebook_share_html = apply_filters('ys_the_sns_share_buttons_facebook',$facebook_share_html);


		// はてブ
		$hatenabookmark_button_text = apply_filters('ys_hatenabookmark_button_text','はてブ');
		$hatenabookmark_share_html = '<li class="hatenabookmark bg-hatenabookmark"><a class="icon-hatenabookmark" href="http://b.hatena.ne.jp/add?mode=confirm&url='.$share_url.'" target="_blank"  rel="nofollow">'.$hatenabookmark_button_text.'</a></li>';
		$hatenabookmark_share_html = apply_filters('ys_the_sns_share_buttons_hatenabookmark',$hatenabookmark_share_html);

		// Google +
		$googleplus_button_text = apply_filters('ys_googleplus_button_text','Google+');
		$googleplus_share_html = '<li class="google-plus bg-google-plus"><a href="https://plus.google.com/share?url='.$share_url.'" target="_blank" rel="nofollow">'.$googleplus_button_text.'</a></li>';
		$googleplus_share_html = apply_filters('ys_the_sns_share_buttons_googleplus',$googleplus_share_html);

		// Pocket
		$pocket_button_text = apply_filters('ys_pocket_button_text','Pocket');
		$pocket_share_html = '<li class="pocket bg-pocket"><a href="http://getpocket.com/edit?url='.$share_url.'&title='.$share_title.'" target="_blank" rel="nofollow">'.$pocket_button_text.'</a></li>';
		$pocket_share_html = apply_filters('ys_the_sns_share_buttons_pocket',$pocket_share_html);

		// LINE
		$line_share_html = '<li class="line bg-line"><a href="http://line.me/R/msg/text/?'.$share_title.'%0A'.$share_url.'" target="_blank">LINE</a></li>';
		$line_share_html = apply_filters('ys_the_sns_share_buttons_line',$line_share_html);

		$share_buttons .= $twitter_share_html.$facebook_share_html.$hatenabookmark_share_html.$googleplus_share_html.$pocket_share_html.$line_share_html;

		// ボタン追加
		$share_buttons = apply_filters('ys_sns_share_buttons_add',$share_buttons);

		$share_buttons .= '</ul>';

		return apply_filters('ys_get_the_sns_share_buttons',$share_buttons);
	}
}




//-----------------------------------------------
//	AMPシェアボタン
//-----------------------------------------------
if( ! function_exists( 'ys_template_get_the_amp_sns_share_buttons' ) ) {
	function ys_template_get_the_amp_sns_share_buttons() {
		$share_buttons = '';

		$fb_app_id = ys_get_setting('ys_amp_share_fb_app_id');

		$share_buttons .= '<amp-social-share type="twitter" width="90" height="44"></amp-social-share>';
		$share_buttons .= '<amp-social-share type="facebook" width="90" height="44" data-param-app_id="'.$fb_app_id.'"></amp-social-share>';
		$share_buttons .= '<amp-social-share type="gplus" width="90" height="44"></amp-social-share>';

		if(ys_get_setting('ys_amp_normal_link_share_btn') == 1) {
			$share_buttons .= '<p class="amp-view-info">その他の方法でシェアする場合は通常表示に切り替えて下さい。<a class="normal-view-link" href="'.get_the_permalink().'#sns-share">通常表示に切り替える »</a></p>';
		}

		return apply_filters('ys_get_the_amp_sns_share_buttons',$share_buttons);
	}
}




/**
 *	購読リンク
 */
if( ! function_exists( 'ys_template_get_the_subscribe_buttons' ) ) {
	function ys_template_get_the_subscribe_buttons($container = true) {

		global $post;

		$subscribe = array(
									'Twitter'=>array(
																'class'=>'twitter',
																'url'=>ys_get_setting('ys_subscribe_url_twitter')
															),
									'Facebook'=>array(
																'class'=>'facebook',
																'url'=>ys_get_setting('ys_subscribe_url_facebook')
															),
									'Google+'=>array(
															'class'=>'google-plus',
															'url'=>ys_get_setting('ys_subscribe_url_googleplus')
														),
									'Feedly'=>array(
															'class'=>'feedly',
															'url'=>ys_get_setting('ys_subscribe_url_feedly')
														)
								);
		$subscribe = apply_filters('ys_get_the_subscribe_buttons_list',$subscribe);

		$subscribe_html = '';
		$count = 0;
		foreach ($subscribe as $key => $value) {
			if($value['url']){
				$subscribe_html .= '<li><a class="'.$value['class'].' bg-'.$value['class'].'" href="'.esc_html($value['url']).'" target="_blank" rel="nofollow">'.$key.'</a></li>';
				$count ++;
			}
		}
		$subscribe_html = apply_filters('ys_get_the_subscribe_buttons_html',$subscribe_html);

		// 何も設定がなければなにも出さない
		if(0 == $count){
			return '';
		}

		// HTML組み立て
		$html = '';
		if($container){
			$html .= '<aside id="subscribe" class="subscribe">';
			$subscribe_title = 'フォローする！';
			$subscribe_title = apply_filters('ys_get_the_subscribe_buttons_title','<p class="subscribe-title">'.$subscribe_title.'</p>');
			$html .= $subscribe_title;
		}

		$html .= (0 == $count%2) ? '<ul>' : '<ul class="follow-odd">' ;
		$html .= $subscribe_html;
		$html .= '</ul>';

		if($container){
			$html .= '</aside>';
		}

		// フォローボタンの非表示設定
		if( "1" === get_post_meta($post->ID,'ys_hide_follow',true) ){
			$html = '';
		}

		return apply_filters('ys_get_the_subscribe_buttons',$html);
	}
}




//-----------------------------------------------
//	関連記事
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_related_post' ) ) {
	function ys_template_the_related_post() {

		if(ys_get_setting('ys_show_post_related') == 1 && !ys_is_amp()) {
			$cats = ys_utilities_get_cat_id_list();
			$cats = apply_filters('ys_the_related_post_category_in',$cats);
			$option = array(
											'post__not_in' => array(get_the_ID()),  //現在の投稿IDは除く
											'category__in' => $cats, //カテゴリー絞り込み
										);

			$query = new WP_Query(ys_utilities_get_rand(4,$option));

			if ($query->have_posts()) {
				echo '<aside class="entry-post-related entry-footer-container">';
				echo '<h2>関連記事</h2>';
				while ($query->have_posts()) : $query->the_post();
					get_template_part( 'template-parts/content','related' );
				endwhile;
				echo '</aside>';
			}
			wp_reset_postdata();
		}

	}//ys_template_the_related_post
}




//-----------------------------------------------
//	前の記事・次の記事
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_post_paging' ) ) {
	function ys_template_the_post_paging() {

		if(ys_get_setting('ys_hide_post_paging') == 1){
			return;
		}

		$html = '<div class="post-navigation entry-footer-container">';

		$home = '<div class="home"><a href="'.esc_url( home_url( '/' ) ).'/">';
		if(ys_is_amp()){
			$home .= 'HOME';
		} else {
			$home .= '<i class="fa fa-home" aria-hidden="true"></i>';
		}
		$home .= '</a></div>';

		$html .= '<div class="nav-prev">';
		$prevpost = get_previous_post();
		if ($prevpost) {
			$prev_info = apply_filters('ys_the_post_paging_prev_info','<span class="prev-label">«前の投稿</span>');
			$prev_img = '';
			if( has_post_thumbnail( $prevpost->ID ) ) {
				$prev_img = ys_template_get_the_post_thumbnail( 'yslistthumb', false, false, '', 'post-navigation-image', $prevpost->ID);
				$prev_img = apply_filters( 'ys_the_post_paging_prev_image', $prev_img, $prevpost->ID );
				$prev_img = '<span class="post-navigation-image-wrap post-list-thumbnail-center">'.$prev_img.'</span>';
			}
			$prev_link = '<a href="'.esc_url(get_permalink($prevpost->ID)).'">'.$prev_img.get_the_title($prevpost->ID).'</a>';
			$prev_link = apply_filters('ys_the_post_paging_prev_link',$prev_link);
			$html .= $prev_info.$prev_link;
		} else {
			$html .= $home;
		}
		$html .= '</div>';

		$html .= '<div class="nav-next">';
		$nextpost = get_next_post();
		if ($nextpost){

			$next_info = apply_filters('ys_the_post_paging_next_info','<span class="next-label">次の投稿»</span>');
			$next_img = '';
			if( has_post_thumbnail( $nextpost->ID ) ) {
				$next_img = ys_template_get_the_post_thumbnail( 'yslistthumb', false, false, '', 'post-navigation-image', $nextpost->ID);
				$next_img = apply_filters( 'ys_the_post_paging_next_image', $next_img, $nextpost->ID );
				$next_img = '<span class="post-navigation-image-wrap post-list-thumbnail-center">'.$next_img.'</span>';
			}
			$next_link = '<a href="'.esc_url(get_permalink($nextpost->ID)).'">'.$next_img.get_the_title($nextpost->ID).'</a>';
			$next_link = apply_filters('ys_the_post_paging_next_link',$next_link);
			$html .= $next_info.$next_link;
		} else {
			$html .= $home;
		}
		$html .= '</div>';



		$html .= '</div>';

		$html = apply_filters('ys_post_paging',$html);

		echo $html;
	}
}








//------------------------------------------------------------------------------
//
//	フッター関連
//
//------------------------------------------------------------------------------

//-----------------------------------------------
//	SNSのフォローリンク
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_follow_sns_list' ) ) {
	function ys_template_the_follow_sns_list() {

		$twitter = ys_get_setting('ys_follow_url_twitter');
		$facebook = ys_get_setting('ys_follow_url_facebook');
		$googlepuls = ys_get_setting('ys_follow_url_googlepuls');
		$instagram = ys_get_setting('ys_follow_url_instagram');
		$tumblr = ys_get_setting('ys_follow_url_tumblr');
		$youtube = ys_get_setting('ys_follow_url_youtube');
		$github = ys_get_setting('ys_follow_url_github');
		$pinterest = ys_get_setting('ys_follow_url_pinterest');
		$linkedin = ys_get_setting('ys_follow_url_linkedin');


		$html = '';

		if($twitter != ''
			|| $facebook != ''
			|| $googlepuls != ''
			|| $instagram != '') {

			$sns_follow_links = '';

			if($twitter != ''){
				$sns_follow_links .= '<li class="twitter"><a href="'.$twitter.'" target="_blank" rel="nofollow"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>';
			}
			if($facebook != ''){
				$sns_follow_links .= '<li class="facebook"><a href="'.$facebook.'" target="_blank" rel="nofollow"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>';
			}
			if($googlepuls != ''){
				$sns_follow_links .= '<li class="googlepuls"><a href="'.$googlepuls.'" target="_blank" rel="nofollow"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>';
			}
			if($instagram != ''){
				$sns_follow_links .= '<li class="instagram"><a href="'.$instagram.'" target="_blank" rel="nofollow"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>';
			}

			if($tumblr != ''){
				$sns_follow_links .= '<li class="tumblr"><a href="'.$tumblr.'" target="_blank" rel="nofollow"><i class="fa fa-tumblr" aria-hidden="true"></i></a></li>';
			}

			if($youtube != ''){
				$sns_follow_links .= '<li class="youtube"><a href="'.$youtube.'" target="_blank" rel="nofollow"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>';
			}

			if($github != ''){
				$sns_follow_links .= '<li class="github"><a href="'.$github.'" target="_blank" rel="nofollow"><i class="fa fa-github" aria-hidden="true"></i></a></li>';
			}

			if($pinterest != ''){
				$sns_follow_links .= '<li class="pinterest"><a href="'.$pinterest.'" target="_blank" rel="nofollow"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>';
			}

			if($linkedin != ''){
				$sns_follow_links .= '<li class="linkedin"><a href="'.$linkedin.'" target="_blank" rel="nofollow"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>';
			}

			$sns_follow_links = apply_filters('ys_follow_sns_list',$sns_follow_links);

			$html .= '<div class="follow-sns-list"><ul>';
			$html .= $sns_follow_links;
			$html .= '</ul></div>';

			if ( !ys_is_amp() ) {
				echo $html;
			} else {
				$html = '';
				echo apply_filters('ys_follow_sns_list_amp',$html);
			}
		}
	}//ys_template_the_follow_sns_list
}




//-----------------------------------------------
//	フッターウィジットエリア
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_fotter_widget' ) ) {
	function ys_template_the_fotter_widget() {

		if ( !ys_is_amp()
					&& (is_active_sidebar( 'footer-left' )
							|| is_active_sidebar( 'footer-center' )
							|| is_active_sidebar( 'footer-right' ) ) ) {

			echo '<div class="footer-widget-wrapper">';

				// 左ウィジェット
				if ( is_active_sidebar( 'footer-left' )){
					echo '<div class="footer-widget-left">';
					dynamic_sidebar( 'footer-left' );
					echo '</div>';
				}

				// 中央ウィジェット
				if ( is_active_sidebar( 'footer-center' )) {
					echo '<div class="footer-widget-center">';
					dynamic_sidebar( 'footer-center' );
					echo '</div>';
				}

				// 右ウィジェット
				if ( is_active_sidebar( 'footer-right' )) {
					echo '<div class="footer-widget-right">';
					dynamic_sidebar( 'footer-right' );
					echo '</div>';
				}

			echo '</div>';
		}
	}//ys_template_the_fotter_widget
}




//-----------------------------------------------
//	AMPページ用メニュー出力
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_amp_menu' ) ) {
	function ys_template_the_amp_menu() {

		if(ys_is_amp()):
		?>
			<amp-sidebar id='sidebar' layout="nodisplay" side="right" class="amp-slider">
				<button class="menu-toggle-label" on='tap:sidebar.close'>
					<span class="top"></span>
					<span class="middle"></span>
					<span class="bottom"></span>
				</button>
				<nav id="site-navigation" class="main-navigation">
		<?php
			wp_nav_menu( array(
				'theme_location' => 'gloval',
				'menu_class'		 => 'gloval-menu',
				'container_class' => 'menu-global-container',
				'depth'          => 2
			 ) );
		?>
			</nav><!-- .main-navigation -->
		</amp-sidebar>
		<?php
		endif;
	}
}




//-----------------------------------------------
//	copyright
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_copyright' ) ) {
	function ys_template_the_copyright() {

		$copyright = '<p class="copy">Copyright &copy; '.ys_get_setting('ys_copyright_year').' <a href="'. esc_url( home_url( '/' ) ) . '" rel="home">' . get_bloginfo('name') . '</a> All Rights Reserved.</p>';
		$powered = '<p id="powered">Powered by <a href="https://ja.wordpress.org/" target="_blank" rel="nofollow">WordPress</a> &amp; ';
		$poweredtheme = '<a href="https://wp-ystandard.com" target="_blank" rel="nofollow">yStandard Theme</a> by <a href="https://yosiakatsuki.net/blog/" target="_blank" rel="nofollow">yosiakatsuki</a></p>';

		$copyright = apply_filters('ys_copyright',$copyright);
		$powered = apply_filters('ys_poweredby',$powered);
		$poweredtheme = apply_filters('ys_poweredby_theme',$poweredtheme);

		echo $copyright.$powered.$poweredtheme;
	}
}







//------------------------------------------------------------------------------
//
//	タクソノミー関連の関数
//
//------------------------------------------------------------------------------
//-----------------------------------------------
//	投稿のカテゴリー出力
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_post_categorys' ) ) {
	function ys_template_the_post_categorys($number = 0,$link=true,$separator=', ',$postid=0) {
		echo '<svg class="entry-meta-ico" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 20 20"><path d="M0 4c0-1.1.9-2 2-2h7l2 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2 2v10h16V6H2z"/></svg>'.ys_utilities_get_the_post_categorys($number,$link,$separator,$postid,array('itemprop'=>true));
	}
}




//-----------------------------------------------
//	投稿のカテゴリー一覧出力
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_category_list' ) ) {
	function ys_template_the_category_list($before,$after,$separator=', ',$link=true,$postid=0,$args=array()) {

		$categorys = ys_utilities_get_the_post_categorys(0,$link,$separator,$postid,$args);

		echo $before;
		echo $categorys;
		echo $after;
	}
}




//-----------------------------------------------
//	投稿のタグ一覧出力
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_tag_list' ) ) {
	function ys_template_the_tag_list($before,$after,$separator=', ',$link=true,$postid=0) {

		$tags = ys_utilities_get_the_tag_list($separator,$link,$postid);

		if($tags != ''){
			echo $before;
			echo $tags;
			echo $after;
		}
	}
}




//-----------------------------------------------
//	投稿のタグ一覧出力
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_taxonomy_list' ) ) {
	function ys_template_the_taxonomy_list() {

		echo '<div class="entry-footer-container">';

		// カテゴリー
		ys_template_the_category_list('<aside class="entry-category-list"><h2>カテゴリー</h2>','</aside>','',true,0);
		// タグ
		ys_template_the_tag_list('<aside class="entry-tag-list"><h2>タグ</h2>','</aside>','');

		echo '</div>';

	}
}




//------------------------------------------------------------------------------
//
//	画像関連の関数
//
//------------------------------------------------------------------------------

if (!function_exists( 'ys_template_get_the_post_thumbnail')) {
	function ys_template_get_the_post_thumbnail(
																	$thumbname='full',
																	$viewsize=false,
																	$outputmeta=true,
																	$imgid='',
																	$imgclass='',
																	$postid=0
																) {

		if($postid == 0){
			$postid = get_the_ID();
		}

		$html = '';
		$image = null;
		$thumbname = apply_filters( 'ys_the_post_thumbnail_thumbname', $thumbname, $postid );
		$imgid = apply_filters( 'ys_the_post_thumbnail_id', $imgid, $thumbname, $postid );
		$imgclass = apply_filters( 'ys_the_post_thumbnail_class', $imgclass, $thumbname, $postid );

		if( has_post_thumbnail( $postid ) ) {

			$post_thumbnail_id = get_post_thumbnail_id( $postid );

			if( $post_thumbnail_id ) {
				$image = wp_get_attachment_image_src( $post_thumbnail_id, $thumbname );
			}

			$attr = array();
			if( $imgid ) $attr = wp_parse_args( array( 'id'=>$imgid ), $attr );
			if( $imgclass ) $attr = wp_parse_args( array( 'class'=>$imgclass ), $attr );

			$html = get_the_post_thumbnail( $postid, $thumbname, $attr );

		}

		if( '' == $html ) {

			// 画像を取得
			$image = ys_utilities_get_post_thumbnail($thumbname,'',$postid);

			// id確認
			if($imgid !== ''){
				$imgid = ' id="'.$imgid.'"';
			}
			// class確認
			if($imgclass !== ''){
				$imgclass = ' class="'.$imgclass.'"';
			}

			if( !is_array( $viewsize ) ){
				$viewsize = array($image[1],$image[2]);
			} else {
				if( 0 == $viewsize[0] ){
					$viewsize[0] = $image[1];
				}
				if( 0 == $viewsize[1] ){
					$viewsize[1] = $image[2];
				}
			}

			$html = '<img '.$imgid.$imgclass.'src="'.$image[0].'" '.image_hwstring($viewsize[0],$viewsize[1]).' alt="" />';

		}

		$html = apply_filters( 'ys_the_post_thumbnail', $html, $image, $thumbname, $postid );
		$html =  ys_utilities_get_the_convert_amp_img($html);

		//metaタグを出力
		if($outputmeta){
			$html .= ys_utilities_get_the_image_object_meta($image);
		}

		return $html;
	}
}

/**
 *	画像取得（出力）
 */
if (!function_exists( 'ys_template_the_post_thumbnail')) {
	function ys_template_the_post_thumbnail(
																	$thumbname='full',
																	$viewsize=false,
																	$outputmeta=true,
																	$imgid='',
																	$imgclass='',
																	$postid=0
																) {

		echo ys_template_get_the_post_thumbnail( $thumbname, $viewsize, $outputmeta, $imgid, $imgclass, $postid );
	}
}



//-----------------------------------------------
//	ユーザー画像出力
//-----------------------------------------------
if (!function_exists( 'ys_template_the_user_avatar')) {
	function ys_template_the_user_avatar($author_id = null,$size = 96,$args=array()){

		echo ys_utilities_get_the_user_avatar_img($author_id,$size,$args);
	}
}








//------------------------------------------------------------------------------
//
//	広告関連
//
//------------------------------------------------------------------------------

//-----------------------------------------------
//	広告出力フォーマット
//-----------------------------------------------
if( ! function_exists( 'ys_template_get_the_advertisement_format' ) ) {
	function ys_template_get_the_advertisement_format($ad) {

		$html = '';
		if($ad !== '' && !is_feed()){
			$html = '<aside class="ad-container"><div class="ad-caption">スポンサーリンク</div><div class="ad-content">';
			$html .= $ad;
			$html .= '</div></aside>';
		}

		return $html;
	}
}

//-----------------------------------------------
//	広告:記事タイトル下
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_advertisement_under_title' ) ) {
	function ys_template_the_advertisement_under_title() {

		$key = 'ys_advertisement_under_title';
		if(ys_is_mobile()){
			$key = 'ys_advertisement_under_title_sp';
		}
		if(ys_is_amp()){
			$key = 'ys_amp_advertisement_under_title';
		}

		$ad = '';
		$ad = ys_get_setting($key);
		echo apply_filters('ys_advertisement_under_title',ys_template_get_the_advertisement_format($ad));

	}
}




//-----------------------------------------------
//	広告:moreタグ置換
//-----------------------------------------------
if( ! function_exists( 'ys_template_get_the_advertisement_more_tag' ) ) {
	function ys_template_get_the_advertisement_more_tag() {

		// ※出力はextras内
		$key = 'ys_advertisement_replace_more';
		if(ys_is_mobile()){
			$key = 'ys_advertisement_replace_more_sp';
		}
		if(ys_is_amp()){
			$key = 'ys_amp_advertisement_replace_more';
		}
		$ad = '';
		$ad = ys_get_setting($key);
		return apply_filters('ys_advertisement_replace_more',ys_template_get_the_advertisement_format($ad));
	}
}




//-----------------------------------------------
//	広告:記事下
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_advertisement_under_content' ) ) {
	function ys_template_the_advertisement_under_content() {

		$key_left = 'ys_advertisement_under_content_left';
		$key_right = 'ys_advertisement_under_content_right';
		if(ys_is_mobile()){
			$key_left = 'ys_advertisement_under_content_sp';
			$key_right = '';
		}
		if(ys_is_amp()){
			$key_left = 'ys_amp_advertisement_under_content';
			$key_right = '';
		}

		$ad = '';
		$ad_left = ys_get_setting($key_left);
		$ad_right = '';
		if($key_right !== ''){
			$ad_right = ys_get_setting($key_right);
		}

		if($ad_left !== '' && $ad_right !== ''){
			$ad .= '<div class="ad-double">';
			$ad .= '<div class="ad-left">'.$ad_left.'</div>';
			$ad .= '<div class="ad-right">'.$ad_right.'</div>';
			$ad .= '</div>';
		} else {
			if($ad_left !== ''){
				$ad = $ad_left;
			}
			if($ad_right !== ''){
				$ad = $ad_right;
			}
		}

		// 固定ページでは広告出さない
		if( is_page() ){
			$ad = '';
		}

		return apply_filters('ys_advertisement_under_content',ys_template_get_the_advertisement_format($ad));

	}
}




//------------------------------------------------------------------------------
//
//	カスタム属性
//
//------------------------------------------------------------------------------

//-----------------------------------------------
//	ヘッダ
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_header_attr' ) ) {
	function ys_template_the_header_attr() {
		echo apply_filters('ys_the_header_attr','');
	}
}
//-----------------------------------------------
//	コンテンツ
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_content_attr' ) ) {
	function ys_template_the_content_attr() {
		echo apply_filters('ys_the_content_attr','');
	}
}
//-----------------------------------------------
//	サイドバー
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_sidebar_attr' ) ) {
	function ys_template_the_sidebar_attr() {
		echo apply_filters('ys_the_sidebar_attr','');
	}
}
//-----------------------------------------------
//	フッター
//-----------------------------------------------
if( ! function_exists( 'ys_template_the_footer_attr' ) ) {
	function ys_template_the_footer_attr() {
		echo apply_filters('ys_the_footer_attr','');
	}
}



?>