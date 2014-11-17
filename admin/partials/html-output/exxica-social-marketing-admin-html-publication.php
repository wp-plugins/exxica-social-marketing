<?php
/**
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/partials/html-output
 */

if(time() <= $n) : 
	echo $this->generate_script_publication_general($post, $item, 'update');
else : 
	echo $this->generate_script_publication_readonly($post, $item);
endif;
 ?>