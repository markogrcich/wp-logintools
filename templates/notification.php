<?php
/**
 * Template representing login notifications.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/templates
 */

// Parse arguments.
$args = wp_parse_args(
	$args,
	[
		'type'    => 'error',
		'message' => '',
	]
);

?>

<div class="wp-notification wp-notification-<?php echo esc_attr( $args['type'] ); ?>">
	<?php wplt_show_message( $args['message'] ); ?>
</div>