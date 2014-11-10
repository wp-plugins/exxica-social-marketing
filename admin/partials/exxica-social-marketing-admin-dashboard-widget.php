<?php
/**
 * @package   Exxica
 * @author    Gaute Rønningen <gaute@exxica.com>
 * @link      http://exxica.com
 * @copyright 2014 Exxica AS
 *
 * @since 1.1.3 Added user manual link.
 * @since 1.0.0
 */
?>
<style>
.esm-dash-block {
	border-bottom: 1px solid #eee;
	overflow: hidden;
	padding: 8px 0 4px;
	width: 100%;
}
</style>
<table class="esm-dash-block">
	<tbody>
		<tr><td colspan="2"><?php _e('Do you need help using this plugin?', $this->name); ?><a href="http://exxica.com/using-exxica-social-marketing" target="_blank"> <?php _e('Click here', $this->name); ?></a><hr class="clear"/></td></tr>
		<?php if($publishing_today) : ?>
		<tr><th colspan="2" style="width:50%;text-align:left;"><h4><?php _e('Scheduled', $this->name); ?></h4></th></tr>
		<?php foreach($publishing_today as $item) : $text = str_split($item['publish_description'],40); ?>
		<tr style="height:30px;">
			<td style="width:30%;text-align:left;">
				<?php echo __(date('D', $item['publish_localtime']), $this->name).date(' j.m, H:i', $item['publish_localtime']); ?>
			</td>
			<td style="width:70%;text-align:left;">
				<a href="post.php?post=<?php echo $item['post_id']; ?>&action=edit"><?php echo $text[0].'...'; ?></a>
			</td>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
		<?php if($five_expired_items) : ?>
		<?php if($publishing_today) : ?><tr><td colspan="2"><hr class="clear" /></td></tr><?php endif; ?>
		<tr><th colspan="2" style="width:50%;text-align:left;"><h4><?php _e('Recently published', $this->name); ?></h4></th></tr>
		<?php foreach($five_expired_items as $item) : $text = str_split($item['publish_description'],40); ?>
		<tr style="height:30px;">
			<td style="width:30%;text-align:left;">
				<?php echo __(date('D', $item['publish_localtime']), $this->name).date(' j.m, H:i', $item['publish_localtime']); ?>
			</td>
			<td style="width:70%;text-align:left;">
				<a href="post.php?post=<?php echo $item['post_id']; ?>&action=edit"><?php echo $text[0].'...'; ?></a>
			</td>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>