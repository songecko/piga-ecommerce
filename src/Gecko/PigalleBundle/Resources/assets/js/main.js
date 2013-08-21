var setupProductDetails = function(withZoom)
{
	withZoom = typeof withZoom !== 'undefined' ? withZoom : true;
	
	var options = {
		borderSize: 1,
		gallery:'productGallery', 
		cursor: 'pointer', 
		galleryActiveClass: 'active',
		zoomLevel: 0.5,
		scrollZoom: true
	};
	
	if(withZoom)
	{
		options.zoomWindowPosition = "productGalleryZoomContainer";
		options.zoomWindowHeight = 460; 
		options.zoomWindowWidth = 460; 
	}
	
	$(".productDetails .fotoDetalle img").elevateZoom(options); 
	
	//Submit on botonCompras
	$('.botonCompras').click(function()
	{
		$('.addToCartButton').click();
	});
	
	$('.addToCartButton').click(function(e)
	{
		if(!$(this).siblings(".talles").find("input[name='sylius_cart_item[variant]']:radio").is(':checked')) 
		{  
			$(this).siblings(".talles").popover('show'); 
			
			e.preventDefault();
		} 
		
	});
};

var setupQuickviewButtons = function()
{
	$(".product-box .imagenProducto").hover(function() //mouseover
	{
		$(this).children('.quickviewButton').show();
		$(this).children('img').fadeTo(0, 0.4);
	}, function() //mouseout
	{
		$(this).children('.quickviewButton').hide();
		$(this).children('img').fadeTo(0, 1);
	});
};

$(document).ready(function()
{	
	//Setup the products details
	setupProductDetails();	
	
	//Quickview
	setupQuickviewButtons();
		
	$('#quickviewModal').on('hidden', function ()
	{
		$(".productDetails .fotoDetalle img").removeData('elevateZoom');
		$('.zoomContainer').remove();
		
		$('#quickviewModal').children('.modal-body').children().remove();
		$(this).removeData('modal');
	});
	
	//Quickview cart
	$("#quickviewCart, .cartItems").hover(function() //mouseover
	{
		$("#quickviewCart").show();
	}, function() //mouseout
	{
		$("#quickviewCart").hide();
	});
	
	//Product scroll pagination
	$(".scrollPagination").scrollPagination({
		afterLoad: function(data)
		{
			setupQuickviewButtons();
		}
	});
	
	//Check if we have a faq hash
	if(location.hash.indexOf('#faq') > -1)
	{
		$(location.hash).collapse('show');
	}
});