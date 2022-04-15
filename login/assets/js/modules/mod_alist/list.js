"use strict";
var AppNews = function() {
	var mainlistTable;
	var tableCont;
	var searchvalue={};
	var mid;
	var initToastr = function() {
		toastr.options.showDuration = 500;
	}
	var initTable = function() {
		mid=_mid;
		tableCont=$("#mainlist");
		mainlistTable=$("#mainlist").DataTable({
			responsive: true,
			searchDelay: 500,
			searching: false,
			stateSave: true,
			order: [],
			serverSide: true,
			ajax: {
				"url": KTAppOptions._RF_ADMIN+"/m/mod_alist/mainlist",
				"dataSrc": function(res) {
					setTimeout(function() {
						KTApp.unblock(tableCont);
					}, 1000);
					if (res.customActionMessage) {
						if(res.customActionStatus == 'OK'){
							toastr.success(res.customActionMessage);
						}else{
							toastr.error(res.customActionMessage);
						}
					}
					return res.data;
				},
				"data": function ( d ) {
					KTApp.block(tableCont, {
						type: 'loader',
						message: 'Уншиж байна',
						state: 'brand',
						opacity: 0.1,
						size: 'lg'
					});
					return $.extend( {"mid":mid}, d, searchvalue );
				}
			},
			"aoColumns": [
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
            ],
            columnDefs: [
				{
					targets: 1,
					title: 'Зураг',
					render: function(data, type, full, meta) {
						var output = `
								<img src="` + data+ `" class="kt-marginless" width="100" alt="photo">
						`;
						return output;
					},
                },{
					targets: -1,
					title: '',
					orderable: false,
					render: function(data, type, full, meta) {
						return `
								<a href="`+data+`" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Засах">
									<i class="la la-edit"></i>
								</a>`;
					},
				}
			],
			drawCallback: function(settings) {
			},
			"language": { // language settings
				"lengthMenu": " Хуудсанд _MENU_ ",
				"info": "Нийт: _TOTAL_",
				"infoEmpty": "Бичлэг олдсонгүй",
				"emptyTable": "Бичлэг байхгүй байна",
				"zeroRecords": "Тохирох бичлэг олдсонгүй",
				"paginate": {
					"previous": "Өмнөх",
					"next": "Дараах",
					"last": "Сүүлийн",
					"first": "Эхний",
					"page": "Хуудас",
					"pageOf": " Нийт хуудас: "
				}
		},
		}); 
		$('#m_search').on('click', function(e) {
			e.preventDefault();
			var params = {};
			$('.kt-input').each(function() {
				searchvalue[$(this).attr("name")]=$(this).val();
			});
			mainlistTable.draw();
		});

		$('#m_reset').on('click', function(e) {
			e.preventDefault();
			$('.kt-input').each(function() {
				$(this).val('');
			});
			searchvalue={};
			mainlistTable.draw();
		});
	};
	return {
		init: function() {
			initToastr();
			initTable();
		},
		getTable: function() {
			return mainlistTable;
		},
	};
}();

jQuery(document).ready(function() {
	AppNews.init();
});