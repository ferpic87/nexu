<?php
/**
 * SociaRoller
 *
 * @package SociaRoller
 *
 * Author:  Shane Barron - SocialApparatus
 * Website:  http://socia.us
 * 
 */
$site = elgg_get_site_entity();
$site_name = $site->name;
$site_url = elgg_get_site_url();
?>


<a class="elgg-heading-site" href="<?php echo $site_url; ?>">
    <?php echo $site_name; ?>
</a>
