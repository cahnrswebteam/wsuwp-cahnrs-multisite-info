<div class="wrap">
    <h1>WSUWP Multisite Info Table</h1>
    <table class="wsuwp-multisite-information wp-list-table widefat display">
        <thead>
            <tr>
                <th>Website</th>
                <th>GA4</th>
                <th>Indexed</th>
                <th>Theme</th>
                <th>Plugins</th>
                <th>Users</th>
                <th>Pages</th>
                <th>Posts</th>
                <th>Events</th>
                <th>Registered</th>
                <th>Last Updated</th>
            </tr>
        </thead>

        <tbody>
            <?php 
                foreach ( $sites as $site ) {
                    $site_id = $site->blog_id;
                    $site_url = get_home_url( $site_id );
                    $theme = get_blog_option( $site_id, 'current_theme' );
                    $plugins = get_blog_option( $site_id, 'active_plugins', array() );
        
                    // Get only the activated plugins for the current site
                    $active_plugins = array();
                    foreach ( $plugins as $plugin ) {
                        $plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
                        $active_plugins[] = $plugin_data['Name'];
                    }
        
                    $users_count = $this->get_user_count( $site_id );
                    $users = $users_count['total_users'];
                    $pages_count = $this->get_page_count( $site_id );
                    $posts_count = $this->get_post_count( $site_id );
                    $custom_post_type_count = $this->get_custom_post_type_count( $site_id );
        
                    // Retrieve the input field setting for the current site
                    $input_field_setting_GA4 = $this->get_input_field_setting_GA4( $site_id );
                    $input_field_settings_index_site = $this->get_input_field_setting_index_site( $site_id );
                    $input_field_settings_index_site = $input_field_settings_index_site ? $input_field_settings_index_site = "Yes" : "No";
                    
                    // Get site registration date
                    $registration_date = $this->get_registration_date( $site_id );
                    $registration_date = date_format(new \DateTime($registration_date), 'M-d-y');
                    
                    // Get last content update date
                    $last_updated = $this->get_last_content_update( $site_id );
        
                    // Format dates for display and set data-order attribute
                    $formatted_registration_date = date_format(new \DateTime($registration_date), 'm-d-y');
                    $formatted_last_updated = date_format(new \DateTime($last_updated), 'm-d-y');
                    
                    echo '<tr>';
                    echo '<td><a href="' . $site_url . '">' . $site_url . '</a></td>';
                    echo '<td>' . $input_field_setting_GA4 . '</td>';
                    echo '<td>' . $input_field_settings_index_site . '</td>';
                    echo '<td>' . $theme . '</td>';
                    echo '<td>' . implode( '<br>', $active_plugins ) . '</td>';
                    echo '<td><a href="' . $site_url . '/wp-admin/users.php">' . $users . '</a></td>';
                    echo '<td><a href="' . $site_url . '/wp-admin/edit.php?post_type=page">' . $pages_count . '</a></td>';
                    echo '<td><a href="' . $site_url . '/wp-admin/edit.php">' . $posts_count . '</a></td>';
                    echo '<td><a href="' . $site_url . '/wp-admin/edit.php?post_type=tribe_events">' . $custom_post_type_count . '</a></td>';
                    echo '<td data-order="' . strtotime($registration_date) . '">' . $formatted_registration_date . '</td>';
                    echo '<td data-order="' . strtotime($last_updated) . '">' . $formatted_last_updated . '</td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>
        
</div>
