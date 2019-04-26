<?php


/**
 * Plugin Name:       vm-voting-table
 * Description:       wp table update
 * Version:           1.0
 * Author:            vm
 * Author URI:        http://pcspace.cz
 * Text Domain:       VM_
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


function voting_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'project_votes';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE {$table_name} (
		id mediumint(1) NOT NULL AUTO_INCREMENT,
        post_id bigint(20) unsigned NOT NULL,
        user_id bigint(20) unsigned NOT NULL,
        vote_insertTime datetime NOT NULL,
        vote_positive tinyint(1) DEFAULT 0,
        vote_negative tinyint(1) DEFAULT 0,
		PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    $postsTableConstraint = $wpdb->prefix .'fk-posts-vote';
    $usersTableConstraint = $wpdb->prefix .'fk-users-vote';
    $posts_table = $wpdb->prefix .'posts';
    $users_table = $wpdb->prefix . 'users';

    if($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") == $table_name) {
        //existuje vytvorena tabulka?
        $wpdb->query("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$postsTableConstraint}` FOREIGN KEY (`post_id`) REFERENCES `{$posts_table}`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT");

        $wpdb->query("ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$usersTableConstraint}` FOREIGN KEY (`user_id`) REFERENCES `{$users_table}`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT");
    }
}


register_activation_hook( __FILE__, 'voting_table' );


/*
ALTER TABLE `vm_project_votes` ADD CONSTRAINT `posts-vote` FOREIGN KEY (`post_id`) REFERENCES `vm_posts`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `vm_project_votes` DROP FOREIGN KEY `fk_posts-vote`; ALTER TABLE `vm_project_votes` ADD CONSTRAINT `fk_users-vote` FOREIGN KEY (`user_id`) REFERENCES `vm_users`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

INSERT INTO `vm_project_votes` (`id`, `post_id`, `user_id`, `vote_insertTime`, `vote_positive`, `vote_negative`) VALUES ('2', '5', '1', '', '0', '0');
*/