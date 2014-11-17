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
	<label for="patternwrap-<?php echo $item['id']; ?>"><?php _e('Time Pattern', $this->name); ?></label>
	<div id="patternwrap-<?php echo $item['id']; ?>">
		<script type="text/javascript">
			(function ( $ ) {
				"use strict";
				$(function () {
					$(document).ready(function() {
						var $pattern = $('#pattern-<?php echo $item['id']; ?>');
						var $singlePattern = $('#one-time-event-wrap-<?php echo $item['id']; ?>')
						var $dailyPattern = $('#daily-event-wrap-<?php echo $item['id']; ?>')
						var $weeklyPattern = $('#weekly-event-wrap-<?php echo $item['id']; ?>')
						var $monthlyPattern = $('#monthly-event-wrap-<?php echo $item['id']; ?>')
						var $yearlyPattern = $('#yearly-event-wrap-<?php echo $item['id']; ?>')
						var $eventRange = $('#event-range-wrap-<?php echo $item['id']; ?>')
						var $identStr = $('span#ident_str');
						function showPattern( selected ) {
							if( selected == "daily" ) {
								$singlePattern.hide();
								$dailyPattern.show();
								$weeklyPattern.hide();
								$monthlyPattern.hide();
								$yearlyPattern.hide();
								$eventRange.show();
								$identStr.html("<?php _e('daily', $this->name); ?>");
							} else if( selected == "weekly" ) {
								$singlePattern.hide();
								$dailyPattern.hide();
								$weeklyPattern.show();
								$monthlyPattern.hide();
								$yearlyPattern.hide();
								$eventRange.show();
								$identStr.html("<?php _e('weekly', $this->name); ?>");
							}  else if( selected == "monthly" ) {
								$singlePattern.hide();
								$dailyPattern.hide();
								$weeklyPattern.hide();
								$monthlyPattern.show();
								$yearlyPattern.hide();
								$eventRange.show();
								$identStr.html("<?php _e('monthly', $this->name); ?>");
							} else if( selected == "yearly" ) {
								$singlePattern.hide();
								$dailyPattern.hide();
								$weeklyPattern.hide();
								$monthlyPattern.hide();
								$yearlyPattern.show();
								$eventRange.show();
								$identStr.html("<?php _e('yearly', $this->name); ?>");
							} else {
								$singlePattern.show();
								$dailyPattern.hide();
								$weeklyPattern.hide();
								$monthlyPattern.hide();
								$yearlyPattern.hide();
								$eventRange.hide();
							}
						}
						var selected = $pattern.find(":selected").val();
						showPattern(selected);
						$pattern.change(function() {
							selected = $(this).find(":selected").val();
							showPattern(selected);
						});
					});
				});
			})(jQuery);
		</script>
		<style>
			#one-time-dayname {
				font-weight: bold;
			}
			#one-time-dayname:before {
				content: " ";
			}
			#one-time-dayname:after {
				content: " ";
			}
		</style>
		<select id="pattern-<?php echo $item['id']; ?>" name="pattern" disabled="disabled">
			<optgroup label="<?php _e('Single Events',$this->name); ?>">
				<option value="single-selected" selected="selected"><?php _e('One-time only',$this->name); ?></option>
			</optgroup>
			<optgroup label="<?php _e('Repeatable Events',$this->name); ?>">
				<option disabled value="daily"><?php _e('Daily',$this->name); ?></option>
				<option disabled value="weekly"><?php _e('Weekly',$this->name); ?></option>
				<option disabled value="monthly"><?php _e('Monthly',$this->name); ?></option>
				<option disabled value="yearly"><?php _e('Yearly',$this->name); ?></option>
			</optgroup>																
		</select>
	</div>
</div>
<script>
	(function ( $ ) {
		"use strict";
		$(function () {
			$(document).ready(function() {
				<?php if(__('en_US', $this->name) == 'nb_NO') : ?>
				$.datepicker.setDefaults($.extend({'dateFormat':'dd.mm.yy'}, $.datepicker.regional['no']));
				<?php endif; ?>

				$('.datepicker').each(function() {
					$(this).datepicker();
				});
				$("#one-time-date-<?php echo $item['id']; ?>").datepicker("setDate", "<?php echo $p_date; ?>");
			});
		});
	})(jQuery);
