<?php
/**
 * WordPress Multisite Configuration (domain-based)
 *
 * Network tables must exist (wp_blogs, wp_site) before enabling MULTISITE.
 * Each club domain is a separate site in the network (see wp_blogs.domain).
 *
 * @package ExtraSport
 */

define( 'WP_ALLOW_MULTISITE', true );
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
define( 'DOMAIN_CURRENT_SITE', 'extrasport.local' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );
define( 'COOKIEDOMAIN', '.local' );
