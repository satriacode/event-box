<?php

class EventBoxInit {
    
    function event_box_create_plugin_activation()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql_registration = "CREATE TABLE " . TABLE_REGISTRATION . " (
            `user_id` INT(11) NOT NULL AUTO_INCREMENT,
            `form_id` INT(11) DEFAULT NULL,
            `first_name` VARCHAR(220) DEFAULT NULL,
            `last_name` VARCHAR(220) DEFAULT NULL,
            `email` VARCHAR(220) DEFAULT NULL,
            `phone` VARCHAR(220) DEFAULT NULL,
            `comment` TEXT NULL,
            `event_date` DATE DEFAULT NULL,
            `creation_time` DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(user_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

        $sql_setup = "CREATE TABLE " . TABLE_SETUP . " (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(225) DEFAULT NULL,
            `place` VARCHAR(225) DEFAULT NULL,
            `body` TEXT NULL,
            `quota` INT(11) NOT NULL DEFAULT '100',
            `event_date` DATE DEFAULT NULL,
            `status` ENUM('0','1','2') default '0',
            `creation_time` DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
            ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

        if ($wpdb->get_var("SHOW TABLES LIKE '" . TABLE_REGISTRATION . "'") != TABLE_REGISTRATION) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql_registration);
            dbDelta($sql_setup);
        }
    }
}