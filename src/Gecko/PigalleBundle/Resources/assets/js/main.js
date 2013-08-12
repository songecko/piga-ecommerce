var setupProductDetailsGallery = function(withZoom)
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
};

$(document).ready(function()
{	
	//Zoom image on gallery
	setupProductDetailsGallery();	
	
	//Quickview
	$(".product-box .imagenProducto").hover(function() //mouseover
	{
		$(this).children('.quickviewButton').show();
	}, function() //mouseout
	{
		$(this).children('.quickviewButton').hide();
	});
	
	
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
	$(".scrollPagination").scrollPagination();
});