/* globals FWP */
/**
 * Pagination handler for FacetWP
 *
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   MIT
 */
(function( $ ) {
	$( document ).on( 'click', '.pagination a', function() {
		var matches = $( this ).attr( 'href' ).match( /\/page\/(\d+)/ );

		if ( null !== matches ) {
			FWP.paged = parseInt( matches[1], 10 );
		}

		FWP.soft_refresh = true;

		FWP.refresh();

		scroll( 0, 0 );

		return false;
	});
})( jQuery );
