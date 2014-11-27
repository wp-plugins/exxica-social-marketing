<?php
/**
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials/html-output
 */
 ?>
 <script>
 (function ( $ ) {
	"use strict";

	$(function () {

		$(document).ready(function() {
			$("a#sm-btn-close-window").click(function(e) {
				e.preventDefault();
				$("div#esm-modal").hide();
			});
		});
	});
}(jQuery));
 </script>
 <div class="media-frame-menu">
	<div class="media-menu">
		<a href="#" class="media-menu-item active"><?php _e('Exxica Social Marketing', $this->name); ?></a>
		<div class="separator"></div>
		<a href="#" class="media-menu-item" id="sm-btn-add-new" title="<?php _e('Add a new publication.', $this->name); ?>"><?php _e( 'Add New', $this->name ); ?></a>
		<div class="separator"></div>
		<a class="media-menu-item" id="sm-btn-overview" title="<?php _e('Show the marketing overview.', $this->name); ?>" href="edit.php?page=exxica-sm-overview"><?php _e( 'Show overview', $this->name ); ?></a>
		<div class="separator"></div>
		<a href="users.php?page=exxica-sm-settings" class="media-menu-item" id="sm-btn-my-settings" title="<?php _e('Show your personal social marketing settings.', $this->name); ?>"><?php _e( 'My settings', $this->name ); ?></a>
		<?php if(current_user_can('manage_options')) : ?>
		<a href="options-general.php?page=exxica-sm-system-settings" class="media-menu-item" id="sm-btn-system-settings" title="<?php _e('Show system social marketing settings.', $this->name); ?>"><?php _e( 'System settings', $this->name ); ?></a>
		<?php endif; ?>
		<div class="separator"></div>
		<a class="media-menu-item" id="sm-btn-close-window" href="#" title="<?php _e('Close your window without discarding changes. If you choose to save the changes in the view behind, they will be discarded though.', $this->name); ?>"><?php _e('Close window', $this->name); ?></a>
	</div>
</div>