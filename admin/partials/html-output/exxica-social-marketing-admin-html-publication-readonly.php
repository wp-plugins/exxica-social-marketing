<?php
/**
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials/html-output
 */
 ?>
<style>
	#pub-img-<?php echo $item['id']; ?> {
		max-width: 300px;
		height:	auto;
	}
</style>
<tr id="sm-item-<?php echo $item['id']; ?>-edit" style="display:none">
	<td colspan="5">
		<div style="display:table;width:100%;border:thin solid black;">
			<div style="display:table-row;width:100%;">
				<div style="display:table-cell;width:25%;height:130px;text-align:left;padding:5px;">
					<label for="channelwrap-<?php echo $item['id']; ?>"><?php _e('Channel', $this->name); ?></label>
					<div id="channelwrap-<?php echo $item['id']; ?>">
						<?php echo $item['channel']; ?>
					</div>
					<label for="accountwrap-<?php echo $item['id']; ?>"><?php _e('Account', $this->name); ?></label>
					<div id="accountwrap-<?php echo $item['id']; ?>">
						<?php if($item['channel'] == 'Facebook' ) : ?>
						<div id="accounts-facebook-<?php echo $item['id']; ?>">
							<label for="accounts-facebook-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
							<?php 
								foreach( $accountsFacebook as $it ) {
									if( $it['fb_page_id'] == $item['channel_account'] ) {
										echo $it['channel_account'];
										break;
									}
								}
							?>
						</div>
						<?php elseif($item['channel'] == 'Twitter' ) : ?>
						<div id="accounts-twitter-<?php echo $item['id']; ?>">
							<label for="accounts-twitter-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
							<?php echo $item['channel_account']; ?><br/>
						</div>
						<?php elseif($item['channel'] == 'LinkedIn' ) : ?>
						<div id="accounts-linkedin-<?php echo $item['id']; ?>">
							<label for="accounts-linkedin-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
							<?php echo $item['channel_account']; ?>														
						</div>
						<?php elseif($item['channel'] == 'Google' ) : ?>
						<div id="accounts-google-<?php echo $item['id']; ?>">
							<label for="accounts-google-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
							<?php echo $item['channel_account']; ?><br/>
						</div>
						<?php elseif($item['channel'] == 'Instagram' ) : ?>
						<div id="accounts-instagram-<?php echo $item['id']; ?>">
							<label for="accounts-instagram-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
							<?php echo $item['channel_account']; ?><br/>
						</div>
						<?php elseif($item['channel'] == 'Flickr' ) : ?>
						<div id="accounts-flickr-<?php echo $item['id']; ?>">
							<label for="accounts-flickr-publish-to-<?php echo $item['id']; ?>"><?php _e('Published on',$this->name); ?>:</label>
							<?php echo $item['channel_account']; ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<div style="display:table-cell;width:40%;height:130px;text-align:center;border-left:thin solid #aaa;border-right:thin solid #aaa;padding:5px;">
					<label for="textwrap-<?php echo $item['id']; ?>"><?php _e('Text', $this->name); ?></label>
					<div id="textwrap-<?php echo $item['id']; ?>" style="text-align:left;height:60px;overflow-y:scroll;overflow-x:hidden;">
						<?php echo $item['publish_description']; ?>
					</div>
					<label for="imagewrap-<?php echo $item['id']; ?>"><?php _e('Image', $this->name); ?></label>
					<div id="imagewrap-<?php echo $item['id']; ?>">
						<div id="image-<?php echo $item['id']; ?>" style="overflow:hidden;">
							<img id="pub-img-<?php echo $item['id']; ?>" src="<?php echo $item['publish_image_url']; ?>">
						</div>
					</div>							
				</div>
				<div style="display:table-cell;width:35%;height:130px;text-align:left;padding:5px;">
					<label for="patternwrap-<?php echo $item['id']; ?>"><?php _e('Published', $this->name); ?></label>
					<div id="patternwrap-<?php echo $item['id']; ?>">
						<?php echo date('d.m.Y \k\l\. H:i', $item['publish_localtime'] ); ?>
					</div>
				</div>
			</div>
		</div>
	</td>
</tr>