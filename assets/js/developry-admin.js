

( function ( $ ) {

 	$( document ).on( 'click', '.wrap--developry .nav-tabs a', function( e ) {
 	
 		e.preventDefault();
 	
 		$( this ).closest( 'nav' ).find( 'a' ).removeClass( 'current' );
 	
 		$( this ).toggleClass( 'current' );
 	
 		$( this ).closest( 'div.main-container' ).find( '.nav-tab-container' ).removeClass( 'd-block' );
 	
 		$( this ).closest( 'div.main-container' ).find( '#' + $( this ).data( 'target' ) ).addClass( 'd-block' );  	
 	} );

 	$( '.wrap--developry img.zoom' ).parent().css( { 'position' : 'relative', 'overflow' : 'hidden' } );

} ) ( jQuery );