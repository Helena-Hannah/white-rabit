$(document).ready(function(){

    $.fn.customDataTable =function () {

        table_id = $(this).attr('id');
        var $ccwca_table = $(this);
        var columns = [];
        var target=$(this).data('target');
        var url=$(this).data('url')
      
        
        var pagination = 10;//$ccwca_table.data('pagination');
       
        var prevtext= "Previous page"
        var nexttext = "Next Page";
        var zeroRecords = "No records found";
        
        var loadingLbl = "Loading";
      
         $ccwca_table.find('thead th').each(function (index) {
            var index = $(this).data('index');
            var thisTH=$(this);
            var classes = $(this).data('classes');
           
            columns.push({
                data: index,
                name: index,
                render: function (data, type, row, meta) {
                    
                    if (meta.settings.json === undefined) { return data; }
                    switch (index) {
                   
                        default:
                            return data;
                    }
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).attr('data-sort', cellData);
                      
                } 
            });
        });
        
            return $ccwca_table.DataTable({
                "deferRender": true,
                "serverSide": true,
                "ajax": {
                    
                    "type": "POST",
                    "dataType": "JSON",
                      'url':url,
                "data": function ( d ) {
             return $.extend( {}, d, {
            //    "action":target,
            //    key:brand.wp_nonce,
              
      
              
             } );
          },
                  
                    "error": function (xhr, error, thrown) {
                        alert('Something wrong with Server');
                    }
                },
                "ordering": false,
                "searching": true,
                stateSave: true,
                
                "columns": columns,
                "responsive": true,
                "lengthChange": true,
                // "pagingType": "simple",
                "processing": true,
                // "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
                "language": {
                    "processing":loadingLbl,
                    "loadingRecords":loadingLbl,
                    // "paginate": {
                    //     "next":  nexttext,
                    //     "previous":prevtext
                    // },
                },
                "zeroRecords":zeroRecords,
                "emptyTable":zeroRecords,
                // "renderer": {
                //     "header": "bootstrap",
                // },
                "drawCallback": function (settings) {
             
                        
                },
              
            });
      
           
       
        }

     $('#mytable').customDataTable()
     var deleted=$('#history_table').customDataTable()
     $("#del").on("click",function(){

        deleted.draw()
     })

     

    //     customDataTable =$('#mytable').DataTable(
    //         {
    //       "processing": true, //Feature control the processing indicator.
    //       "serverSide": false, //Feature control DataTables' server-side processing mode.
    //       "order": [], //Initial no order.
   
    //       // Load data for the table's content from an Ajax source
    //       "ajax": {
    //           "url": base_url+"welcome/ajax_list",
    //           "type": "POST"
    //       },
   
    //       //Set column definition initialisation properties.
    //       "columnDefs": [
    //       { 
    //           "targets": [ 0 ], //first column / numbering column
    //           "orderable": false, //set not orderable
    //       },
    //           ],
    //   }
    //     );

        })
       
 

