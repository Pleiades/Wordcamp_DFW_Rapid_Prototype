/**
 * This script adds the jquery effects to the front page of the Cafe Pro Theme.
 *
 * @package Cafe\JS
 * @author StudioPress
 * @license GPL-2.0+
 */

jQuery(function( $ ){
	
	// Header Height.
	var navHeight          = $('.nav-primary').outerHeight();
	var snavHeight         = $('.nav-secondary').outerHeight();
	var beforeheaderHeight = $('.before-header').outerHeight();
	var windowHeight       = $(window).height();
	var newHeight          = windowHeight - navHeight - snavHeight - beforeheaderHeight;

	$('.front-page-header') .css({'height': newHeight +'px'});
	$('.image-section') .css({'height': windowHeight +'px'});

	$(window).resize(function(){

	var navHeight          = $('.nav-primary').outerHeight();
	var snavHeight         = $('.nav-secondary').outerHeight();
	var beforeheaderHeight = $('.before-header').outerHeight();
	var windowHeight       = $(window).height();
	var newHeight          = windowHeight - navHeight - snavHeight - beforeheaderHeight;

		$('.front-page-header') .css({'height': newHeight +'px'});
		$('.image-section') .css({'height': windowHeight +'px'});

	});

	// Sticky Navigation.
	var headerHeight       = $('.site-header').innerHeight();
	var beforeheaderHeight = $('.before-header').outerHeight();
	var abovenavHeight     = headerHeight + beforeheaderHeight - 1;

	$(window).scroll(function(){

		if ($(document).scrollTop() > abovenavHeight){

			$('.nav-primary').addClass('fixed');

		} else {

			$('.nav-primary').removeClass('fixed');

		}

	});

});