<?php
function remove_attachments_on_occasions_delete($post_id) {
    $post_type = get_post_type($post_id);
    
    if ($post_type === 'occasions') {
        // Remove all attachments associated with the post
        $attachments = get_attached_media('', $post_id);
        
        foreach ($attachments as $attachment) {
            wp_delete_attachment($attachment->ID, true);
        }
    }
}
add_action('before_delete_post', 'remove_attachments_on_occasions_delete');