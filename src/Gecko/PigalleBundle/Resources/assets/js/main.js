$(document).ready(function()
{
	//Menu width fix
	$('#menu ul li a').each(function() 
	{
		//$(this).css('width', $(this).width() + 10);
	});
	
	//Zoom image on gallery
	$(".detalleProducto .fotoDetalle img").elevateZoom({
		zoomWindowPosition: "productGalleryZoomContainer",
		zoomWindowHeight: 460, 
		zoomWindowWidth:460, 
		borderSize: 1,
		gallery:'productGallery', 
		cursor: 'pointer', 
		galleryActiveClass: 'active'
	}); 
});