function pagination(indentifier, url, config) {
    $('#'+indentifier).DataTable({
        "language": {
            "url": base_url+"assets/vendor/datatables/Indonesian.json"
        },
        "ordering": false,
        "columnDefs": config,
        "bProcessing": true,
        "serverSide": true,
        "bDestroy" : true,
        "ajax":{
            url : url, // json datasource
            type: "post",  // type of method  , by default would be get
            error: function(){  // error handling code
                $("#"+indentifier).css("display","none");
            }
        }
    }); 
}