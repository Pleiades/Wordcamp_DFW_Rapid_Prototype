/*!
* FitVids 1.1
*
* Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
*/
;(function( $ ) {

	'use strict';

	$.fn.fitVids = function( options ) {
		var settings = {
			customSelector: null,
			ignore: null
		};

		if ( ! document.getElementById( 'fit-vids-style' ) ) {
			// appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
			var head = document.head || document.getElementsByTagName( 'head' )[0];
			var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
			var div = document.createElement( 'div' );
			div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
			head.appendChild( div.childNodes[1] );
		}

		if ( options ) {
			$.extend( settings, options );
		}

		return this.each(function() {
			var selectors = [
				'iframe[src*="player.vimeo.com"]',
				'iframe[src*="youtube.com"]',
				'iframe[src*="youtube-nocookie.com"]',
				'iframe[src*="kickstarter.com"][src*="video.html"]',
				'object',
				'embed'
			];

			if ( settings.customSelector ) {
				selectors.push( settings.customSelector );
			}

			var ignoreList = '.fitvidsignore';

			if ( settings.ignore ) {
				ignoreList = ignoreList + ', ' + settings.ignore;
			}

			var $allVideos = $( this ).find( selectors.join( ',' ) );
			$allVideos = $allVideos.not( 'object object' ); // SwfObj conflict patch
			$allVideos = $allVideos.not( ignoreList ); // Disable FitVids on this video.

			$allVideos.each(function( count ) {
				var $this = $( this );
				if ( $this.parents( ignoreList ).length > 0 ) {
					return; // Disable FitVids on this video.
				}
				if ( this.tagName.toLowerCase() === 'embed' && $this.parent( 'object' ).length || $this.parent( '.fluid-width-video-wrapper' ).length ) {
					return;
				}
				if ( ( ! $this.css( 'height' ) && ! $this.css( 'width' ) ) && ( isNaN( $this.attr( 'height' ) ) || isNaN( $this.attr( 'width' ) ) ) ) {
					$this.attr( 'height', 9 );
					$this.attr( 'width', 16 );
				}
				var height = ( this.tagName.toLowerCase() === 'object' || ( $this.attr( 'height' ) && ! isNaN( parseInt( $this.attr( 'height' ), 10 ) ) ) ) ? parseInt( $this.attr( 'height' ), 10 ) : $this.height(),
					width = ! isNaN( parseInt( $this.attr( 'width' ), 10 ) ) ? parseInt( $this.attr( 'width' ), 10 ) : $this.width(),
					aspectRatio = height / width;
				if ( ! $this.attr( 'id' ) ) {
					var videoID = 'fitvid' + count;
					$this.attr( 'id', videoID );
				}
				$this.wrap( '<div class="fluid-width-video-wrapper"></div>' ).parent( '.fluid-width-video-wrapper' ).css( 'padding-top', ( aspectRatio * 100 ) + '%' );
				$this.removeAttr( 'height' ).removeAttr( 'width' );
			});
		});
	};
})( window.jQuery || window.Zepto );

