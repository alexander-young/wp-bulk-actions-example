<?php

add_filter('bulk_actions-edit-post', function(){
    $bulk_actions['email_to_me'] = 'Email to Me';
    return $bulk_actions;
});

add_filter('handle_bulk_actions-edit-post', 'handle_bulk_post_email', 10, 3);
function handle_bulk_post_email($redirect_to, $doaction, $post_ids)
{
    if ($doaction !== 'email_to_me') {
        return $redirect_to;
    }

    $message = '';
    foreach ($post_ids as $post_id) {
        $message .= get_the_title($post_id) . "\n \n";
    }

    wp_mail('dev-email@flywheel.local', 'List Of Posts', $message);
    $redirect_to = add_query_arg('bulk_emailed_posts', count($post_ids), $redirect_to);
    return $redirect_to;
}


add_action('admin_notices', function(){

    if (!empty($_REQUEST['bulk_emailed_posts'])) {
        $emailed_count = intval($_REQUEST['bulk_emailed_posts']);
        printf('<div id="message" class="updated fade">Emailed %d post(s) to Alex</div>', $emailed_count);
    }

});
