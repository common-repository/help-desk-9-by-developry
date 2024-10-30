<div class="wrap wrap-extened">
    <div class="meta-box-sortables ui-sortable">
        <div id="inboundlogdiv" class="postbox">
            <button type="button" class="handlediv" aria-expanded="false">
                <span class="screen-reader-text">Toggle panel: Help Desk 9 Log</span>
                <span class="toggle-indicator" aria-hidden="true"></span>
            </button>
            <h2 class="hndle ui-sortable-handle">
                <span>Help Desk 9 Log</span>
            </h2>
            <div class="inside">
                <?php

                    $meta      = get_post_meta( sanitize_text_field( $_GET['post'] ), '_flamingo_inbound_reply_data' );  

                    $timestamp = array_column( $meta, 'timestamp' );

                    array_shift($meta);

                    @array_multisort( $timestamp, SORT_DESC, $meta );
                ?>
                <?php if ( ! empty( $meta ) && 1 === $meta[0]['show'] ) : ?>
                <table class="widefat message-fields striped">
                    <tbody>
                        <?php foreach ( $meta as $response ) : ?>
                            <?php if ( 1 === $response['show'] ) :  ?>
                                <tr>
                                    <td class="field-title">
                                        <?php if ( get_userdata( $response['user_id'] ) ) : ?>
                                            <?php echo get_userdata( $response['user_id'] )->display_name; ?>&nbsp;<small>replied:</small><br />
                                        <?php else : ?>
                                            <em>'Anonymous'</em><br />
                                        <?php endif; ?>
                                        <small><?php echo date( '\o\n F jS Y \a\t  h:i A', $response['timestamp'] ); ?></small>
                                    </td>
                                    <td class="field-value">
                                        <?php echo wp_specialchars_decode( $response['content'] ); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    There aren't any Help Desk 9 log reply messages posted yet.
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>