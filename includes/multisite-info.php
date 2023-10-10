<?php namespace WSUWP\Plugin\NetworkInfo;


class WSUWP_Multisite_Info {

    public function __construct() {
        add_action( 'network_admin_menu', array( $this, 'add_menu_page' ) );
    }

    public function add_menu_page() {
        add_menu_page(
            'WSU Multisite Info',
            'WSU Multisite Info',
            'manage_network',
            'wsuwp-multisite-info',
            array( $this, 'render_info_table' ),
            'dashicons-networking',
            1
        );
    }


    // Creates the table on Admin dashboard.
    public function render_info_table() {
        $sites = get_sites();

        include Plugin::get('dir') . 'assets/templates/wsuwp-multisite-info-table.php';
    }

    // Retrieve the registration date for a site.
    public function get_registration_date( $site_id ) {
        $site_details = get_blog_details( $site_id );
        $registration_date = $site_details->registered;

        return $registration_date;
    }


    // Retrieve the last content update date for a site.
    public function get_last_content_update( $site_id ) {
        switch_to_blog( $site_id );

        $args = array(
            'post_type'      => 'any',
            'posts_per_page' => 1,
            'orderby'        => 'modified',
            'order'          => 'DESC',
        );

        $query = new \WP_Query( $args );
        $last_updated = '';

        if ( $query->have_posts() ) {
            $query->the_post();
            $last_updated = get_the_modified_date();
        }

        wp_reset_postdata();
        restore_current_blog();

        return $last_updated;
    }


     // Retrieve the count of users for a site.
    public function get_user_count( $site_id ) {
        switch_to_blog( $site_id );
        $users_count = count_users();
        restore_current_blog();

        return $users_count;
    }


    // Retrieve the count of pages for a site.
    public function get_page_count( $site_id ) {
        switch_to_blog( $site_id );
        $count = wp_count_posts( 'page' )->publish;
        restore_current_blog();

        return $count;
    }

    // Retrieve the count of posts for a site.
    public function get_post_count( $site_id ) {
        switch_to_blog( $site_id );
        $count = wp_count_posts( 'post' )->publish;
        restore_current_blog();

        return $count;
    }

    // Retrieve the count of a events for a site.
    public function get_custom_post_type_count( $site_id ) {
        switch_to_blog( $site_id );

        $args = array(
            'post_type'      => 'tribe_events',
            'posts_per_page' => -1,
        );

        $query = new \WP_Query( $args );
        $count = $query->post_count;

        restore_current_blog();

        return $count;
    }

    
    // Retrieve the GA4 code for a specific website.
    public function get_input_field_setting_GA4( $site_id ) {
        switch_to_blog( $site_id );

        $setting_value = get_option( 'wsuwp_ga4_id' );

        restore_current_blog();

        return $setting_value;
    }

    // Check to see if the website is indexed or not.
    public function get_input_field_setting_index_site( $site_id ) {
        switch_to_blog( $site_id );

        $setting_value = get_option( 'blog_public' );

        restore_current_blog();

        return $setting_value;
    }
}

new WSUWP_Multisite_Info();