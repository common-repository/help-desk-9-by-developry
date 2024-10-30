'use strict';
( function( $ ) {
  'use strict';
  $( '.flamingo_page_flamingo_inbound' ).append(`
    <div id="hd9-modal" class="hd9-modal d-none">
      <div class="hd9-modal-body">
        <button type="button" name="hd9-modal-close" class="hd9-close-button">
          <span>&times;</span>
        </button>
        <form action="#" method="post" name="hd9-forma">
          <input type="hidden" name="hd9-user-id" value="" />
          <input type="hidden" name="hd9-post-id" value="" />
          <input type="hidden" name="hd9-title" value="" />
          <textarea name="hd9-reply-message" placeholder="Enter your reply message..."></textarea>
          <input type="submit" name="hd9-submit-button" value="Save" class="button button-primary hd9-submit-button" />
        </form>
      </div>
    </div>`
  );
  $( document ).on( 'click', '#hd9-modal .hd9-close-button', function( e) {
    e.preventDefault();
    $( '#hd9-modal input[name="hd9-user-id"]' ).val( '' );
    $( '#hd9-modal input[name="hd9-post-id"]' ).val( '' );
    $( '#hd9-modal input[name="hd9-title"]' ).val( '' );
    $( '#hd9-modal textarea[name="hd9-reply-message"]' ).val( '' );
    $( '#hd9-modal' ).animate( { opacity: 0, left: '-6rem' }, 'fast', function() {
      $( this ).toggleClass( 'd-none' ).toggleClass( 'd-flex' );
      $( 'body' ).css( { 'position' : 'initial', 'width' : 'auto' } );
    } );
  } );
  $( document ).on( 'click', '#hd9-modal .hd9-submit-button', function( e) {
    e.preventDefault();
    $.ajax( {
      type     : "post",
      dataType : "html",
      url      : wphd9.ajax_url,
      data     : { 
        action           : 'hd9_post_reply', 
        user_ID          : $( 'input[name="hd9-user-id"]' ).val(),
        post_ID          : $( 'input[name="hd9-post-id"]' ).val(),
        message_title    : $( 'input[name="hd9-title"]' ).val(), 
        message_content  : $( 'textarea[name="hd9-reply-message"]' ).val() 
      },
      success  : function( responseTxtCode ) { 

        if ( '0' === responseTxtCode ) { // error state
          return false;
        }

        $( '#wpbody-content .wp-header-end' ).after( '<div class="notice notice-success is-dismissible"><p>You have succesfully posted a reply to a customer ticket with Help Desk 9!</p></div>' );

        // Reset the modal form to default.
        $( '#hd9-modal' ).animate( { opacity: 0, left: '-6rem' }, 'fast', function() {
          $( this ).toggleClass( 'd-none' ).toggleClass( 'd-flex' );
          $( 'body' ).css( { 'position' : 'initial', 'width' : 'auto' } );
        } );

        $( 'input[name="hd9-user-id"]' ).val( '' );
        $( 'input[name="hd9-post-id"]' ).val( '' );
        $( 'input[name="hd9-title"]' ).val( '' );
        $( 'textarea[name="hd9-reply-text"]' ).val( '' ); 
      },
      error   : function() {
              alert( 'Something went terribly wrong, please contact the admin of the site right away.' );
          }
    } );
  } );
  $( document ).on( 'click', '.flamingo_page_flamingo_inbound a.reply-link', function( e ) {
    e.preventDefault();
    $( '#hd9-modal input[name="hd9-user-id"]' ).val( $( this ).data( 'userid' ) );
    $( '#hd9-modal input[name="hd9-post-id"]' ).val( $( this ).data( 'postid' ) );
    $( '#hd9-modal input[name="hd9-title"]' ).val( $( this ).data( 'title' ) );
    $( '#hd9-modal textarea[name="hd9-reply-message"]' ).val( '' );
    $( '#hd9-modal' ).toggleClass( 'd-none' ).toggleClass( 'd-flex' ).animate( { 
      opacity: 1, left: '6rem' }, 'fast', function() {
    } );
    $( 'body' ).css( { 'position' : 'fixed', 'width' : '100%' } );
  } );
  $( document ).on( 'click', '.flamingo_page_flamingo_inbound a.close-link', function( e ) {
    e.preventDefault();
    $.ajax( {
      type     : "post",
      dataType : "html",
      url      : wphd9.ajax_url,
      data     : { 
        action  : 'hd9_action_close_status', 
        post_ID : $( this ).data( 'postid' )
      },
      success  : function( responseTxtCode ) { 

        if ( '0' === responseTxtCode ) { // error state
          return false;
        }

        $( '#wpbody-content .wp-header-end' ).after( '<div class="notice notice-success is-dismissible"><p>Your support ticket status has been changed succesfully!</p></div>' );
        setTimeout( function( e ) {
          $( '#wpbody-content' ).load( window.location.href + ' #wpbody-content>*', '' );
        }, 5000 );
      },
      error   : function() {
              alert( 'Something went terribly wrong, please contact the admin of the site right away.' );
          }
    } );
  } );
  $( document ).on( 'click', '.flamingo_page_flamingo_inbound a.re-open-link', function( e ) {
    e.preventDefault();
    $.ajax( {
      type     : "post",
      dataType : "html",
      url      : wphd9.ajax_url,
      data     : { 
        action  : 'hd9_action_reopen_status', 
        post_ID : $( this ).data( 'postid' )
      },
      success  : function( responseTxtCode ) { 

        if ( '0' === responseTxtCode ) { // error state
          return false;
        }

        $( '#wpbody-content .wp-header-end' ).after( '<div class="notice notice-success is-dismissible"><p>Your support ticket status has been changed succesfully!</p></div>' );

        setTimeout( function( e ) {
          $( '#wpbody-content' ).load( window.location.href + ' #wpbody-content>*', '' );
        }, 5000 );
      },
      error   : function() {
              alert( 'Something went terribly wrong, please contact the admin of the site right away.' );
          }
    } );
  } );
} ) ( jQuery );
