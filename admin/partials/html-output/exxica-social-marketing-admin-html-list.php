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
.sm-table {
	width: 100%;
	border-top: 1px solid #ddd;
}
</style> 
<table id="sm-table" class="sm-table">
	<thead>
		<tr>
			<th style="width:2%;text-align:center"></th>
			<th style="width:10%;text-align:left;"><?php _e('Channel', $this->name); ?></th>
			<th style="width:43%;text-align:left;"><?php _e('Text', $this->name); ?></th>
			<th style="width:30%;text-align:right;"><?php _e('Publish Date', $this->name); ?></th>
			<th style="width:15%;text-align:right;"><?php _e('Actions', $this->name); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php if( count($data) !== 0 ) : ?>
		<?php $i = 0; 
		foreach($data as $itemObj) : 
			$item = (array) $itemObj;
			$i++; 
		?>
		<?php echo $this->generate_script_list_row($post, $item, $i); ?>
		<?php echo $this->generate_script_publication($post, $item); ?>
		<?php endforeach; ?>
	<?php else : ?>
		<tr>
			<td colspan="5"><?php _e('No publishing options found.', $this->name); ?></td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>