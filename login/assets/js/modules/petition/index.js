"use strict";
var AppPetition = function() {
	var mainlistTable;
	var tableCont;
	var searchvalue={};
	var initToastr = function() {
		toastr.options.showDuration = 500;
	}
	var init = function() {
		$('#petitionModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget); 
			var id = button.data('id');
			var modal = $(this);
			modal.find('.modal-content').html("");
			var blockelm=modal.find('.modal-content');
			$('body').addClass('modalprinter');
			$.ajax({
				method: "POST",
				url: KTAppOptions._RF_ADMIN+"/m/petition/detail",
				data: { id:id},
				beforeSend: function( xhr ) {
					KTApp.block(blockelm, {
						overlayColor: '#ffffff',
						type: 'loader',
						state: 'brand',
						opacity: 0.3,
						size: 'lg'
					});
				}
			}).fail(function( html ) {
				setTimeout(function() {
					KTApp.unblock(blockelm);
				}, 1000);
			}).done(function( html ) {
				modal.find('.modal-content').html(html);
				Stimulsoft.Base.StiLicense.key =
					"6vJhGtLLLz2GNviWmUTrhSqnOItdDwjBylQzQcAOiHl+frPmrtRTu7G3lOXSiLetMDBzrT56tP84t8Oj" +
					"NUfqbTb2q0S7Z98NNXOEHFgmXBCxlOvTlo1h8l00da/HEp6ZwdSjki1DiHNVVhXx/dVyl/Kq+2GK42dz" +
					"3wTC49MmkrtZjZEA1ETwdW8meIqQl4qX20A3Q/fDB/yS0e1XRKGgCf4k9l1oJHEjK/tnoOYV1NIfbBnG" +
					"KI101HA/b1bQM/mJ6ZkPdLzZ/ti1WrR7gbeWEJ5u6ABh/tdPc2o38YZ2pEijX0Xi5N2GLRgPL2Jyp+YW" +
					"pEad0fTJXfoxFUYNiyxB5FZ/GZI+XchXXg+hB5WmUJWMkv2GU21w8vV3DLtaD64IwaRncwseUDFZ+sYj" +
					"XDqdVo8KNufLmbdX5YzS49B5AYlGve9oZigL2laGMvW1DkbDx3+wvURdRzjZGhxqboXJOjnZ55ZjTjKm" +
					"90n+ps7Vl+nCn3LJfHZcgQdJJ1KLlPPK1Oomqc7iz+MyQ3nq4R0JsjO4qk20cQ+A78S/QkS4GbYqdIqs" +
					"GIfxGP/rr2GmcXBv";

				var report = new Stimulsoft.Report.StiReport();
				report.loadFile(KTAppOptions._RF_ADMIN+"/print/petition.mrt");

				report.dictionary.databases.clear();

				var dsJSON = new Stimulsoft.System.Data.DataSet();
				dsJSON.readJsonFile(KTAppOptions._RF_ADMIN+"/m/petition/json?id="+id);
				
				report.regData("JSON", null, dsJSON);

				var options = new Stimulsoft.Viewer.StiViewerOptions();
				var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
				viewer.report = report;
				viewer.renderHtml("viewerContent");
			});
		}).on('hide.bs.modal', function (event) {
			var modal = $(this);
			modal.find('.modal-content').html("");
		});
	}
	var initTable = function() {
		tableCont=$("#petitionlist");
		mainlistTable=$("#petitionlist").DataTable({
			responsive: true,
			searchDelay: 500,
			searching: false,
			stateSave: true,
			order: [],
			serverSide: true,
			ajax: {
				"url": KTAppOptions._RF_ADMIN+"/m/petition/petitionlist",
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
					return $.extend( {}, d, searchvalue );
				}
			},
			"aoColumns": [
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
				{"sClass": "font-12","bSortable": false},
            ],
            
			
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
			init();
			initToastr();
			initTable();
		},
		getTable: function() {
			return mainlistTable;
		},
	};
}();

jQuery(document).ready(function() {
	AppPetition.init();
});