'use strict';
( function( $ ) {
  'use strict';
  if ( $( 'form' ).hasClass( 'wpcf7-form') ) {
    $( 'body' ).append(`
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
  }
  $( document ).on( 'wpcf9:mailsent', function( e ) {
    setTimeout( function( e ) {
      $( '#help-desk-9-container' ).load( window.location.href + ' #help-desk-9-container>*', '' );
    }, 3000 );
  } );
  $( document ).on( 'click', 'button.hd9-reply-link', function( e ) {
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
    $( '#help-desk-9-container' ).load( window.location.href + ' #help-desk-9-container>*', '' );
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
        $( '#post-group-' + $( 'input[name="hd9-post-id"]' ).val() ).slideDown( 'slow', function() {
          $( '#help-desk-9-reply-box-container-' + $( 'input[name="hd9-post-id"]' ).val() ).load( window.location.href + ' #help-desk-9-reply-box-container-' + $( 'input[name="hd9-post-id"]' ).val() + '>*', '' );
        } );
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
       error: function() {
              alert( 'Something went terribly wrong, please contact the admin of the site right away.' );
          }
    } );
  } );
  $( document ).on( 'click', 'button[data-toggle="collapse"]', function( e ) {
    e.preventDefault();
    var href = $( this ).attr( 'src' );
    var hash = href.substr( href.indexOf( '#' ) );
    $( this ).toggleText( 'Hide Replies...', 'Show Replies...' );
    $( 'div' + hash + '.collapse' ).slideToggle( 'slow', function() {} );
  } );
  // Helpers
  $.fn.extend( {
      toggleText: function( a, b ) {
          return this.text( this.text() == b ? a : b );
      }
  } );
} ) ( jQuery );
