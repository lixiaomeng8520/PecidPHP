// $(document).ready(function(){
	$('body').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    });

    var dt_act_list = $("#act_list").DataTable({
        "ajax": {
            "url": current_url,
            "dataSrc": function(ret){
                if(ret.redirect) window.location.href = ret.redirect;
                return ret.data;
            }
        },
        "columns": [
            { data: 'title' },
            { data: 'status' },
            { data: 'vote_start' },
            { data: 'vote_end' },
            { data: 'vote_interval' },
            { data: 'player_num' },
            { data: 'op' }
        ],
        "ordering": false,
        "lengthChange": false,
        "searching" : true,
        "pageLength" : 20,
        "language" : {
        	"paginate" : {
        		"previous" : "上一页",
        		"next" : "下一页",
        	},
        	"info": "当前第_PAGE_页，共_PAGES_页"
        },
    });

    var dt_player_list = $("#player_list").DataTable({
        "ajax": current_url,
        "columns": [
            { data: 'number' },
            { data: 'name' },
            { data: 'mobile' },
            { data: 'num' },
            { data: 'status' },
            { data: 'op' }
        ],
        "ordering": false,
        "lengthChange": false,
        "searching" : true,
        "pageLength" : 20,
        "language" : {
        	"paginate" : {
        		"previous" : "上一页",
        		"next" : "下一页",
        	},
        	"info": "当前第_PAGE_页，共_PAGES_页"
        },
    });

    var dt_log_list = $("#log_list").DataTable({
        "ajax": current_url,
        "columns": [
            { data: 'adminname' },
            { data: 'type' },
            { data: 'url' },
            { data: 'data' },
            { data: 'ip' },
            { data: 'time' },
        ],
        "ordering": false,
        "lengthChange": false,
        "searching" : false,
        "pageLength" : 20,
        "language" : {
        	"paginate" : {
        		"previous" : "上一页",
        		"next" : "下一页",
        	},
        	"info": "当前第_PAGE_页，共_PAGES_页"
        },
    });

    $(document).on('click', '.modal_log', function(){
        var log_data = decodeURIComponent($(this).attr('log_data'));
        $('#myModal').on('show.bs.modal', function(){
            $(this).find('.modal-title').text('日志数据');
            $(this).find('.modal-body').html(log_data);
        });
        $('#myModal').on('hidden.bs.modal', function(){
            $(this).removeData("bs.modal");
        });
		$('#myModal').modal({});

    });

    $(document).on('click', '.modal_form', function(){
		$('#myModal .modal-dialog').css('width', '70%');
		$('#myModal').modal({
			remote: $(this).attr('url'),
		}).on('show.bs.modal', function(){
		}).on('shown.bs.modal', function(){
		}).on('hidden.bs.modal', function(){
			$(this).removeData("bs.modal");
		});
    });
    
    
// });
    