<?php

function register_required_plugins() {
    $plugins = array(
        array(
            'name' => 'Jetpack by WordPress.com',
            'slug' => 'jetpack',
            'required' => false
        )
    );

    $config = array(
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => true,
        'message'      => ''
    );

    tgmpa($plugins, $config);
}
// add_action('tgmpa_register', 'register_required_plugins');
