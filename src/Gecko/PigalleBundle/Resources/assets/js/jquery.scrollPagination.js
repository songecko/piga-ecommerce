/**
 * Scroll Pagination
 * 
 * @author Diego D'amico <songecko@gmail.com
 */
(function($) {

	$.fn.scrollPagination = function(options) 
	{
		var opts = $.extend($.fn.scrollPagination.defaults, options);
		
		return this.each(function() 
		{
			$.fn.scrollPagination.init($(this), opts);
		});
	};
	
	$.fn.scrollPagination.init = function(obj, opts)
	{
		if(opts.totalPages == null)
		{
			opts.totalPages = $(obj).data('spTotalPages');
		}
		if(opts.loadUrl == null)
		{
			opts.loadUrl = $(obj).data('spLoadUrl');
		}
		
		if(opts.totalPages <= 1)
		{
			return; 
		}
	 
		$(obj).find(opts.scrollLoadMoreTarget).click(function(e)
		{
			if(!$.fn.scrollPagination.loading && 
				$.fn.scrollPagination.currentPage < opts.totalPages)
			{
				//Set the current page
				$.fn.scrollPagination.currentPage++;
				
				$.fn.scrollPagination.loadContent(obj, opts);
			}
			e.preventDefault();
		});
	};
	 
	$.fn.scrollPagination.loadContent = function(obj, opts)
	{
		if (opts.beforeLoad != null){
			opts.beforeLoad(); 
		}

		$.fn.scrollPagination.loading = true;
		 
		var loadData = $.extend({page: $.fn.scrollPagination.currentPage }, opts.loadData);
		 
		$.ajax({
			type: 'GET',
			url: opts.loadUrl,
			data: loadData,
			success: function(data)
			{
				$.fn.scrollPagination.loading = false;
				
				$(obj).find(opts.scrollContentTarget).append(data);
			  
				if($.fn.scrollPagination.currentPage == opts.totalPages)
				{
					$(obj).find(opts.scrollLoadMoreTarget).hide();					
				}
				
				if (opts.afterLoad != null)
				{
					opts.afterLoad(data);	
				}
			},
			dataType: 'html'
		});
	};
	
	$.fn.scrollPagination.currentPage = 1;
	$.fn.scrollPagination.loading = false;
	$.fn.scrollPagination.defaults = 
	{
		scrollContentTarget: ".spContent",
		scrollLoadMoreTarget: ".spLoadMoreButton",
		totalPages: null,
		loadUrl: null,
		loadData: {},
		beforeLoad: null,
		afterLoad: null
	 };

})(jQuery);