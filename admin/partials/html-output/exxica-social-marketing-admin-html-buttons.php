<?php
/**
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials/html-output
 */
 ?>
<?php if($action == 'create') : ?>
<div style="display:table;width:100%;border-top:thin solid #ddd;border-bottom:thin solid #555;">
	<div style="display:table-row;width:100%;">
		<div style="display:table-cell;width:100%;height:30px;text-align:right;">
			<input style="position:relative;float:right;" id="submit-<?php echo $item['id']; ?>" type="submit" value="<?php _e( 'Save changes', $this->name ); ?>" class="button button-primary" id="submit" name="submit">
			<div id="save-changes-spinner" class="spinner">&nbsp;</div>
			<input style="margin-right:5px;position:relative;float:right;" id="reset-<?php echo $item['id']; ?>" class="button" type="submit" value="<?php _e( 'Discard changes', $this->name ); ?>" name="submit">
		</div>
	</div>
</div>
<?php endif; ?>