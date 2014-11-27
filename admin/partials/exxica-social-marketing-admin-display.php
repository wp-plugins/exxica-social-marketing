<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials
 */
 ?>
<table style="width:100%;background-color:#fff;border:1px solid #ddd;padding:10px;">
	<tbody>
		<tr>
			<td>
				<h2><?php _e('Social Marketing Settings', $locale); ?></h2>
				<form id="update_info" method="POST" action="#">
					<fieldset class="setting-fieldset" style="display:none;">
						<legend><?php _e('Exxica Username',$locale); ?></legend>
						<input readonly type="text" id="info_username" name="username" value="<?php echo $un; ?>" required style="width:100%;"><br/>
						<span class="description"><?php _e('The Exxica username is generated and can not be edited.', $locale); ?></span><br/>
						<input type="hidden" id="info_usermail" name="usermail" value="<?php echo $usermail; ?>" required>
						<input type="hidden" id="info_api_key" name="api_key" value="<?php echo $key; ?>" required>
						<input type="hidden" id="info_origin" name="origin" value="<?php echo $origin; ?>" required>
						<input type="hidden" id="info_api_secret" name="api_secret" value="<?php echo $secret; ?>" required>
						<input type="hidden" id="info_api_key_created" name="api_key_created" value="<?php echo $time; ?>" required>
					</fieldset>
				</form>
				<fieldset class="setting-fieldset">
					<legend><?php _e('License Data',$locale); ?></legend>
					<table width="100%">
						<tr>
							<th style="text-align:right;width:25%;"><?php _e('Exxica Username',$locale); ?>:</th>
							<td style="text-align:left;width:75%;">
								<?php echo $un; ?>
							</td>
						</tr>
						<tr>
							<th style="text-align:right;width:25%;"><?php _e('Account type',$locale); ?>:</th>
							<td style="text-align:left;width:75%;">
								<span id="account_type"><?php echo $account_type; ?></span>
							</td>
						</tr>
						<tr>
							<th style="text-align:right;width:25%;"><?php _e('Expires',$locale); ?>:</th>
							<td style="text-align:left;width:75%;">
								<span id="license_expiry" 
								<?php if($account_type_src == "Lifetime") : ?>
									>
									<?php _e( 'Never', $locale ); ?>
								<?php else : ?>
									<?php echo ( $license_expiry_epoch <= strtotime("+10 days") ) ? 'style="color:red;font-weight:bold;"' : ''; ?>>
									<?php echo $license_expiry_text; ?>
								<?php endif; ?>
								</span>
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<?php if($account_type_src == "Basic") : ?>
						<tr style="background-color:#ffffd8;border:1px solid;margin:10px;">
							<td colspan="2" style="padding:10px;">
								<h2><?php _e('Use Exxica Social Marketing for FREE for 45 days', $locale); ?></h2>
								<?php 
									$subject = 'Bestilling av Exxica Social Marketing';
									$body = 'Jeg ønsker herved å bestille Exxica Social Marketing til mitt domene:%0D%0A%0D%0A'.get_bloginfo('name').' ('.get_bloginfo('wpurl').')%0D%0A%0D%0A---%0D%0A%0D%0AMine opplysninger:%0D%0A%0D%0AKontaktperson: [FYLL INN NAVN]%0D%0AKontaktperson e-post: [FYLL INN]%0D%0A%0D%0AOrganisasjonsnummer: [FYLL INN]%0D%0AFirmanavn: [FYLL INN]%0D%0AFakturaadresse: [FYLL INN]%0D%0APostnummer: [FYLL INN]%0D%0APoststed: [FYLL INN]%0D%0A%0D%0A---';
									$order = sprintf(
										__('<p>Exxica Social Marketing comes with a free trial period of 45 days.</p>', $locale), 
										"<a href='mailto:post@exxica.com?subject=$subject&body=$body' id='manual_order_btn' class='button button-primary'>".__('Order', $locale)."</a>"); 
									echo $order;
								?>
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
									<input type="hidden" name="cmd" value="_s-xclick">
									<input type="hidden" name="hosted_button_id" value="DP8TNT4KNA2ZU">
									<table>
										<tr>
											<td>
												<input type="hidden" name="on0" value="Payment options"><?php _e('Payment options', $locale); ?>
											</td>
										</tr>
										<tr>
											<td>
												<select name="os0">
													<option value="Monthly"><?php _e('Monthly : €19.50 EUR', $locale); ?></option>
													<option value="Yearly - 2 months free"><?php _e('Yearly - 2 months free : €195.00 EUR', $locale); ?></option>
												</select> 
											</td>
										</tr>
									</table>
									<input type="hidden" name="currency_code" value="EUR">
									<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribe_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
								</form>
							</td>
						</tr>
						<?php endif; ?>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr class="hide_when_locked">
			<td>
				<h3><?php _e('Authorizations', $locale); ?></h3>
				<form id="auth" method="POST" action="#">
					<fieldset class="setting-fieldset">
						<legend><?php _e('Authorize Exxica Social Marketing on ', $locale); ?></legend>
						<div class="clear">
							<input type="hidden" id="auth_username" name="username" value="<?php echo $un; ?>" required>
							<input type="hidden" id="auth_origin" name="origin" value="<?php echo $origin; ?>" required>
							<input type="hidden" id="auth_api_key" name="api_key" value="<?php echo $key; ?>" required>
							<input type="hidden" id="auth_api_secret" name="api_secret" value="<?php echo $secret; ?>" required>
							<input type="hidden" id="auth_api_key_created" name="api_key_created" value="<?php echo $time; ?>" required>
							<a href="#" id="submit_auth_facebook" class="button-primary auth_btn" target="_blank"><?php _e('Facebook', $locale); ?></a>
							<a href="#" id="submit_auth_twitter" class="button-primary auth_btn" target="_blank"><?php _e('Twitter', $locale); ?></a>
							<a href="#" id="submit_auth_linkedin" class="button-primary auth_btn" target="_blank"><?php _e('LinkedIn', $locale); ?></a>
							<a href="#" id="submit_auth_google" class="button-primary auth_btn" target="_blank"><?php _e('Google', $locale); ?></a>
							<a href="#" id="submit_auth_instagram" class="button-primary auth_btn" target="_blank"><?php _e('Instagram', $locale); ?></a>
							<a href="#" id="submit_auth_flickr" class="button-primary auth_btn" target="_blank"><?php _e('Flickr', $locale); ?></a>
						</div>
					</fieldset>
				</form>
				<p><?php _e('Remember to click the <strong>Refresh</strong> link below when you have authorized your accounts.', $locale); ?></p>
			</td>
		</tr>
		<tr class="hide_when_locked">
			<td>
				<form id="set_standard" action="#" method="POST">
					<h3><?php _e('Paired accounts', $locale); ?></h3>
					<fieldset class="setting-fieldset">
						<a href="#" id="refresh_paired"><span class="exxica-dashicons dashicons dashicons-update"></span><?php _e('Refresh', $locale); ?></a>
						<table id="paired_accounts" style="width:100%;">
							<thead>
								<tr>
									<th><?php _e('Name', $locale); ?></th>
									<th><?php _e('Channel', $locale); ?></th>
									<!--<th><?php _e('Expiry date', $locale); ?></th>-->
									<th><?php _e('Set Standard', $locale); ?></th>
									<th style="text-align:right;"><?php _e('Actions', $locale); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($facebook_accounts as $account) : ?>
								<script type="text/javascript">
									(function ( $ ) {
										"use strict";

										$(function () {
											$(document).ready(function() {
												$("a#<?php echo $account['ID']; ?>.account_remove").click(function(e) {
													e.preventDefault();
													var data = [
														{ 
															'name' : 'id', 
															'value' : $("input#account_id_<?php echo $account['ID']; ?>").val() 
														}
													];
													$.post( ChannelHandlerAjax_Destroy.ajaxurl, data, function( data, status, xhr ) {
														var d = $.parseJSON(data);
														if(d.success) {
															window.location.reload(true);
														} else {
															console.log(d);
														}
													});
												});
											});
										});
									}(jQuery));
								</script>
								<tr id="<?php echo $account['ID']; ?>" class="paired_account_row">
									<td style="text-align:left;"><?php echo $account['name']; ?></td>
									<td style="text-align:left;">Facebook</td>
									<!--<td style="text-align:left;"><?php echo date('d.m.Y', $account['expiry_date']); ?></td>-->
									<td style="text-align:center">
										<input type="radio" id="standard_<?php echo $account['ID']; ?>" name="standard" value="<?php echo $account['account_identifier']; ?>"<?php checked($standard_account_id, $account['account_identifier']); ?>>
									</td>
									<td style="text-align:right;">
										<input type="hidden" id="account_id_<?php echo $account['ID']; ?>" name="account_id" value="<?php echo $account['ID']; ?>">
										<a id="<?php echo $account['ID']; ?>" class="facebook account_remove" href="#"><span class="exxica-dashicons dashicons dashicons-trash"></span></a>
									</td>
								</tr>
								<?php endforeach; ?>
								<?php foreach($twitter_accounts as $account) : ?>
								<script type="text/javascript">
									(function ( $ ) {
										"use strict";

										$(function () {
											$(document).ready(function() {
												$("a#<?php echo $account['ID']; ?>.account_remove").click(function(e) {
													e.preventDefault();
													var data = [
														{ 
															'name' : 'id', 
															'value' : $("input#account_id_<?php echo $account['ID']; ?>").val() 
														}
													];
													$.post( ChannelHandlerAjax_Destroy.ajaxurl, data, function( data, status, xhr ) {
														var d = $.parseJSON(data);
														if(d.success) {
															window.location.reload(true);
														} else {
															console.log(d);
														}
													});
												});
											});
										});
									}(jQuery));
								</script>
								<tr id="<?php echo $account['ID']; ?>" class="paired_account_row">
									<td style="text-align:left;"><?php echo $account['name']; ?></td>
									<td style="text-align:left;">Twitter</td>
									<!--<td style="text-align:left;"><?php echo date('d.m.Y', $account['expiry_date']); ?></td>-->
									<td style="text-align:center">
										<input type="radio" id="standard_<?php echo $account['ID']; ?>" name="standard" value="<?php echo $account['account_identifier']; ?>"<?php checked($standard_account_id, $account['account_identifier']); ?>>
									</td>
									<td style="text-align:right;">
										<input type="hidden" id="account_id_<?php echo $account['ID']; ?>" name="account_id" value="<?php echo $account['ID']; ?>">
										<a id="<?php echo $account['ID']; ?>" class="twitter account_remove" href="#"><span class="exxica-dashicons dashicons dashicons-trash"></span></a>
									</td>
								</tr>
								<?php endforeach; ?>
								<?php foreach($linkedin_accounts as $account) : ?>
								<script type="text/javascript">
									(function ( $ ) {
										"use strict";

										$(function () {
											$(document).ready(function() {
												$("a#<?php echo $account['ID']; ?>.account_remove").click(function(e) {
													e.preventDefault();
													var data = [
														{ 
															'name' : 'id', 
															'value' : $("input#account_id_<?php echo $account['ID']; ?>").val() 
														}
													];
													$.post( ChannelHandlerAjax_Destroy.ajaxurl, data, function( data, status, xhr ) {
														var d = $.parseJSON(data);
														if(d.success) {
															window.location.reload(true);
														} else {
															console.log(d);
														}
													});
												});
											});
										});
									}(jQuery));
								</script>
								<tr id="<?php echo $account['ID']; ?>" class="paired_account_row">
									<td style="text-align:left;"><?php echo $account['name']; ?></td>
									<td style="text-align:left;">LinkedIn</td>
									<!--<td style="text-align:left;"><?php echo date('d.m.Y', $account['expiry_date']); ?></td>-->
									<td style="text-align:center">
										<input type="radio" id="standard_<?php echo $account['ID']; ?>" name="standard" value="<?php echo $account['account_identifier']; ?>"<?php checked($standard_account_id, $account['account_identifier']); ?>>
									</td>
									<td style="text-align:right;">
										<input type="hidden" id="account_id_<?php echo $account['ID']; ?>" name="account_id" value="<?php echo $account['ID']; ?>">
										<a id="<?php echo $account['ID']; ?>" class="linkedin account_remove" href="#"><span class="exxica-dashicons dashicons dashicons-trash"></span></a>
									</td>
								</tr>
								<?php endforeach; ?>
								<?php foreach($google_accounts as $account) : ?>
								<script type="text/javascript">
									(function ( $ ) {
										"use strict";

										$(function () {
											$(document).ready(function() {
												$("a#<?php echo $account['ID']; ?>.account_remove").click(function(e) {
													e.preventDefault();
													var data = [
														{ 
															'name' : 'id', 
															'value' : $("input#account_id_<?php echo $account['ID']; ?>").val() 
														}
													];
													$.post( ChannelHandlerAjax_Destroy.ajaxurl, data, function( data, status, xhr ) {
														var d = $.parseJSON(data);
														if(d.success) {
															window.location.reload(true);
														} else {
															console.log(d);
														}
													});
												});
											});
										});
									}(jQuery));
								</script>
								<tr id="<?php echo $account['ID']; ?>" class="paired_account_row">
									<td style="text-align:left;"><?php echo $account['name']; ?></td>
									<td style="text-align:left;">Google+</td>
									<!--<td style="text-align:left;"><?php echo date('d.m.Y', $account['expiry_date']); ?></td>-->
									<td style="text-align:center">
										<input type="radio" id="standard_<?php echo $account['ID']; ?>" name="standard" value="<?php echo $account['account_identifier']; ?>"<?php checked($standard_account_id, $account['account_identifier']); ?>>
									</td>
									<td style="text-align:right;">
										<input type="hidden" id="account_id_<?php echo $account['ID']; ?>" name="account_id" value="<?php echo $account['ID']; ?>">
										<a id="<?php echo $account['ID']; ?>" class="google account_remove" href="#"><span class="exxica-dashicons dashicons dashicons-trash"></span></a>
									</td>
								</tr>
								<?php endforeach; ?>
								<?php foreach($instagram_accounts as $account) : ?>
								<script type="text/javascript">
									(function ( $ ) {
										"use strict";

										$(function () {
											$(document).ready(function() {
												$("a#<?php echo $account['ID']; ?>.account_remove").click(function(e) {
													e.preventDefault();
													var data = [
														{ 
															'name' : 'id', 
															'value' : $("input#account_id_<?php echo $account['ID']; ?>").val() 
														}
													];
													$.post( ChannelHandlerAjax_Destroy.ajaxurl, data, function( data, status, xhr ) {
														var d = $.parseJSON(data);
														if(d.success) {
															window.location.reload(true);
														} else {
															console.log(d);
														}
													});
												});
											});
										});
									}(jQuery));
								</script>
								<tr id="<?php echo $account['ID']; ?>" class="paired_account_row">
									<td style="text-align:left;"><?php echo $account['name']; ?></td>
									<td style="text-align:left;">Instagram</td>
									<!--<td style="text-align:left;"><?php echo date('d.m.Y', $account['expiry_date']); ?></td>-->
									<td style="text-align:center">
										<input type="radio" id="standard_<?php echo $account['ID']; ?>" name="standard" value="<?php echo $account['account_identifier']; ?>"<?php checked($standard_account_id, $account['account_identifier']); ?>>
									</td>
									<td style="text-align:right;">
										<input type="hidden" id="account_id_<?php echo $account['ID']; ?>" name="account_id" value="<?php echo $account['ID']; ?>">
										<a id="<?php echo $account['ID']; ?>" class="instagram account_remove" href="#"><span class="exxica-dashicons dashicons dashicons-trash"></span></a>
									</td>
								</tr>
								<?php endforeach; ?>
								<?php foreach($flickr_accounts as $account) : ?>
								<script type="text/javascript">
									(function ( $ ) {
										"use strict";

										$(function () {
											$(document).ready(function() {
												$("a#<?php echo $account['ID']; ?>.account_remove").click(function(e) {
													e.preventDefault();
													var data = [
														{ 
															'name' : 'id', 
															'value' : $("input#account_id_<?php echo $account['ID']; ?>").val() 
														}
													];
													$.post( ChannelHandlerAjax_Destroy.ajaxurl, data, function( data, status, xhr ) {
														var d = $.parseJSON(data);
														if(d.success) {
															window.location.reload(true);
														} else {
															console.log(d);
														}
													});
												});
											});
										});
									}(jQuery));
								</script>
								<tr id="<?php echo $account['ID']; ?>" class="paired_account_row">
									<td style="text-align:left;"><?php echo $account['name']; ?></td>
									<td style="text-align:left;">Flickr</td>
									<!--<td style="text-align:left;"><?php echo date('d.m.Y \k\l\. H:i:s', $account['expiry_date']); ?></td>-->
									<td style="text-align:center">
										<input type="radio" id="standard_<?php echo $account['ID']; ?>" name="standard" value="<?php echo $account['account_identifier']; ?>"<?php checked($standard_account_id, $account['account_identifier']); ?>>
									</td>
									<td style="text-align:right;">
										<input type="hidden" id="account_id_<?php echo $account['ID']; ?>" name="account_id" value="<?php echo $account['ID']; ?>">
										<a id="<?php echo $account['ID']; ?>" class="flickr account_remove" href="#"><span class="exxica-dashicons dashicons dashicons-trash"></span></a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</fieldset>
				</form>
			</td>
		</tr>
	</tbody>
</table>