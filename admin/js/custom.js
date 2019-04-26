 $(document).ready(function(){

/*================================
  Close alert
 =================================*/
$( ".main" ).each(function(){

      $(this).click(function() {
       $(this).css("display","none");
      });
      
});

// ##################### Active class to the nav bar elements #####################
   let pathname = location.pathname,
       url  = pathname.slice(pathname.lastIndexOf('/') + 1),
      elem = $('.navbar__list li[data-url="' + url + '"]');

    elem.addClass('active');
   if (elem.closest('ul').hasClass('navbar__sub-list'))
   {
     elem.closest('.has-sub').addClass('active');
     elem.closest('.has-sub').find('.js-arrow').addClass('open');
     elem.closest('.has-sub').find('.arrow').addClass('up');
     elem.closest('.navbar__sub-list').css('display', 'block');
   }



  // Bootstrap Tooltips
 $('[data-toggle="tooltip"]').tooltip();
 // <img src='https://static.louisxiii-cognac.com/img/desktop/loader-2.gif'>
   // Loader
    let loader = `
	    <tr align='center' class="w-100 text-center">
			<td colspan="10"> 
			  <img src='https://static.louisxiii-cognac.com/img/desktop/loader-2.gif' width='40'>
			  <span class='d-block'> Loading... </span>
		    </td>
		</tr>
    `;


// if product has no size then disable the size selection
$('#nosize').on('click', function(){
  if($('input[name="nosize"]').is(':checked') == true){
    $('.add_sizes').prop('disabled', true).closest('label').css({color: '#ccc', borderColor: '#ccc', cursor: 'not-allowed'});
  }else{
    $('.add_sizes').prop('disabled', false).closest('label').css({color: '#333', borderColor: '#333', cursor: 'pointer'});
  }
});

// ################# Products filtering area ###################
  // Filter products for editing
	load_products_to_edit({records_per_page: 5, page: 1});

   function load_products_to_edit(data){
	   	$.ajax({
	   		url: 'editproductData.php',
	   		method: 'POST',
	   		data: data,
	   		beforeSend: function(){
	   			$('#products_table').empty().append(loader);
	   		},
	   		success: function(res){
	   			$('#products_table').empty().append(res);
	   		}
	   	});
   }

   // filter with entries number
   $('#filter_records_num').on('change', function(){
   	let val = $(this).val();
   	load_products_to_edit({records_per_page: val, page: 1});
   	$('.records_info').text(val);
   });

   // Pagination
    var page = 1;
   $(document).on('click', '.page-item', function(){
   	var action = $(this).data('action');

   	if(action == 'next' && page < $('#total_pages').val())
   		page = page + 1;
   	else if(action == 'prev' && page > 1)
   		page = page - 1;
   	else if(action == undefined)
   	    page = $(this).data('page');
   	else
   		return false;
   	
   	// console.log(page);
   	// console.log(action);
   	load_products_to_edit({records_per_page: $('.records_info').text(), page: page});
   });

   // filter with search
   $('#filter_search').on('keyup', function(){
   	var word = $(this).val();
    if(word !== ''){
    	load_products_to_edit({search: word});
    }
   });


// ############### Orders area #################
   // Change the order status
   $(document).on('change', '#order_status', function(){
      var status = $(this).val(),
          order_id = $(this).data('orderid');
    $.ajax({
      url: 'orders.php',
      method: 'POST',
      data: {status: status, order_id: order_id},
      success: function(){
        $('#toast-title').html('<p class="text-success">Nice !<p>');
        $('#toast-bd').html('The order status was successfully changed to '+status);
        $('.toast').toast('show');
      }
    });
   });


// ############## Categories area ##############
 // Get the sub categorys
 $(document).on('change', '#cate', function(){
      var c_id = $(this).find(':selected').data('cid');
    $.ajax({
      url: 'ajaxData.php',
      method: 'POST',
      data: {c_id: c_id},
      beforeSend: function(){
        $('#sub_c').html(loader);
      },
      success: function(data){
        $('#sub_c').html(data);
        console.log(data);
      }
    });
   });

  // for the appearence alert/msg
  $('#parent').on('change', function(){
    if($(this).val() == 0){
        $('#cate_appear_msg').html(`
           Do you want this main category to appear in the site, like in homepage, menu, footer... ?
          `);
    }else{
        $('#cate_appear_msg').html(`
          Do you want this sub-category to appear in the menu ?
        `);
    }
  });


// ############ Users managment area ##############
 //load users
 load_users(1);
   function load_users(page)
   {
    $.ajax({
      url: 'ajaxData.php',
      method: 'POST',
      data: {users_pagenum: page},
      beforeSend: function(){
        $('#users_data').html(loader);
      },
      success: function(res){
        $('#users_data').html(res);
      }
    });
 }
 // Users Pagination
    var page = 1;
   $(document).on('click', '.users_manage_page', function(){
    var action = $(this).data('action');
    if(action == 'next' && page < $('#total_pages').val())
      page = page + 1;
    else if(action == 'prev' && page > 1)
      page = page - 1;
    else if(action == undefined)
        page = $(this).data('page');
    else
      return false;
    

    load_users(page);
   });

// ################# Discounts area #################
  // change discount logo(%, $) when changing the type
  $('#discount_type').on('change', function(){
    var d_type = $(this).val();

    if(d_type == 'precent'){
      $('.d_type_logo').empty().text('%');
    }else{
      $('.d_type_logo').empty().text('$');
    }
  });


  // check all "applied to discount" products
  $('#check_all').on('click', function(){
    $('.apply_discount_to').each(function(){
      if($(this).prop('checked') == true){
        $(this).prop('checked', false);
      }else{
        $(this).prop('checked', true);
      }
    });
  });



 }); // end of jquery

