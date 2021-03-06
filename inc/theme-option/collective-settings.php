<?php

	if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		$tw_account = '';
		if(isset($_POST['ys_collective_twitter_account']) ){
			$tw_account = wp_unslash($_POST['ys_collective_twitter_account']);
			if($tw_account != ''){
				update_option('ys_sns_share_tweet_via_account', $tw_account);
				update_option('ys_twittercard_user', $tw_account);
				update_option('ys_subscribe_url_twitter', 'https://twitter.com/'.$tw_account);
			}
		}
		$fb_app_id = '';
		if(isset($_POST['ys_collective_fb_app_id']) ){
			$fb_app_id = wp_unslash($_POST['ys_collective_fb_app_id']);
			if($fb_app_id != ''){
				update_option('ys_ogp_fb_app_id', $fb_app_id);
				update_option('ys_amp_share_fb_app_id', $fb_app_id);
			}
		}
		$fb_admins = '';
		if(isset($_POST['ys_collective_fb_admins']) ){
			$fb_admins = wp_unslash($_POST['ys_collective_fb_admins']);
			if($fb_admins != ''){
				update_option('ys_ogp_fb_admins', $fb_admins);
			}
		}

		if ($tw_account !== ''
				|| $fb_app_id !== ''
				|| $fb_admins !== '' ) {

					add_settings_error('general', 'settings_updated', __('Settings saved.'), 'updated');
    }
		settings_errors();
?>

<div class="wrap">
<h2>簡単設定</h2>
<p>※既に各項目設定済みでも設定内容はこのページには表示されません。</p>
<p>※各設定で既に設定済みの内容も上書きしますのでご注意下さい。</p>
<div id="poststuff">
	<form method="post" action="">



	<div class="postbox">
		<h2>SNSアカウント設定</h2>
		<div class="inside">

			<table class="form-table">
				<tr valign="top">
					<th scope="row">Twitterアカウント</th>
					<td>
						Twitterアカウント名:@<input type="text" name="ys_collective_twitter_account" id="ys_collective_twitter_account" value="" placeholder="accountname1234" />
						<div class="collective-dscr">
							<p><strong>変更箇所</strong></p>
							<ul>
								<li>基本設定 - Twitterシェアボタン設定 - Twitterアカウント名</li>
								<li>基本設定 - OGP・Twitterカード設定 - Twitterアカウント名</li>
								<li>基本設定 - 購読ボタン設定 - Twitter（アカウントページへのURLを生成します）</li>
							</ul>
						</div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Facebook app_id</th>
					<td>
						<input type="text" name="ys_collective_fb_app_id" id="ys_collective_fb_app_id" value="" placeholder="000000000000000" />
						<div class="collective-dscr">
							<p><strong>変更箇所</strong></p>
							<ul>
								<li>基本設定 - OGP・Twitterカード設定 - Facebook app_id</li>
								<li>AMP設定 - シェアボタン設定 - Facebook app_id</li>
							</ul>
						</div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Facebook admins</th>
					<td>
						<input type="text" name="ys_collective_fb_admins" id="ys_collective_fb_admins" value="" placeholder="000000000000000" />
						<div class="collective-dscr">
							<p><strong>変更箇所</strong></p>
							<ul>
								<li>基本設定 - OGP・Twitterカード設定 - Facebook admins</li>
							</ul>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<?php submit_button(); ?>
	</form>
</div>
</div><!-- /.warp -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ys-setting-page-style.min.css">