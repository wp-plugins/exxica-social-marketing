<?php
/**
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials/html-output
 */
 ?>
<div>
	<label for="timewrap-<?php echo $item['id']; ?>"><?php _e('Time', $this->name); ?></label>
	<div id="timewrap-<?php echo $item['id']; ?>">
		<?php if($twentyfour_clock_enabled) : ?>
		<input id="hour-<?php echo $item['id']; ?>" name="hour" value="<?php echo $p_hour; ?>"  style="text-align:left;" size="2" maxlength="2" autocomplete="off" type="text">:
		<input id="minute-<?php echo $item['id']; ?>" name="minute" value="<?php echo $p_minute; ?>"  style="text-align:left;" size="2" maxlength="2" autocomplete="off" type="text">
		<?php else : ?>
		<input id="hour-<?php echo $item['id']; ?>" name="hour" value="<?php echo $p_hour; ?>"  style="text-align:left;" size="2" maxlength="2" autocomplete="off" type="text">:
		<input id="minute-<?php echo $item['id']; ?>" name="minute" value="<?php echo $p_minute; ?>"  style="text-align:left;" size="2" maxlength="2" autocomplete="off" type="text">
		<select name="ampm" id="ampm-<?php echo $item['id']; ?>" class="ampm exxica-select">
			<option value="am" <?php selected(date('a', $item['publish_localtime']), 'am'); ?>>AM</option>
			<option value="pm" <?php selected(date('a', $item['publish_localtime']), 'pm'); ?>>PM</option>
		</select>
		<?php endif; ?>
	</div>
</div>
<hr class="clear"/>
<p style="font-size:0.8em;">
	<?php printf(__('Your scheduled publication might be delayed by up to 15 minutes due to limitations on our server. Once scheduled your publication will send even if %s is down.', $this->name), get_bloginfo( 'site_name' ) ); ?>
</p>