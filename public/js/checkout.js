
$().ready(function(){
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});


jQuery(document).ready(function($) {
	 'use strict';	 	 
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





	$('.checkout-button').on('click',function(e){
		e.preventDefault()
		var $self =  $(this)
		var user  =  $self.data('user')
		var x = FlutterwaveCheckout({
			public_key: "FLWPUBK_TEST-d8c9813bd0912d597cc6fddacc11e45f-X",
			tx_ref: "hooli-tx-1920bbtyt",
			amount: user.cart_total,
			currency: user.iso_code,
			redirect_url: // specified redirect URL
			  "",
			meta: {
			  consumer_id: user.id,
			},
			customer: {
			  email: "user.email",
			  name: user.name + " " + user.last_name,
			},
			callback: function (data) {
				if (
					response.respcode == "00" ||
					response.success == true
				) {
					$('#checkout').submit()
					
				} else {
                    notify('danger','top','right',"We could not complete your payment")
				}
			 
				x.close(); // use this to close the modal immediately after payment.
			},
			onclose: function() {
			  // close modal
			},
		
		});
       
	})

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