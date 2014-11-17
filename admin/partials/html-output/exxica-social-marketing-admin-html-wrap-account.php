<?php
/**
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials/html-output
 */
 ?>
<?php if( isset( $accountsFacebook ) ) : ?>
<div id="accounts-facebook-<?php echo $item['id']; ?>">
	<label><?php _e('Pages you administer', $this->name); ?></label>
	<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
		<div style="display:table-row;background-color:#bbb;">
			<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
			<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Publish',$this->name); ?></div>
		</div>
		<?php for($k = 0; $k < count($accountsFacebook); $k++ ) : $account = $accountsFacebook[$k]; ?>
		<div style="display:table-row;">
			<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
			<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
				<input id="<?php echo $k; ?>-facebook-publish" type="radio" name="facebook-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['fb_page_id']) ? ' checked="checked"' : ''; ?> value="<?php echo $account['fb_page_id']; ?>">
			</div>
		</div>
		<?php endfor; ?>
	</div>
</div>
<?php endif; ?>
<?php if( isset( $accountsTwitter ) ) : ?>
<div id="accounts-twitter-<?php echo $item['id']; ?>" style="display:none">
	<label><?php _e('Accounts you administer', $this->name); ?></label>
	<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
		<div style="display:table-row;background-color:#bbb;">
			<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
			<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Tweet',$this->name); ?></div>
		</div>
		<?php for($k = 0; $k < count($accountsTwitter); $k++ ) : $account = $accountsTwitter[$k]; ?>
		<div style="display:table-row;">
			<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
			<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
				<input id="<?php echo $k; ?>-twitter-publish" type="radio" name="twitter-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['fb_page_id']) ? ' checked="checked"' : ''; ?> value="<?php echo $account['fb_page_id']; ?>">
			</div>
		</div>
		<?php endfor; ?>
	</div>
</div>
<?php endif; ?>
<?php if( isset( $accountsLinkedIn) ) : ?>
<div id="accounts-linkedin-<?php echo $item['id']; ?>" style="display:none">
	<label><?php _e('Pages you administer', $this->name); ?></label>
	<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
		<div style="display:table-row;background-color:#bbb;">
			<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
			<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Publish',$this->name); ?></div>
		</div>
		<?php for($k = 0; $k < count($accountsLinkedIn); $k++ ) : $account = $accountsLinkedIn[$k]; ?>
		<div style="display:table-row;">
			<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
			<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
				<input id="<?php echo $k; ?>-linkedin-publish" type="radio" name="linkedin-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['channel_account']) ? ' checked' : ''; ?> value="<?php echo $account['name']; ?>">
			</div>
		</div>
		<?php endfor; ?>
	</div>												
</div>
<?php endif; ?>
<?php if( isset( $accountsGoogle ) ) : ?>
<div id="accounts-google-<?php echo $item['id']; ?>" style="display:none">
	<label><?php _e('Pages you administer', $this->name); ?></label>
	<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
		<div style="display:table-row;background-color:#bbb;">
			<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
			<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Publish',$this->name); ?></div>
			<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('+1',$this->name); ?></div>
		</div>
		<?php for($k = 0; $k < count($accountsGoogle); $k++ ) : $account = $accountsGoogle[$k]; ?>
		<div style="display:table-row;">
			<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
			<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
				<input id="<?php echo $k; ?>-google-publish" type="radio" name="google-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['channel_account']) ? ' checked' : ''; ?> value="<?php echo $account['name']; ?>">
			</div>
		</div>
		<?php endfor; ?>
	</div>
</div>
<?php endif; ?>
<?php if( isset( $accountsInstagram ) ) : ?>
<div id="accounts-instagram-<?php echo $item['id']; ?>" style="display:none">
	<label><?php _e('Accounts you administer', $this->name); ?></label>
	<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
		<div style="display:table-row;background-color:#bbb;">
			<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
			<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Publish',$this->name); ?></div>
			<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Like',$this->name); ?></div>
		</div>
		<?php for($k = 0; $k < count($accountsInstagram); $k++ ) : $account = $accountsInstagram[$k]; ?>
		<div style="display:table-row;">
			<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
			<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
				<input id="<?php echo $k; ?>-instagram-publish" type="radio" name="instagram-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['channel_account']) ? ' checked' : ''; ?> value="<?php echo $account['name']; ?>">
			</div>
		</div>
		<?php endfor; ?>
	</div>
</div>
<?php endif; ?>
<?php if( isset( $accountsFlickr ) ) : ?>
<div id="accounts-flickr-<?php echo $item['id']; ?>" style="display:none">
	<label><?php _e('Accounts you administer', $this->name); ?></label>
	<div style="display:table;width:100%;border:thin solid #ddd;background-color: #fff;">
		<div style="display:table-row;background-color:#bbb;">
			<div style="display:table-cell;text-align:left;font-weight:bold;"><?php _e('Name',$this->name); ?></div>
			<div style="display:table-cell;text-align:center;font-weight:bold;"><?php _e('Publish',$this->name); ?></div>
		</div>
		<?php for($k = 0; $k < count($accountsFlickr); $k++ ) : $account = $accountsFlickr[$k]; ?>
		<div style="display:table-row;">
			<div style="display:table-cell;text-align:left;"><?php echo $account['channel_account']; ?></div>
			<div style="display:table-cell;text-align:center;border-left: thin solid #ddd;">
				<input id="<?php echo $k; ?>-flickr-publish" type="radio" name="flickr-publish-<?php echo $item['id']; ?>"<?php echo ($item['channel_account'] == $account['channel_account']) ? ' checked' : ''; ?> value="<?php echo $account['name']; ?>">
			</div>
		</div>
		<?php endfor; ?>
	</div>	
</div>
<?php endif; ?>