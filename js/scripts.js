$(document).ready(function(){
/*==================================================================
[ Menu mobile ]*/
$('.btn-show-menu-mobile').on('click', function(){
    $(this).toggleClass('is-active');
    $('.menu-mobile').slideToggle();
});

var arrowMainMenu = $('.arrow-main-menu-m');

for(var i=0; i<arrowMainMenu.length; i++){
    $(arrowMainMenu[i]).on('click', function(){
        $(this).parent().find('.sub-menu-m').slideToggle();
        $(this).toggleClass('turn-arrow-main-menu-m');
    })
}

$(window).resize(function(){
    if($(window).width() >= 992){
        if($('.menu-mobile').css('display') == 'block') {
            $('.menu-mobile').css('display','none');
            $('.btn-show-menu-mobile').toggleClass('is-active');
        }

        $('.sub-menu-m').each(function(){
            if($(this).css('display') == 'block') { console.log('hello');
                $(this).css('display','none');
                $(arrowMainMenu).removeClass('turn-arrow-main-menu-m');
            }
        });
            
    }
});


// -----------------------
$('.js-pscroll').each(function(){
		$(this).css('position','relative');
		$(this).css('overflow','hidden');
		var ps = new PerfectScrollbar(this, {
			wheelSpeed: 1,
			scrollingThreshold: 1000,
			wheelPropagation: false,
		});

		$(window).on('resize', function(){
			ps.update();
		})
	});

		 
   /*==================================================================
    [ +/- num product ]*/
    var changed_val = false;
    $(document).on('click', '.btn-num-product-down', function(){
        var numProduct = Number($(this).next().val());
        	changed_val = true;
        if(numProduct > 0)
        	$(this).next().val(numProduct - 1);
        
    });

    $(document).on('click', '.btn-num-product-up', function(){
        var numProduct = Number($(this).prev().val()),
        	max = $(this).prev().data('max');
        	changed_val = true;
        if(numProduct < max){
        	$(this).prev().val(numProduct + 1);
        }else{
        	$(this).prev().val(numProduct);
        	alert_msg(
			  "only "+max+" are in the stock",
			  'warning'
			);
        }
    });

// Give an active class to the checked checkboxes, size and color checkboxes
 $('.btn-group-toggle .active').find('input').attr('checked', true);
 
// open the collection mega menu
$('#collection').on('click', function(){
	$('.mega_menu').fadeToggle(200).toggleClass('active');
	$(this).toggleClass('selected');
});

// give all inputs and textareas autocomplete off;
$('input, textarea').attr('autocomplete', 'off');
// Loader
let loader = `<div class='d-flex flex-column align-items-center justify-content-center w-100'>
					<img src='https://www.tenaquip.com/images/ajax-loader.gif' width='80'>
				 	<small class='mt-2 text-muted'> Please wait... </small>
				 </div>`;

// ====== Show snackbar function =======
function alert_msg(msg, type){
	$('#snackbar_text').html(msg);
	$('.da_snack').prop('class', 'notification da_snack');
	$('.da_snack').addClass(`active_snackbar ${type}`);
	setTimeout(function(){
		$('.da_snack').removeClass(`active_snackbar ${type}`);
	}, 3000);
}


// For the order style in the profile page
// get the last active order step
$('.order_steps').each(function(){
	$(this).find('.active').last().addClass('active_test');
});


// ####### Load all the products from the DB #########

var cond = [];
let params = (new URL(document.location)).searchParams;
	  if(params.has('cate') == true){
		cond.push('category = "'+params.get('cate')+'"');
		order ='order by created_at desc';
		load_products(cond, order);
	  }else if(params.has('subc') == true){
	  	cond.push('sub_category = "'+params.get('subc')+'"');
		order ='order by created_at desc';
		load_products(cond, order);
	  }else{
	  	load_products('products.image=i.connector','order by created_at desc');
	  }
function load_products(condition, order){
		$.ajax({
			method: 'POST',
			url: 'filteredData.php',
			data: {cond: condition, order: order},
			beforeSend: () => { 
				$('#loaded_products').html(loader);
			},
			success: (data) => {
				$('#loaded_products').html(data);
				$.getScript("js/main.js");
				$.getScript("js/slick-custom.js");
			},
			error: (err) => {
			 alert_msg("Oops! something went wrong, we are hardly working to fix the problem :)", "error" );
			}
		})
}

// Clear the filter
$('#Newness').prop('checked', true);
$('#clear_filter').on('click', function(){
	$('.custom-control-input:checked').not('#Newness').map(function(){
        return $(this).prop('checked', false);
    });
    $('#Newness').prop('checked', true);
    $('.filter_by_color').removeClass('active');
	cond = ['products.image=i.connector'];
	order = 'order by created_at desc';

	load_products(cond, order);
});

// ############### Filter Function ###############

$(document).on('click', '.filter_btn, .sort', function(){

	var cond = $('.filter_btn').filter(':checked').map(function()
        {
            return $(this).val();
        }).get();
	if($('.sort:checked').val() !== undefined){
		order = $('.sort:checked').val();
	}else{
		order = 'order by products.price asc';
	}
	console.log(cond, order);
	
	load_products(cond, order);
});

$('.filter_by_color').on('click', function(){
	$('.filter_by_color').removeClass('active');
	$(this).addClass('active');
});

// ---------- end of filter function -------->



// ####################### Get user cart data ######################
var user = $('#user').val();
load_cart(user);

function load_cart(user_id){
		$.ajax({
			method: 'POST',
			url: 'getUserCart.php',
			data: {user_id: user_id},
			beforeSend: () => { 
				$('#cart_items').html(loader);
			},
			success: (data) => {
				if(data !== false){
					$('#cart_items').html(data);
					let total = $('#total_price').val() == undefined ? 0 : $('#total_price').val();
					$('#total').text(`Total: $ ${total}`);
					let cart_many = $('#cart_many').val() == undefined ? 0 : $('#cart_many').val(); 
					$('.cart-notify').attr('data-notify', cart_many);
				}else{
					$('#cart_items').html(loader);
					 alert_msg( "Oops! Something went wrong, please try again later", "error" );
				}
			},
			error: (err) => {
			  alert_msg("Oops! something went wrong, we are hardly working to fix the problem :)", "error" );
			}
		});
}


// ###################### Add to the cart ########################
$(document).on('click', '.add_to_cart_btn', function(e){
	e.preventDefault();
	// get size, color, qntt
	 var p_id = $(this).data('pid'),
	 	 btn = $(this),
		 size = $(this).closest(p_id).find('#size:checked').val(),
	 	 color = $(this).closest(p_id).find('#color:checked').val(),
	 	 qntt = $(this).closest(p_id).find('#qntt').val(),
	 	 product_id = $(this).closest(p_id).find('#p_id').val();
	 	// console.log(size);
	//make some validation
	if(qntt == 0){
		alert_msg('Please choose the quantity', 'warn');
	}else if(size == undefined){
		alert_msg('You forgot to choose the size', 'warn');
	}else if(color == undefined){
		alert_msg('You forgot to choose the color !', 'warn');
	}else{
	//send it to the server
		$.ajax({
			method: 'POST',
			url: 'addCart.php',
			data: {size: size,
				   color: color, 
				   qntt: qntt,
				   product_id: product_id,
				   user_id: user},
		    beforeSend: function(){
		    	btn.html("<img src='https://www.tenaquip.com/images/ajax-loader.gif' width='30px'>");
		    },
			success: (data) => {
				if(data == 1){
					load_cart(user);
					alert_msg('Yayy! the item added to your cart', 'success');
					if($('#_'+product_id+'').hasClass('show-modal1')){
				     	setTimeout(function(){
							$('#_'+product_id+'').removeClass('show-modal1')
						}, 700);
					}
				}else{
					alert_msg(data, 'warn');
				}
				btn.html('ADD TO CART');
			},
			error: (error) => {
				console.log(error)
			}
		});
	} // end of else
});

// ################## Update User quantity Cart ##################


$(document).on('change', '.update_cart_qntt', function(){
	var price = $(this).data('price'),
		qntt = $(this).val(),
		product_id = $(this).data('pid'),
		product_total = price * qntt,
		cart_id = $(this).data('cartid'),
		sum = parseInt($('#order_summ_total').text()) - parseInt($(this).closest('.cart_p_card').find('.cart_p_price').text()) + product_total;
	$.ajax({
		url: 'shoping-cart.php',
		method: 'POST',
		data: {updated_qntt: qntt, product_id: product_id, cartid: cart_id},
		success: () =>{
			$(this).closest('.cart_p_card').find('.quantity').text(qntt);
			$(this).closest('.cart_p_card').find('.cart_p_price').text(product_total.toFixed(2));
			$('#order_summ_total, #total_for_items').text(sum.toFixed(2));
			load_cart(user);
		},
		error: () => {
			alert_msg('Whoops! Something went wrong please try again later..', 'error');
		}
	});
});


// ##################### Add to wishlist ######################
$(document).on('click', '.btn-addwish-b2', function(){
	var product_id  = $(this).data('id'),
		action = $(this).data('action'),
		user_role = $('#user_role').val();
	if(user_role == 'logged'){
		$.ajax({
			url: 'customer_profile.php',
			method: 'POST',
			data: {action: action, p_id: product_id},
			success: () =>{
				if(action == 'add'){
					$(this).html('<i class="fas fa-heart"></i>');
					$(this).addClass('text-danger');
					$(this).data('action', 'remove');
					$(this).prop('title', 'remove from wishlist');
				}
				else{
					$(this).html('<i class="far fa-heart"></i>');
					$(this).removeClass('text-danger');
					$(this).data('action', 'add');
					$(this).prop('title', 'add to wishlist');
				}
			},
			error: () => {
				alert_msg('Whoops! Something went wrong we are hardly working to fix it :).', 'error');
			}
		});
	}else{
		alert_msg('Hey! <b> you must be logged in </b> to add this to your wishlist', 'info');
	}
});






}); // <-- end of jquery