</script>
<div id="wrap-<?php echo $item['id']; ?>">
	<div id="one-time-event-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
		<div id="one-time-event-<?php echo $item['id']; ?>" style="text-align:left;padding:5px;">
			<label for="one-time-date-<?php echo $item['id']; ?>"><?php _e('Date', $this->name); ?></label><br/>
			<input type="text" id="one-time-date-<?php echo $item['id']; ?>" class="datepicker" name="one-time-date">
		</div>
	</div>
	<?php
	/*
				<div id="daily-event-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
					<div id="daily-event-<?php echo $item['id']; ?>-patterns" style="text-align:left;padding:5px;">
						<input id="daily-event-<?php echo $item['id']; ?>-pattern-1" type="radio" name="daily-pattern" value="specified" checked="checked"><?php _e('Every', $this->name); ?> <input type="number" id="daily-event-<?php echo $item['id']; ?>-spec-day" value="1" name="daily-pattern-specified" style="width:60px;"> <?php _e('day', $this->name); ?><br>
						<input id="daily-event-<?php echo $item['id']; ?>-pattern-2" type="radio" name="daily-pattern" value="every"><?php _e('Every weekday', $this->name); ?>
					</div>
				</div>
				<div id="weekly-event-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
					<div id="weekly-event-<?php echo $item['id']; ?>-weekdays" style="padding:5px;">
						<?php _e('Happens every', $this->name ); ?> <input id="weekly-event-<?php echo $item['id']; ?>-spec" type="number" min="1" value="1" name="weekly-pattern-specified" style="width: 60px;"> <?php _e('week on', $this->name ); ?>: </br>
						<div><input id="weekly-event-<?php echo $item['id']; ?>-monday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="monday"><?php _e( 'Monday' ); ?>
						<input id="weekly-event-<?php echo $item['id']; ?>-tuesday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="tuesday"><?php _e( 'Tuesday' ); ?></div>
						<div><input id="weekly-event-<?php echo $item['id']; ?>-wednesday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="wednesday"><?php _e( 'Wednesday' ); ?>
						<input id="weekly-event-<?php echo $item['id']; ?>-thursday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="thursday"><?php _e( 'Thursday' ); ?>
						<input id="weekly-event-<?php echo $item['id']; ?>-friday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="friday"><?php _e( 'Friday' ); ?></div>
						<div><input id="weekly-event-<?php echo $item['id']; ?>-saturday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="saturday"><?php _e( 'Saturday' ); ?>
						<input id="weekly-event-<?php echo $item['id']; ?>-sunday" type="checkbox" name="weekday-<?php echo $item['id']; ?>" value="sunday"><?php _e( 'Sunday' ); ?></div>
					</div>
					<?php $str = __('weekly', $this->name); ?>
				</div>
				<div id="monthly-event-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
					<div id="monthly-event-<?php echo $item['id']; ?>-patterns" style="padding:5px;">
						<input id="monthly-event-<?php echo $item['id']; ?>-pattern-1" type="radio" name="monthly-pattern" value="specified" checked="checked"><?php _e( 'Day', $this->name ); ?> <input id="monthly-event-<?php echo $item['id']; ?>-pattern-1-day" type="number" min="1" max="31" value="<?php echo $c_day; ?>" style="width: 60px;"> <?php _e('every', $this->name ); ?> <input id="monthly-event-<?php echo $item['id']; ?>-pattern-1-month" type="number" value="1" min="1" style="width: 60px;"> <?php _e('month', $this->name); ?>.<br/>
						<input id="monthly-event-<?php echo $item['id']; ?>-pattern-2" type="radio" name="monthly-pattern" value="specified-word"><?php _e( 'The', $this->name ); ?> 
						<select id="monthly-event-<?php echo $item['id']; ?>-pattern-2-select-num" name="monthly-select-num">
							<option value="first"><?php _e( 'first', $this->name ); ?></option>
							<option value="second"><?php _e( 'second', $this->name ); ?></option>
							<option value="third"><?php _e( 'third', $this->name ); ?></option>
							<option value="fourth"><?php _e( 'fourth', $this->name ); ?></option>
							<option value="fifth"><?php _e( 'fifth', $this->name ); ?></option>
						</select>
						<select id="monthly-event-<?php echo $item['id']; ?>-pattern-2-select-weekday" name="monthly-select-weekday">
							<option value="monday"><?php _e( 'Monday' ); ?></option>
							<option value="tuesday"><?php _e( 'Tuesday' ); ?></option>
							<option value="wednesday"><?php _e( 'Wednesday' ); ?></option>
							<option value="thursday"><?php _e( 'Thursday' ); ?></option>
							<option value="friday"><?php _e( 'Friday' ); ?></option>
							<option value="saturday"><?php _e( 'Saturday' ); ?></option>
							<option value="sunday"><?php _e( 'Sunday' ); ?></option>
						</select> <?php _e( 'every', $this->name ); ?> <input id="monthly-event-<?php echo $item['id']; ?>-pattern-2-month" type="number" value="1" min="1" style="width: 60px;"> <?php _e('month(s)', $this->name); ?>.
					</div>
					<?php $str = __('monthly', $this->name); ?>
				</div>
				<div id="yearly-event-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
					<div id="yearly-event-<?php echo $item['id']; ?>-patterns" style="padding:5px;">
						<?php _e('Happens every', $this->name); ?> <input id="yearly-event-<?php echo $item['id']; ?>-pattern-year" type="number" min="1" value="1" style="width:60px;"> <?php _e('year', $this->name); ?>.<br/>
						<input id="yearly-event-<?php echo $item['id']; ?>-pattern-1" type="radio" name="yearly-pattern" value="specified" checked="checked"><?php _e('Date', $this->name); ?>: <input id="yearly-event-<?php echo $item['id']; ?>-pattern-1-day" type="number" value="<?php echo $c_day; ?>" min="1" max="31" style="width:60px;"> 
						<select id="yearly-event-<?php echo $item['id']; ?>-pattern-1-month" name="pattern-1-month">
							<option value="01"<?php echo ( $c_month == "01" ) ? ' selected="selected"' : ''; ?>><?php _e('January'); ?></option>
							<option value="02"<?php echo ( $c_month == "02" ) ? ' selected="selected"' : ''; ?>><?php _e('February'); ?></option>
							<option value="03"<?php echo ( $c_month == "03" ) ? ' selected="selected"' : ''; ?>><?php _e('March'); ?></option>
							<option value="04"<?php echo ( $c_month == "04" ) ? ' selected="selected"' : ''; ?>><?php _e('April'); ?></option>
							<option value="05"<?php echo ( $c_month == "05" ) ? ' selected="selected"' : ''; ?>><?php _e('May'); ?></option>
							<option value="06"<?php echo ( $c_month == "06" ) ? ' selected="selected"' : ''; ?>><?php _e('June'); ?></option>
							<option value="07"<?php echo ( $c_month == "07" ) ? ' selected="selected"' : ''; ?>><?php _e('July'); ?></option>
							<option value="08"<?php echo ( $c_month == "08" ) ? ' selected="selected"' : ''; ?>><?php _e('August'); ?></option>
							<option value="09"<?php echo ( $c_month == "09" ) ? ' selected="selected"' : ''; ?>><?php _e('September'); ?></option>
							<option value="10"<?php echo ( $c_month == "10" ) ? ' selected="selected"' : ''; ?>><?php _e('October'); ?></option>
							<option value="11"<?php echo ( $c_month == "11" ) ? ' selected="selected"' : ''; ?>><?php _e('November'); ?></option>
							<option value="12"<?php echo ( $c_month == "12" ) ? ' selected="selected"' : ''; ?>><?php _e('December'); ?></option>
						</select><br/>
						<input id="yearly-event-<?php echo $item['id']; ?>-pattern-2" type="radio" name="yearly-pattern" value="every"><?php _e('Every', $this->name); ?>: 
						<select id="yearly-event-<?php echo $item['id']; ?>-pattern-2-select-num" name="yearly-select-num">
							<option value="first"><?php _e( 'first', $this->name ); ?></option>
							<option value="second"><?php _e( 'second', $this->name ); ?></option>
							<option value="third"><?php _e( 'third', $this->name ); ?></option>
							<option value="fourth"><?php _e( 'fourth', $this->name ); ?></option>
							<option value="fifth"><?php _e( 'fifth', $this->name ); ?></option>
						</select>
						<select id="yearly-event-<?php echo $item['id']; ?>-pattern-2-select-weekday" name="yearly-select-weekday">
							<option value="monday"><?php _e( 'Monday' ); ?></option>
							<option value="tuesday"><?php _e( 'Tuesday' ); ?></option>
							<option value="wednesday"><?php _e( 'Wednesday' ); ?></option>
							<option value="thursday"><?php _e( 'Thursday' ); ?></option>
							<option value="friday"><?php _e( 'Friday' ); ?></option>
							<option value="saturday"><?php _e( 'Saturday' ); ?></option>
							<option value="sunday"><?php _e( 'Sunday' ); ?></option>
						</select> <?php _e('in', $this->name); ?> 
						<select id="yearly-event-<?php echo $item['id']; ?>-pattern-2-month" name="pattern-2-month">
							<option value="01"<?php echo ( $c_month == "01" ) ? ' selected="selected"' : ''; ?>><?php _e('January'); ?></option>
							<option value="02"<?php echo ( $c_month == "02" ) ? ' selected="selected"' : ''; ?>><?php _e('February'); ?></option>
							<option value="03"<?php echo ( $c_month == "03" ) ? ' selected="selected"' : ''; ?>><?php _e('March'); ?></option>
							<option value="04"<?php echo ( $c_month == "04" ) ? ' selected="selected"' : ''; ?>><?php _e('April'); ?></option>
							<option value="05"<?php echo ( $c_month == "05" ) ? ' selected="selected"' : ''; ?>><?php _e('May'); ?></option>
							<option value="06"<?php echo ( $c_month == "06" ) ? ' selected="selected"' : ''; ?>><?php _e('June'); ?></option>
							<option value="07"<?php echo ( $c_month == "07" ) ? ' selected="selected"' : ''; ?>><?php _e('July'); ?></option>
							<option value="08"<?php echo ( $c_month == "08" ) ? ' selected="selected"' : ''; ?>><?php _e('August'); ?></option>
							<option value="09"<?php echo ( $c_month == "09" ) ? ' selected="selected"' : ''; ?>><?php _e('September'); ?></option>
							<option value="10"<?php echo ( $c_month == "10" ) ? ' selected="selected"' : ''; ?>><?php _e('October'); ?></option>
							<option value="11"<?php echo ( $c_month == "11" ) ? ' selected="selected"' : ''; ?>><?php _e('November'); ?></option>
							<option value="12"<?php echo ( $c_month == "12" ) ? ' selected="selected"' : ''; ?>><?php _e('December'); ?></option>
						</select>
					</div>
				</div>
				<hr/>
				<div id="event-range-wrap-<?php echo $item['id']; ?>" style="display:none;" class="wrapping">
					<div id="event-range-<?php echo $item['id']; ?>-from" style="padding:5px;text-align:right;">
						<label for="date-from-<?php echo $item['id']; ?>"><?php _e('From', $this->name); ?></label><br/>
						<input id="date-from-<?php echo $item['id']; ?>" name="date-from" class="datepicker">
						<input id="day-from-<?php echo $item['id']; ?>" name="day-from" value="<?php echo $p_day; ?>" size="2" maxlength="2" autocomplete="off" type="text">.
						<input id="month-from-<?php echo $item['id']; ?>" name="month-from" value="<?php echo $p_month; ?>" size="2" maxlength="2" autocomplete="off" type="text">.
						<input id="year-from-<?php echo $item['id']; ?>" name="year-from" value="<?php echo $p_year; ?>" size="4" maxlength="4" autocomplete="off" type="text">
					</div>
					<div id="event-range-<?php echo $item['id']; ?>-to" style="padding:5px;text-align:right;">
						<label for="date-to-<?php echo $item['id']; ?>"><?php _e('To', $this->name); ?></label><br/>
						<input id="date-to-<?php echo $item['id']; ?>" name="date-to" class="datepicker">
						<input id="day-to-<?php echo $item['id']; ?>" name="day-to" value="<?php echo $f_day; ?>" size="2" maxlength="2" autocomplete="off" type="text">.
						<input id="month-to-<?php echo $item['id']; ?>" name="month-to" value="<?php echo $f_month; ?>" size="2" maxlength="2" autocomplete="off" type="text">.
						<input id="year-to-<?php echo $item['id']; ?>" name="year-to" value="<?php echo $f_year; ?>" size="4" maxlength="4" autocomplete="off" type="text">
					</div>
					<hr/>
					<p style="font-size:10px;"><?php _e('The <span id="ident_str">daily</span> events can only span over a max of 12 events due to spam limitations.', $this->name); ?></p>
				</div>
	*/
	?>
</div>