/*
* debouncedresize: special jQuery event that happens once after a window resize
*
* latest version and complete README available on Github:
* https://github.com/louisremi/jquery-smartresize/blob/master/jquery.debouncedresize.js
*
* Copyright 2011 @louis_remi
* Licensed under the MIT license.
*/
var $event = $.event,
$special,
resizeTimeout;

$special = $event.special.debouncedresize = {
	setup: function() {
		$( this ).on( "resize", $special.handler );
	},
	teardown: function() {
		$( this ).off( "resize", $special.handler );
	},
	handler: function( event, execAsap ) {
		// Save the context
		var context = this,
			args = arguments,
			dispatch = function() {
				// set correct event type
				event.type = "debouncedresize";
				$event.dispatch.apply( context, args );
			};

		if ( resizeTimeout ) {
			clearTimeout( resizeTimeout );
		}

		execAsap ?
			dispatch() :
			resizeTimeout = setTimeout( dispatch, $special.threshold );
	},
	threshold: 180
};

// ======================= imagesLoaded Plugin ===============================
// https://github.com/desandro/imagesloaded

// $('#my-container').imagesLoaded(myFunction)
// execute a callback when all images have loaded.
// needed because .load() doesn't work on cached images

// callback function gets image collection as argument
//  this is the container

// original: MIT license. Paul Irish. 2010.
// contributors: Oren Solomianik, David DeSandro, Yiannis Chatzikonstantinou

// blank image data-uri bypasses webkit log warning (thx doug jones)
var BLANK = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';

$.fn.imagesLoaded = function( callback ) {
	var $this = this,
		deferred = $.isFunction($.Deferred) ? $.Deferred() : 0,
		hasNotify = $.isFunction(deferred.notify),
		$images = $this.find('img').add( $this.filter('img') ),
		loaded = [],
		proper = [],
		broken = [];

	// Register deferred callbacks
	if ($.isPlainObject(callback)) {
		$.each(callback, function (key, value) {
			if (key === 'callback') {
				callback = value;
			} else if (deferred) {
				deferred[key](value);
			}
		});
	}

	function doneLoading() {
		var $proper = $(proper),
			$broken = $(broken);

		if ( deferred ) {
			if ( broken.length ) {
				deferred.reject( $images, $proper, $broken );
			} else {
				deferred.resolve( $images );
			}
		}

		if ( $.isFunction( callback ) ) {
			callback.call( $this, $images, $proper, $broken );
		}
	}

	function imgLoaded( img, isBroken ) {
		// don't proceed if BLANK image, or image is already loaded
		if ( img.src === BLANK || $.inArray( img, loaded ) !== -1 ) {
			return;
		}

		// store element in loaded images array
		loaded.push( img );

		// keep track of broken and properly loaded images
		if ( isBroken ) {
			broken.push( img );
		} else {
			proper.push( img );
		}

		// cache image and its state for future calls
		$.data( img, 'imagesLoaded', { isBroken: isBroken, src: img.src } );

		// trigger deferred progress method if present
		if ( hasNotify ) {
			deferred.notifyWith( $(img), [ isBroken, $images, $(proper), $(broken) ] );
		}

		// call doneLoading and clean listeners if all images are loaded
		if ( $images.length === loaded.length ){
			setTimeout( doneLoading );
			$images.unbind( '.imagesLoaded' );
		}
	}

	// if no images, trigger immediately
	if ( !$images.length ) {
		doneLoading();
	} else {
		$images.bind( 'load.imagesLoaded error.imagesLoaded', function( event ){
			// trigger imgLoaded
			imgLoaded( event.target, event.type === 'error' );
		}).each( function( i, el ) {
			var src = el.src;

			// find out if this image has been already checked for status
			// if it was, and src has not changed, call imgLoaded on it
			var cached = $.data( el, 'imagesLoaded' );
			if ( cached && cached.src === src ) {
				imgLoaded( el, cached.isBroken );
				return;
			}

			// if complete is true and browser supports natural sizes, try
			// to check for image status manually
			if ( el.complete && el.naturalWidth !== undefined ) {
				imgLoaded( el, el.naturalWidth === 0 || el.naturalHeight === 0 );
				return;
			}

			// cached images don't fire load sometimes, so we reset src, but only when
			// dealing with IE, or image is complete (loaded) and failed manual check
			// webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
			if ( el.readyState || el.complete ) {
				el.src = BLANK;
				el.src = src;
			}
		});
	}

	return deferred ? deferred.promise( $this ) : $this;
};

