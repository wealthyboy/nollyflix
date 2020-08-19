
$().ready(function(){
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});


jQuery(document).ready(function($) {
	 'use strict';

	 loadCart()
/*
=============================================== 01. MENU ACTIVATION  ===============================================
*/
	 jQuery('nav#site-navigation-pro ul.sf-menu, nav#sidebar-nav ul.sf-menu').superfish({
			 	popUpSelector: 'ul.sub-menu, .sf-mega', 	// within menu context
	 			delay:      	200,                	// one second delay on mouseout
	 			speed:      	0,               		// faster \ speed
		 		speedOut:    	200,             		// speed of the closing animation
				animation: 		{opacity: 'show'},		// animation out
				animationOut: 	{opacity: 'hide'},		// adnimation in
		 		cssArrows:     	true,              		// set to false
			 	autoArrows:  	true,                    // disable generation of arrow mark-up
		 		disableHI:      true,
	 });
	 

	 /* Sticky Landing Page Header */
	 $('header.sticky-header').scrollToFixed({
		 minWidth:768
	 });
	 
	 
	 /* Remove Fixed Heading on Mobile */
 	$(window).resize(function() {
 	   var width_progression = $(document).width();
 	      if (width_progression < 768) {
			  $('header.sticky-header').trigger('detach.ScrollToFixed');
		  }
 	}).resize();
	 

	 
	 
/*
=============================================== 02. MOBILE NAVIGATION ACTIVATION  ===============================================
*/
 	$('#mobile-bars-icon-pro').click(function(e){
 		e.preventDefault();
 		$('#mobile-navigation-pro').slideToggle(350);
 		$("header#masthead-pro").toggleClass("active-mobile-icon-pro");
		$("header#videohead-pro").toggleClass("active-mobile-icon-pro");
 	});
	
	
   	$('ul#mobile-menu-pro').slimmenu({
   	    resizeWidth: '90000',
   	    collapserTitle: 'Menu',
   	    easingEffect:'easeInOutQuint',
   	    animSpeed:350,
   	    indentChildren: false,
   		childrenIndenter: '- '
   	});
	 
/*
=============================================== 03. FLEXSLIDER  ===============================================
*/
    $('.progression-studios-slider').flexslider({
		slideshow: false,  		/* Autoplay True/False */
		slideshowSpeed: 8000,	/* Autoplay Speed */
		animation: "fade",		/* Slideshow Transition Animation */
		animationSpeed: 800, 	/* Slide Transition Speed */
		directionNav: true,		/* Left/Right Navigation True/False */
		controlNav: true,		/* Bullet Navigaion True/False */
		prevText: "",
		nextText: "",
    });	 

	 	 
/*
=============================================== 04. SCROLL TO TOP BUTTON  ===============================================
*/
	 
   	// browser window scroll (in pixels) after which the "back to top" link is shown
   	var offset = 150,
  	
 	//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
   	offset_opacity = 1200,
  	
 	//duration of the top scrolling animation (in ms)
   	scroll_top_duration = 700,
  	
 	//grab the "back to top" link
   	$back_to_top = $('#pro-scroll-top');

 	//hide or show the "back to top" link
 	$(window).scroll(function(){
   		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
   		if( $(this).scrollTop() > offset_opacity ) { 
   			$back_to_top.addClass('cd-fade-out');
   		}
   	});

 	//smooth scroll to top
 	$back_to_top.on('click', function(event){
 		event.preventDefault();
 		$('body,html').animate({ scrollTop: 0 , }, scroll_top_duration
 	);
 	});


/*
=============================================== 05. User Profile & Search On/Off  ===============================================
*/
	

	$("#header-user-profile-click").click(function() {
		var $this = $("#header-user-profile");
		if ($this.hasClass('active')) {
			$this.removeClass('active').addClass('hide');
		} else {
			$this.addClass('active');
		}		
	});

	
	$("#progression-studios-header-search-icon").on('click', function(e){
		var $this = $(".header-container");
	    if ($this.hasClass('active-search-icon-pro')) {
	        $this.removeClass('active-search-icon-pro').addClass('hide-search-icon-pro');
	    } else {
	        $this.addClass('active-search-icon-pro');
	    }		
	});

	/* If clicking outside of boxes, automatically hide */
	$(document).on('click', function(e){

	    if (e.target.id != 'header-user-profile' && !$('#header-user-profile').find(e.target).length) {
	        if ($("#header-user-profile").hasClass('active')) {
	        	$("#header-user-profile").removeClass('active').addClass('hide');
	        }
	    }
		
	    if (e.target.id != 'progression-studios-header-search-icon' && !$('#progression-studios-header-search-icon').find(e.target).length) {
	        
			if (e.target.id != 'video-search-header' && !$('#video-search-header').find(e.target).length) {
			if ($(".header-container").hasClass('active-search-icon-pro')) {
	        	$(".header-container").removeClass('active-search-icon-pro').addClass('hide-search-icon-pro');
	        }
			}
	    }
		
	});
	
	
/*
=============================================== 07. Carousel JS  ===============================================
*/	
	$('#progression-video-carousel').owlCarousel({
	    margin:4,
	    items:5,
		autoplay:false,
		autoplayTimeout:5000,
		nav: true,
		slideBy:1,
	   
		dots: false,
		autoplayHoverPause:true,
		responsive : {
		    // breakpoint from 0 up
		    0 : {
		        items:1,
		    },
		    // breakpoint from 768 up
		    768 : {
		        items:4,
		    },
		    // breakpoint from 1025 up
		    1025 : {
		        items:5,
		    }
		}
	});

	$('.checkout-button').on('click',function(e){
		e.preventDefault()
		var $self =  $(this)
		var user  =  $self.data('user')
		console.log(user)

	})


	$('.buy-video, .rent-video').on('click',function(e){
		e.preventDefault()
		var $self =  $(this)
		var property = $self.data('prop');
		var type = $self.data('type');
		var price = null
		if (type == 'undefined' || type == ''){
			notify('danger','top','right',"We could not process your request")
            return false;
		} else{
			if (type == 'buy'){
			   var price = property.buy_price
			}
			if (type == 'rent'){
				var price = property.rent_price
			}
		}
		//$(".spinner-border").remove()
		$self.find('.spinner-border').remove();
		$self.append('<span  style=" margin-left: 8px; float: right;"  class="spinner-border  spinner-border-sm" role="status" aria-hidden="true"></span>')
		var payLoad = {
			purchase_type: $self.data('type'),
			id: property.id,
			price: price,
			type: type,
			currency: property.currency,
		}

		$.ajax({
			url: "/carts",
			type:"POST",
			data: payLoad,
		 }).done(function(res) {
			var count = res.count && typeof res.count.original !== 'undefined' ? res.count.original.count : res.count;
			notify('success','top','right',"" +property.title + "  has been added")
			$self.find('.spinner-border').remove();
			$('#cart-count').text('('+count+')');
		 }).fail(function(){
			notify('danger','top','right',"We could not add your item to cart.")
			$self.find('.spinner-border').remove();

		 });
	
		// var x = getpaidSetup({
		// 	PBFPubKey: "FLWPUBK_TEST-d8c9813bd0912d597cc6fddacc11e45f-X",
		// 	customer_email: 're@g.ail.com',
		// 	amount: price,
		// 	currency: property.iso_code,
		// 	country: "NG",
		// 	payment_method: "both",
		// 	txref: "rave-"+ Math.floor((Math.random() * 1000000000) + 1), 
		// 	meta: [{
		// 		metatitle: property.title,
		// 	}],
		// 	onclose: function() {
				
		// 	},
		// 	callback: function(response) {
		// 		if (
		// 			response.respcode == "00" ||
		// 			response.success == true
		// 		) {
		// 			$.ajax({
		// 			   url: "/cart",
		// 			   type:"POST",
		// 			   data: payLoad,
		// 			}).done(function(res) {
		// 			  // location.href="/watch/"+property.id
		// 			  console.log(res)
		// 			});
		// 		} else {
		// 			console.log(false)
		// 		}
			 
		// 		x.close(); // use this to close the modal immediately after payment.
		// 	}
		// });
	
	})

	function loadCart(){
		$.ajax({
			url: "/carts?number=true",
			type:"GET",
		 }).done(function(res) {
			var count = res.count && typeof res.count.original !== 'undefined' ? res.count.original.count : res.count;
			$('#cart-count').text('('+count+')');
		 }).fail(function(){
			notify('danger','top','right',"We could not get your cart.")
		 });
	}

	function notify(color,from,align,msg){
		$.notify({
			icon: "<i class='fas fa-bell'></i>",
			message: msg
		},{
			type: color,
			timer: 3000,
			placement: {
				from: from,
				align: align
			}
		});
	}

	
		 	 
});