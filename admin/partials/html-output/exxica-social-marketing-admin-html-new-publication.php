<?php
/**
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials/html-output
 */
 ?>
<script type="text/javascript">
	(function ( $ ) {
		"use strict";
		$(function () {
			$(document).ready(function() {
				$('#sm-btn-add-new').click(function(e) {
					e.preventDefault();

					// Get current date
					var old = new Date();
					var cur = new Date(old.getTime() + 30 * 60 * 1000);
					var to = new Date();
					to.setDate( cur.getDate() + 60 );

					// Generate parts
					var day = cur.getDate();
					var month = cur.getMonth()+1;
					var year = cur.getFullYear();
					var hour = cur.getHours();
					var minute = cur.getMinutes();
					var _day = to.getDate();
					var _month = to.getMonth()+1;
					var _year = to.getFullYear();

					// Add leading zeros
					if(hour < 10) hour = ("0"+hour).slice(-2);
					if(minute < 10) minute = ("0"+minute).slice(-2);
					if(day < 10) day = ("0"+day).slice(-2);
					if(month < 10) month = ("0"+month).slice(-2);
					if(_day < 10) _day = ("0"+_day).slice(-2);
					if(_month < 10) _month = ("0"+_month).slice(-2);

					// Update fields
					$("#hour-<?php echo $item['id']; ?>").val(hour);
					$("#minute-<?php echo $item['id']; ?>").val(minute);
					$("#one-time-day-<?php echo $item['id']; ?>").val(day);
					$("#one-time-month-<?php echo $item['id']; ?>").val(month);
					$("#one-time-year-<?php echo $item['id']; ?>").val(year);
					$("#day-from-<?php echo $item['id']; ?>").val(day);
					$("#month-from-<?php echo $item['id']; ?>").val(month);
					$("#year-from-<?php echo $item['id']; ?>").val(year);
					$("#day-to-<?php echo $item['id']; ?>").val(_day);
					$("#month-to-<?php echo $item['id']; ?>").val(_month);
					$("#year-to-<?php echo $item['id']; ?>").val(_year);

					// Show input form
					$("#sm-item-<?php echo $item['id']; ?>-edit").fadeIn(400);
				});
			});
		});
	})(jQuery);
</script>
<table id="sm-table-add-new" class="sm-table">
	<tbody>
		<?php echo $this->generate_script_publication_general($post, $item, 'create'); ?>
	</tbody>
</table>