var Grid = (function() {

		// list of items
	var $grid = $( '#og-grid' ),
		// the items
		$items = $grid.children( 'li' ),
		// current expanded item's index
		current = -1,
		// position (top) of the expanded item
		// used to know if the preview will expand in a different row
		previewPos = -1,
		// extra amount of pixels to scroll the window
		scrollExtra = 0,
		// extra margin when expanded (between preview overlay and the next items)
		marginExpanded = 10,
		$window = $( window ), winsize,
		$body = $( 'html, body' ),
		// transitionend events
		transEndEventNames = {
			'WebkitTransition' : 'webkitTransitionEnd',
			'MozTransition' : 'transitionend',
			'OTransition' : 'oTransitionEnd',
			'msTransition' : 'MSTransitionEnd',
			'transition' : 'transitionend'
		},
		transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ],
		// support for csstransitions
		support = Modernizr.csstransitions,
		// default settings
		settings = {
			minHeight : 500,
			speed : 350,
			easing : 'ease'
		};

	function init( config ) {

		// the settings..
		settings = $.extend( true, {}, settings, config );

		// preload all images
		$grid.imagesLoaded( function() {

			// save itemÂ´s size and offset
			saveItemInfo( true );
			// get windowÂ´s size
			getWinSize();
			// initialize some events
			initEvents();

		} );

	}

	// add more items to the grid.
	// the new items need to appended to the grid.
	// after that call Grid.addItems(theItems);
	function addItems( $newitems ) {

		$items = $items.add( $newitems );

		$newitems.each( function() {
			var $item = $( this );
			$item.data( {
				offsetTop : $item.offset().top,
				height : $item.height()
			} );
		} );

		initItemsEvents( $newitems );

	}

	// saves the itemÂ´s offset top and height (if saveheight is true)
	function saveItemInfo( saveheight ) {
		$items.each( function() {
			var $item = $( this );
			$item.data( 'offsetTop', $item.offset().top );
			if( saveheight ) {
				$item.data( 'height', $item.height() );
			}
		} );
	}

	function initEvents() {

		// when clicking an item, show the preview with the itemÂ´s info and large image.
		// close the item if already expanded.
		// also close if clicking on the itemÂ´s cross
		initItemsEvents( $items );

		// on window resize get the windowÂ´s size again
		// reset some values..
		$window.on( 'debouncedresize', function() {

			scrollExtra = 0;
			previewPos = -1;
			// save itemÂ´s offset
			saveItemInfo();
			getWinSize();
			var preview = $.data( this, 'preview' );
			if( typeof preview != 'undefined' ) {
				hidePreview();
			}

		} );

	}

	function initItemsEvents( $items ) {
		$items.on( 'click', 'span.og-close', function() {
			hidePreview();
			return false;
		} ).children( 'a' ).on( 'click', function(e) {

			var $item = $( this ).parent();
			// check if item already opened
			current === $item.index() ? hidePreview() : showPreview( $item );
			return false;

		} );
	}

	function getWinSize() {
		winsize = { width : $window.width(), height : $window.height() };
	}

	function showPreview( $item ) {

		var preview = $.data( this, 'preview' ),
			// itemÂ´s offset top
			position = $item.data( 'offsetTop' );

		scrollExtra = 0;

		// if a preview exists and previewPos is different (different row) from itemÂ´s top then close it
		if( typeof preview != 'undefined' ) {

			// not in the same row
			if( previewPos !== position ) {
				// if position > previewPos then we need to take te current previewÂ´s height in consideration when scrolling the window
				if( position > previewPos ) {
					scrollExtra = preview.height;
				}
				hidePreview();
			}
			// same row
			else {
				preview.update( $item );
				return false;
			}

		}

		// update previewPos
		previewPos = position;
		// initialize new preview for the clicked item
		preview = $.data( this, 'preview', new Preview( $item ) );
		// expand preview overlay
		preview.open();

	}

	function hidePreview() {
		current = -1;
		var preview = $.data( this, 'preview' );
		preview.close();
		$.removeData( this, 'preview' );
	}

	// the preview obj / overlay
	function Preview( $item ) {
		this.$item = $item;
		this.expandedIdx = this.$item.index();
		this.create();
		this.update();
	}

	Preview.prototype = {
		create : function() {
			// create Preview structure:
			this.$title = $( '<p class="title"></p>' );
			this.$description = $( '<li class="description"></li>' );
			this.$valuetype1 = $( '<li class="valuetype1"></li>' );
			this.$valuetype2 = $( '<li class="valuetype2"></li>' );
			this.$valuetype3 = $( '<li class="valuetype3"></li>' );

			this.$alt_title1 = $( '<li class="alt_title1"></li>' );
			this.$alt_title2 = $( '<li class="alt_title2"></li>' );
			this.$alt_title3 = $( '<li class="alt_title3"></li>' );
			this.$alt_title4 = $( '<li class="alt_title4"></li>' );
			this.$alt_title = $( '<h4>Alternate Title</h4>' );
			this.$alt_div = $( '<ul class="alternates mb-4"></ul>' ).append(this.$alt_title,this.$alt_title1,this.$alt_title2,this.$alt_title3,this.$alt_title4);

			this.$work_activity1 = $( '<li class="work_activity1"></li>' );
			this.$work_activity2 = $( '<li class="work_activity2"></li>' );
			this.$work_activity3 = $( '<li class="work_activity3"></li>' );
			this.$work_activity4 = $( '<li class="work_activity4"></li>' );
			this.$work_activity5 = $( '<li class="work_activity5"></li>' );
			this.$work_activity6 = $( '<li class="work_activity6"></li>' );
			this.$work_activity7 = $( '<li class="work_activity7"></li>' );

			this.$education_norm = $('<p></p>' );
			this.$education_min = $( '<p></p>' );
			this.$education_min_title = $( '<h4>Rewards and Risks</h4>' );
			this.$rewards_risks = $('<div class="rewards-risks mb-4"></div>').append(this.$education_min_title,this.$education_norm, this.$education_min);

			this.$tech_skill_kn1 = $( '<li class="tech_skill_kn1"></li>' );
			this.$tech_skill_kn2 = $( '<li class="tech_skill_kn2"></li>' );
			this.$tech_skill_kn3 = $( '<li class="tech_skill_kn3"></li>' );
			this.$tech_skill_kn4 = $( '<li class="tech_skill_kn4"></li>' );

			this.$med_wage_title = $( '<h4>Average Wage</h4>' );
			this.$med_wage = $( '<p></p>' );
			this.$med_wage_div = $( '<div class="med_wage"></div>' ).append(this.$med_wage_title,this.$med_wage);

			this.$openings_count = $( '<li class="openings_count"></li>' );
			this.$openings_rate = $( '<li class="openings_rate"></li>' );
			this.$openings_rate_cat = $( '<li class="openings_rate_cat"></li>' );
			this.$openings_title = $( '<h4>Total Openings</h4>' );
			this.$openings_div = $( '<ul class="openings mb-4"></ul>' ).append(this.$openings_title,this.$openings_rate_cat,this.$openings_rate,this.$openings_count);

			this.$pct_self_emp = $( '<p class="pct_self_emp"></p>' );
			this.$pct_self_emp_cat = $( '<p class="pct_self_emp_cat"></p>' );
			this.$pct_self_emp_title = $( '<h4>Going out on your own</h4>' );
			this.$pct_self_div = $( '<div class="self_emp mb-4"></div>' ).append(this.$pct_self_emp_title,this.$pct_self_emp_cat,this.$pct_self_emp);

			this.$href = $( '<a href="#">Visit website</a>' );
			this.$row111 = $( '<a href="#">Visit website</a>' );

			this.goodForPeopletitle = $('<h4>Good For People Who Are Good At:</h4>');
			this.$goodForPeople = $('<ul class="good-for-people mb-4"></ul>').append(this.goodForPeopletitle,this.$valuetype1, this.$valuetype2, this.$valuetype3);
			this.$majorActivitiesTitle = $('<h4>Major Activities:</h4>');
			this.$majorActivitiesUL = $('<ul class="major-activities mb-4"></ul>').append(this.$majorActivitiesTitle,this.$work_activity1, this.$work_activity2, this.$work_activity3, this.$work_activity4, this.$work_activity5, this.$work_activity6, this.$work_activity7);

			this.$majorSpecialtiesTitle = $('<h4>Major Specialities:</h4>');
			this.$majorSpecialtiesUL = $('<ul class="major-specialties mb-4"></ul>').append(this.$majorSpecialtiesTitle,this.$tech_skill_kn1, this.$tech_skill_kn2, this.$tech_skill_kn3, this.$tech_skill_kn4);

			this.$loading = $( '<div class="og-loading"></div>' );
			this.$fullimage = $( '<div class="og-fullimg"></div>' ).append( this.$loading );
			this.$closePreview = $( '<span class="og-close"></span>' );
			this.$columnLeft = $('<div class="col-lg-6"></div>').append(this.$goodForPeople, this.$majorActivitiesUL);
			this.$columnReft = $('<div class="col-lg-3"></div>').append(this.$majorSpecialtiesUL, this.$med_wage_div, this.$openings_div,this.$alt_div, this.$rewards_risks );
			this.$columnReft2 = $('<div class="col-lg-3"></div>').append(this.$openings_div,this.$alt_div,this.$pct_self_div );
			this.$previewInner = $( '<div class="og-expander-inner row"></div>' ).append( this.$closePreview, this.$columnLeft, this.$columnReft,this.$columnReft2);

			this.$previewEl = $( '<div class="og-expander"></div>' ).append( this.$previewInner );

			// append preview element to the item
			this.$item.append( this.getEl() );
			// set the transitions for the preview and the item
			if( support ) {
				this.setTransition();
			}
		},
		update : function( $item ) {

			if( $item ) {
				this.$item = $item;
			}

			// if already expanded remove class "og-expanded" from current item and add it to new item
			if( current !== -1 ) {
				var $currentItem = $items.eq( current );
				$currentItem.removeClass( 'og-expanded' );
				this.$item.addClass( 'og-expanded' );
				// position the preview correctly
				this.positionPreview();
			}

			// update current value
			current = this.$item.index();

			// update previewÂ´s content
			var $itemEl = this.$item.children( 'a' ),
				eldata = {
					href : $itemEl.attr( 'href' ),
					largesrc : $itemEl.data( 'largesrc' ),
					title : $itemEl.data( 'title' ),
					description : $itemEl.data( 'description' ),
					valuetype1: $itemEl.data( 'valuetype1' ),
					valuetype2: $itemEl.data( 'valuetype2' ),
					valuetype3: $itemEl.data( 'valuetype3' ),
					alt_title1: $itemEl.data( 'alt_title1' ),
					alt_title2: $itemEl.data( 'alt_title2' ),
					alt_title3: $itemEl.data( 'alt_title3' ),
					alt_title4: $itemEl.data( 'alt_title4' ),
					work_activity1: $itemEl.data( 'work_activity1' ),
					work_activity2: $itemEl.data( 'work_activity2' ),
					work_activity3: $itemEl.data( 'work_activity3' ),
					work_activity4: $itemEl.data( 'work_activity4' ),
					work_activity5: $itemEl.data( 'work_activity5' ),
					work_activity6: $itemEl.data( 'work_activity6' ),
					work_activity7: $itemEl.data( 'work_activity7' ),
					education_norm: $itemEl.data( 'education_norm' ),
					education_min: $itemEl.data( 'education_min' ),
					tech_skill_kn1: $itemEl.data( 'tech_skill_kn1' ),
					tech_skill_kn2: $itemEl.data( 'tech_skill_kn2' ),
					tech_skill_kn3: $itemEl.data( 'tech_skill_kn3' ),
					tech_skill_kn4: $itemEl.data( 'tech_skill_kn4' ),
					med_wage: $itemEl.data( 'med_wage' ),
					openings_count: $itemEl.data( 'openings_count' ),
					openings_rate: $itemEl.data( 'openings_rate' ),
					openings_rate_cat: $itemEl.data( 'openings_rate_cat' ),
					pct_self_emp: $itemEl.data( 'pct_self_emp' ),
					pct_self_emp_cat: $itemEl.data( 'pct_self_emp_cat' )
				};

			this.$title.html( eldata.title );
			this.$description.html( eldata.description );
			this.$href.attr( 'href', eldata.href );

			this.$valuetype1.html(eldata.valuetype1);
			this.$valuetype2.html(eldata.valuetype2);
			this.$valuetype3.html(eldata.valuetype3);

			this.$alt_title1.html(eldata.alt_title1);
			this.$alt_title2.html(eldata.alt_title2);
			this.$alt_title3.html(eldata.alt_title3);
			this.$alt_title4.html(eldata.alt_title4);

			this.$work_activity1.html(eldata.work_activity1);
			this.$work_activity2.html(eldata.work_activity2);
			this.$work_activity3.html(eldata.work_activity3);
			this.$work_activity4.html(eldata.work_activity4);
			this.$work_activity5.html(eldata.work_activity5);
			this.$work_activity6.html(eldata.work_activity6);
			this.$work_activity7.html(eldata.work_activity7);

			this.$education_norm.html(eldata.education_norm);
			this.$education_min.html(eldata.education_min);

			this.$tech_skill_kn1.html(eldata.tech_skill_kn1);
			this.$tech_skill_kn2.html(eldata.tech_skill_kn2);
			this.$tech_skill_kn3.html(eldata.tech_skill_kn3);
			this.$tech_skill_kn4.html(eldata.tech_skill_kn4);

			this.$med_wage.html(eldata.med_wage);

			this.$openings_count.html(eldata.openings_count);
			this.$openings_rate.html(eldata.openings_rate);
			this.$openings_rate_cat.html(eldata.openings_rate_cat);

			this.$pct_self_emp.html(eldata.pct_self_emp);
			this.$pct_self_emp_cat.html(eldata.pct_self_emp_cat);

			var self = this;

			// remove the current image in the preview
			if( typeof self.$largeImg != 'undefined' ) {
				self.$largeImg.remove();
			}

			// preload large image and add it to the preview
			// for smaller screens we donÂ´t display the large image (the media query will hide the fullimage wrapper)
			if( self.$fullimage.is( ':visible' ) ) {
				this.$loading.show();
				$( '<img/>' ).load( function() {
					var $img = $( this );
					if( $img.attr( 'src' ) === self.$item.children('a').data( 'largesrc' ) ) {
						self.$loading.hide();
						self.$fullimage.find( 'img' ).remove();
						self.$largeImg = $img.fadeIn( 350 );
						self.$fullimage.append( self.$largeImg );
					}
				} ).attr( 'src', eldata.largesrc );
			}

		},
		open : function() {

			setTimeout( $.proxy( function() {
				// set the height for the preview and the item
				this.setHeights();
				// scroll to position the preview in the right place
				this.positionPreview();
			}, this ), 25 );

		},
		close : function() {

			var self = this,
				onEndFn = function() {
					if( support ) {
						$( this ).off( transEndEventName );
					}
					self.$item.removeClass( 'og-expanded' );
					self.$previewEl.remove();
				};

			setTimeout( $.proxy( function() {

				if( typeof this.$largeImg !== 'undefined' ) {
					this.$largeImg.fadeOut( 'fast' );
				}
				this.$previewEl.css( 'height', 0 );
				// the current expanded item (might be different from this.$item)
				var $expandedItem = $items.eq( this.expandedIdx );
				$expandedItem.css( 'height', $expandedItem.data( 'height' ) ).on( transEndEventName, onEndFn );

				if( !support ) {
					onEndFn.call();
				}

			}, this ), 25 );

			return false;

		},
		calcHeight : function() {

			var heightPreview = winsize.height - this.$item.data( 'height' ) - marginExpanded,
				itemHeight = winsize.height;

			if( heightPreview < settings.minHeight ) {
				heightPreview = settings.minHeight;
				itemHeight = settings.minHeight + this.$item.data( 'height' ) + marginExpanded;
			}

			this.height = heightPreview;
			this.itemHeight = itemHeight;

		},
		setHeights : function() {

			var self = this,
				onEndFn = function() {
					if( support ) {
						self.$item.off( transEndEventName );
					}
					self.$item.addClass( 'og-expanded' );
				};

			this.calcHeight();
			this.$previewEl.css( 'height', this.height );
			this.$item.css( 'height', this.itemHeight ).on( transEndEventName, onEndFn );

			if( !support ) {
				onEndFn.call();
			}

		},
		positionPreview : function() {

			// scroll page
			// case 1 : preview height + item height fits in windowÂ´s height
			// case 2 : preview height + item height does not fit in windowÂ´s height and preview height is smaller than windowÂ´s height
			// case 3 : preview height + item height does not fit in windowÂ´s height and preview height is bigger than windowÂ´s height
			var position = this.$item.data( 'offsetTop' ),
				previewOffsetT = this.$previewEl.offset().top - scrollExtra,
				scrollVal = this.height + this.$item.data( 'height' ) + marginExpanded <= winsize.height ? position : this.height < winsize.height ? previewOffsetT - ( winsize.height - this.height ) : previewOffsetT;

			$body.animate( { scrollTop : scrollVal }, settings.speed );

		},
		setTransition  : function() {
			this.$previewEl.css( 'transition', 'height ' + settings.speed + 'ms ' + settings.easing );
			this.$item.css( 'transition', 'height ' + settings.speed + 'ms ' + settings.easing );
		},
		getEl : function() {
			return this.$previewEl;
		}
	}

	return {
		init : init,
		addItems : addItems
	};

})();
