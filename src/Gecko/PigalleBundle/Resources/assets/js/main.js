$(document).ready(function(){
	$('#menu ul li a').each(function() 
	{
		$(this).css('width', $(this).width() + 10);
	});
});