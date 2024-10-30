<div id="help-desk-9-container">
    <p class="alignright">
        <a href="#h3-new-support-ticket" class="button submit-button">New Support Ticket...</a>
    </p>
    <br clear="all" />
    <table class="table">
        <?php while ( $query->have_posts() ) : $query->the_post(); ?> 
            <tr>
                <td valign="top" colspan="2">
                    <?php 

                        $fields   = get_post_meta( $post->ID, '_fields', true );
                        
                        foreach ($fields as $key => $value) 
                        {
                            if ( preg_match('/subject|message|description|input|title|content|textarea/', $key) ) 
                            { // some kind of column filter
                                print '<p><strong>' . ucwords( str_replace( '-', ' ', $key ) ) . '</strong> : ' . get_post_meta( $post->ID, '_field_' . $key )[0] . '</p>';
                            }
                        }
                    ?>
                </td>
            </tr>
            <?php 

                $meta = get_post_meta( $post->ID, '_flamingo_inbound_reply_data' ); // all associated meta

                if ( ! empty( $meta[0] ) && 0 !== $meta[0]['show'] ) :

                    unset( $meta[0] );
                    
                    $timestamp = array_column( $meta, 'timestamp' );

                    array_multisort( $timestamp, SORT_DESC, $meta );
            ?>
                <tr>
                    <td nowrap valign="top">
                        <button src="#post-group-<?php print $post->ID; ?>" data-toggle="collapse">
                            Hide Replies...
                        </button> &nbsp;
                        <button src="#" class="hd9-reply-link" data-postid="<?php echo $post->ID; ?>" data-userid="<?php echo $user_ID; ?>" data-title="Re: User reply to #<?php echo $post->ID; ?>" >
                            Post a Reply
                        </button>
                    </td>
                    <td width="100%">
                        <p><big><strong>#<?php print $post->ID; ?></strong></big></p><br />
                        <div id="post-group-<?php print $post->ID; ?>" class="collapse">
                            <div id="help-desk-9-reply-box-container-<?php print $post->ID; ?>">
                                <?php foreach ( $meta as $response ) : ?>
                                    <?php if ( 1 === $response['show'] ) :  ?>
                                        <div class="help-desk-9-reply-box <?php if ( user_can( get_userdata( $response['user_id'] ), 'manage_options' ) ) echo 'help-desk-9-reply-box__admin'; ?>">
                                            <p>
                                                <?php if ( get_userdata( $response['user_id'] ) ) : ?>
                                                    <strong><?php echo get_userdata( $response['user_id'] )->display_name; ?></strong>
                                                <?php else : ?>
                                                    <em>Anonymous</em>
                                                <?php endif; ?>
                                                posted a reply:<br />
                                                <small><?php echo date( '\o\n F jS Y \a\t h:i A', $response['timestamp'] ); ?></small><br />
                                                <?php echo wp_specialchars_decode( $response['content'] ); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="2">
                        <p>
                            <strong>Notice:</strong> The support ticket is pending or has been resolved and archived.
                        </p>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endwhile; ?>
    </table>
</div>