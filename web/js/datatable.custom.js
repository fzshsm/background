// Initialize DataTable
function customDatatable(table , format){
	var options = format.getFormatOptions();
	jQuery.fn.dataTable.ext.errMode = 'none';
	return table.on('error.dt', function ( e, settings, techNote, message ) {
	    toastr.error(message, "数据加载错误:" , toastrOpts);
	}).DataTable( {
        "order": options.order,
        "aoColumnDefs": options.aoColumnDefs,
        "language": {
			"decimal":        ".",
		    "emptyTable":     options.emptyTable,
		    "info":           options.infolabel,
		    "infoEmpty":      options.infolabel,
		    "infoFiltered":   "(filtered from _MAX_ total entries)",
		    "infoPostFix":    "",
		    "thousands":      ",",
		    "lengthMenu":     options.lengthMenu,
		    "loadingRecords": "数据加载中...",
		    "processing":     "数据处理中...",
		    "search":         "",
		    "zeroRecords":    "未找到相关记录！",
		    "paginate": {
		        "next":       "",
		        "previous":   ""
		    }
	    },
		"lengthMenu": options.pageMenu,
		"autoWidth" : false,
		"deferRender" : true,
		"scrollX" : true,
		"stateSave": true,
		"ordering" : options.ordering,
		"processing": true,
	    "serverSide": true,
	    "ajax": {
			"url" : options.ajaxUrl,
			"data" : function(d){
				delete d.columns;
				delete d.search;
				if (typeof options.ajaxData != 'undefined'){
                    for(var key in options.ajaxData){
                    	d[key] = options.ajaxData[key];
					}
				}
			},
			"beforeSend" :function(){
				$('.loading-shade').show();
			},
			"complete" : function(){
				$('.loading-shade').fadeOut();
			}
		 },

        "columns" :  options.columns,
	    "createdRow": function( row, data, dataIndex ) {
			if (typeof format.execute != 'undefined'){
                format.execute(row , data);
			}
	    },
	    "preDrawCallback" : function(settings , json){
			if (typeof format.initDataTable != 'undefined'){
                format.initDataTable();
			}
			if (options.lengthMenu != ''){
                table.closest( '.dataTables_wrapper' ).find( 'select' ).select2( {
                    minimumResultsForSearch: -1
                });
			}

	    },
		"drawCallback" : function () {
	    	if(typeof format.complete != 'undefined'){
                format.complete();
			}
        },
		"fnDrawCallback" : function(settings){
            if (typeof format.showDatatableTitle != 'undefined'){
                format.showDatatableTitle(settings);
            }
		}

	});
}
