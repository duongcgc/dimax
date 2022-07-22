<?php
/**
 * Template part for displaying the campaign bar
 *
 * @package dimax
 */

use Dimax\Helper;

$campaigns    = array_filter( (array) Helper::get_option( 'campaign_items' ) );
$class_mobile = Helper::get_option( 'mobile_campaign_bar' ) ? '' : 'dimax-hide-on-mobile';

?>
<div id="campaign-bar" class="campaign-bar <?php echo esc_attr( $class_mobile ); ?>">
    <div class="campaign-bar__campaigns">
		<?php
		foreach ( $campaigns as $campaign ) {
			\Dimax\Theme::instance()->get( 'campaigns' )->campaign_item( $campaign );
		}
		?>
    </div>
</div>