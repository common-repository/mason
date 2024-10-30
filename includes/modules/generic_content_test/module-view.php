<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
	/**
	 * string	$args['title']
	 * string	$args['content']
	 */
	global $args;
?>
<div class="gc-wrap wow animated fadeInUp">
	<h3><?php echo $args['block_name']; ?></h3>
	<div class="gc-content"><?php echo $args['content']; ?></div>
</div>