/*! Gamajo Accessible Menu - v1.0.0 - 2014-09-08
* https://github.com/GaryJones/accessible-menu
* Copyright (c) 2014 Gary Jones; Licensed MIT */
;(function( $, window, document, undefined ) {
	'use strict';

	var pluginName = 'gamajoAccessibleMenu',
		hoverTimeout = [];

	// The actual plugin constructor
	function Plugin( element, options ) {
		this.element = element;
		// jQuery has an extend method which merges the contents of two or
		// more objects, storing the result in the first object. The first object
		// is generally empty as we don't want to alter the default options for
		// future instances of the plugin
		this.opts = $.extend({}, $.fn[ pluginName ].options, options );
		this.init();
	}

	// Avoid Plugin.prototype conflicts
	$.extend( Plugin.prototype, {
		init: function() {
			$( this.element )
				.on( 'mouseenter.' + pluginName, this.opts.menuItemSelector, this.opts, this.menuItemEnter )
				.on( 'mouseleave.' + pluginName, this.opts.menuItemSelector, this.opts, this.menuItemLeave )
				.find( 'a' )
				.on( 'focus.' + pluginName + ', blur.' + pluginName, this.opts, this.menuItemToggleClass );
		},

		/**
		 * Add class to menu item on hover so it can be delayed on mouseout.
		 *
		 * @since 1.0.0
		 */
		menuItemEnter: function( event ) {
			// Clear all existing hover delays
			$.each( hoverTimeout, function( index ) {
				$( '#' + index ).removeClass( event.data.hoverClass );
				clearTimeout( hoverTimeout[ index ] );
			});
			// Reset list of hover delays
			hoverTimeout = [];

			$( this ).addClass( event.data.hoverClass );
		},

		/**
		 * After a short delay, remove a class when mouse leaves menu item.
		 *
		 * @since 1.0.0
		 */
		menuItemLeave: function( event ) {
			var $self = $( this );
			// Delay removal of class
			hoverTimeout[ this.id ] = setTimeout(function() {
				$self.removeClass( event.data.hoverClass );
			}, event.data.hoverDelay );
		},

		/**
		 * Toggle menu item class when a link fires a focus or blur event.
		 *
		 * @since 1.0.0
		 */
		menuItemToggleClass: function( event ) {
			$( this ).parents( event.data.menuItemSelector ).toggleClass( event.data.hoverClass );
		}
	});

	// A really lightweight plugin wrapper around the constructor,
	// preventing against multiple instantiations
	$.fn[ pluginName ] = function( options ) {
		this.each(function() {
			if ( ! $.data( this, 'plugin_' + pluginName ) ) {
				$.data( this, 'plugin_' + pluginName, new Plugin( this, options ) );
			}
		});

		// chain jQuery functions
		return this;
	};

	$.fn[ pluginName ].options = {
		// The CSS class to add to indicate item is hovered or focused
		hoverClass: 'menu-item-hover',

		// The delay to keep submenus showing after mouse leaves
		hoverDelay: 250,

		// Selector for general menu items. If you remove the default menu item
		// classes, then you may want to call this plugin with this value
		// set to something like 'nav li' or '.menu li'.
		menuItemSelector: '.menu-item'
	};
})( jQuery, window, document );

/**
 * Cookd Pro General Scripts
 *
 * @copyright Copyright (c) 2017, Feast Design Co
 * @license   MIT
 */
(function( $, undefined ) {
	'use strict';

	var $document = $( document ),
		$navs     = $( '.nav-primary, .nav-secondary, .nav-header' );

	/**
	 * Returns a function, that, as long as it continues to be invoked, will not
	 * be triggered. The function will be called after it stops being called for
	 * N milliseconds.
	 *
	 * @source underscore.js
	 * @see http://unscriptable.com/2009/03/20/debouncing-javascript-methods/
	 * @param {Function} func to wrap
	 * @param {Number} wait in ms (`100`)
	 */
	function debounce( func, wait ) {
		var timeout;

		return function() {
			var that = this;
			var args = arguments;

			clearTimeout( timeout );

			timeout = setTimeout( function() {
				timeout = null;
				func.apply( that, args );
			}, wait );
		};
	}

	/**
	 * Check whether or not a given element is visible.
	 *
	 * @param  {object} $object a jQuery object to check
	 * @return {bool} true if the current element is hidden
	 */
	function isHidden( $object ) {
		var element = $object[0];

		if ( 'undefined' === typeof element ) {
			return false;
		}

		return ( null === element.offsetParent );
	}

	function addNavToggles() {
		$navs.before( '<div class="menu-toggle"><span></span></div>' );
		$navs.find( '.sub-menu' ).before( '<div class="sub-menu-toggle"></div>' );
	}

	function showHideNav() {
		$( '.menu-toggle, .sub-menu-toggle' ).on( 'click', function() {
			var $that = $( this );
			$that.toggleClass( 'active' );
			$that.next( 'nav, .sub-menu' ).slideToggle( 'slow' );
		});
	}

	function reflowNavs() {
		if ( isHidden( $navs ) ) {
			$navs.removeAttr( 'style' );
			$( '.sub-menu-toggle, .menu-toggle' ).removeClass( 'active' );
		}
	}

	function navInit() {
		if ( 0 !== $navs.length ) {
			addNavToggles();
			showHideNav();
			$( window ).resize( debounce( reflowNavs, 200 ) );
		}
	}

	function toggleRecipeFilters() {
		if ( ! $( document.body ).hasClass( 'page-template-page-recipes' ) ) {
			return;
		}

		var $sidebar = $( '.sidebar' );

		$( '.filter-toggle' ).on( 'click', function() {
			var $that = $( this );
			$that.toggleClass( 'active' );
			$sidebar.find( '.filter-wrap' ).slideToggle( 'slow' );
		});
	}

	$document.ready(function() {
		$( '#genesis-content' ).fitVids();
		$document.gamajoAccessibleMenu();
		navInit();
		toggleRecipeFilters();
	});
}( jQuery ) );
