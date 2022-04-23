"use strict";
var AppReport = function() {
	var initToastr = function() {
		toastr.options.showDuration = 500;
	}
	
	var init = function() {
		$(".kt-container").find(".datepicker").datepicker({
			format: 'yyyy-mm-dd',
			autoclose:true,
            todayHighlight: true,
            orientation: "bottom left",
        });
		
		$(".download_excel").click(function(){
			var download_search={};
			$(this).closest( ".search_body" ).find('.kt-input').each(function() {
				download_search[$(this).attr("name")]=$(this).val();
			});
			var id = $(this).data('id');
			if(!$(this).data('url') || $(this).data('url')==""){
				toastr.error("Excel татах боломжгүй байна");
				return false;
			}
			var url = $(this).data('url');
			
			$.ajax({
				method: "POST",
				dataType:  'json',
				url: url,
				data:  download_search,
				beforeSend: function( xhr ) {
					KTApp.block("body", {
						overlayColor: '#000000',
						type: 'loader',
						state: 'brand',
						message: 'Татаж байна',
						opacity: 0.3,
						size: 'lg'
					});
				},
				error: function (data) {
					toastr.error("Алдаа гарсан байна. Err msg: "+data.responseText);
					setTimeout(function() {
						KTApp.unblock("body");
					}, 2000);
				},
				success: function(jsonData){
					if(typeof jsonData !== 'undefined' && jsonData!="" && jsonData._state) {
						var $a = $("<a>");
					    $a.attr("href",jsonData.file);
					    $("body").append($a);
					    $a.attr("download",jsonData.filename);
					    $a[0].click();
					    $a.remove();
					} else {
						toastr.error("Алдаа гарсан байна. Err msg: "+jsonData);
					}
					setTimeout(function() {
						KTApp.unblock("body");
					}, 2000);
				}
			});
		});
	};
	return {
		init: function() {
			initToastr();
			init();
		},
	
	};
}();
jQuery(document).ready(function() {
	AppReport.init();
});