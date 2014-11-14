<?php
/**
 *
 * @link       http://exxica.com
 * @since      1.1.5.1
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials
 */
 ?> 
 <script>
 (function ( $ ) {
	"use strict";

	$(function() {
		$( "#radio" ).buttonset();
	});
}(jQuery));
</script>
<form id="system-settings" method="POST" action="#">
	<?php wp_nonce_field('systemsettings'); ?>
	<table style="width:100%;background-color:#fff;border:1px solid #ddd;padding:10px;">
		<tbody>
			<tr>
				<td>
					<h2><?php _e('System-wide Social Marketing Settings', $this->name); ?></h2>
					<fieldset class="setting-fieldset" style="width:98%;">
						<legend><?php _e('Date &amp; Time related', $this->name); ?></legend>
						<div>
							<h3><?php _e('Date format', $this->name); ?></h3><br/>
							<div style="display:table;width:100%;">
								<div style="display:table-row;">
									<div style="display:table-cell;width:20%;">
										<?php _e('Current pattern', $this->name); ?>
									</div>
									<div style="display:table-cell;">
										<div>
											<?php printf('<code>%s</code> = <strong>%s</strong>', $date_format, date($date_format, time())); ?>
										</div>
									</div>
								</div>
								<div style="display:table-row;">
									<div style="display:table-cell;width:20%;">
										<?php _e('Custom', $this->name); ?>
									</div>
									<div style="display:table-cell;">
										<div>
											<input type="text" id="date_format_custom" name="date_format_custom" value="<?php _e('m/d/Y',$this->name); ?>">
										</div>
										<span class="description"><?php printf( __('Default: <code>%s</code> - Reference <a href="%s" target="_blank">%s</a>', $this->name), __( 'm/d/Y', $this->name ), 'http://php.net/manual/en/function.date.php', __('PHP Date', $this->name) ); ?></span>
									</div>
								</div>
							</div>
							<h3><?php _e('Time format', $this->name); ?></h3><br/>
							<div style="display:table;width:100%;">
								<div style="display:table-row;">
									<div style="display:table-cell;width:20%;">
										<?php _e('Current pattern', $this->name); ?>
									</div>
									<div style="display:table-cell;">
										<div>
											<?php printf('<code>%s</code> = <strong>%s</strong>', $time_format, date($time_format, time())); ?>
										</div>
									</div>
								</div>
								<div style="display:table-row;">
									<div style="display:table-cell;width:20%;">
										<?php _e('Custom', $this->name); ?>
									</div>
									<div style="display:table-cell;">
										<div>
											<input type="text" id="time_format_custom" name="time_format_custom" value="<?php _e('g:i A',$this->name); ?>">
										</div>
										<span class="description"><?php printf( __('Default: <code>%s</code> - Reference <a href="%s" target="_blank">%s</a>', $this->name), __( 'g:i A', $this->name ), 'http://php.net/manual/en/function.date.php', __('PHP Date', $this->name) ); ?></span>
									</div>
								</div>
							</div>
							<h3><?php _e('Clock', $this->name); ?></h3>
							<div style="display:table;width:100%;">
								<div style="display:table-row;">
									<div style="display:table-cell;width:20%;">
										<?php _e('24-hour clock', $this->name); ?> 
									</div>
									<div style="display:table-cell;">
										<div id="radio">
											<input type="radio" id="radio1" name="twentyfour_hour_clock" value="1" <?php checked($twentyfour_clock_enabled, '1'); ?>><label for="radio1"><strong><?php _e('On', $this->name); ?></strong></label>
											<input type="radio" id="radio2" name="twentyfour_hour_clock" value="0" <?php checked($twentyfour_clock_enabled, '0'); ?>><label for="radio2"><strong><?php _e('Off', $this->name); ?></strong></label>
										</div>
										<span class="description"><?php _e('This will only have affect on input fields.', $this->name); ?></span>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
					<?php submit_button( __('Save changes', $this->name ) ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</form>