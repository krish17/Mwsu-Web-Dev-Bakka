<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8 />
	<title>Client Side Pagination</title>
	<link rel="stylesheet" type="text/css" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="./css/font-awesome.css" />
	<script   src="https://code.jquery.com/jquery-2.2.4.js"   integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="   crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<style>

        /* Start by setting display:none to make this hidden.
           Then we position it in relation to the viewport window
           with position:fixed. Width, height, top and left speak
           speak for themselves. Background we set to 80% white with
           our animation centered, and no-repeating */
        .modal {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 ) 
                        url('http://sampsonresume.com/labs/pIkfp.gif') 
                        50% 50% 
                        no-repeat;
        }

        /* When the body has the loading class, we turn
           the scrollbar off with overflow:hidden */
        body.loading {
            overflow: hidden;   
        }

        /* Anytime the body has the loading class, our
           modal element will be visible */
        body.loading .modal {
            display: block;
        }
        
		.pagination>li>a, .pagination>li>span { border-radius: 50% !important;margin: 0 5px;}
		.pagination>li>a>.fa {
		    font-size:18px
		}
	</style>
</head>
<body>
<div class="btn-toolbar">
    <button class="btn btn-primary">New User</button>
    <button class="btn">Import</button>
    <button class="btn">Export</button>
</div>
<div class="well">
    <table class="table table-striped">
      <thead>
      </thead>
      <tbody>
      </tbody>
    </table>
</div>
<div class="container">
    <ul class="pagination" id="pagination">

        </ul>
</div>
<div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
        <h3 id="myModalLabel">Delete Confirmation</h3>
    </div>
    <div class="modal-body">
        <p class="error-text">Are you sure you want to delete the user?</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-danger" data-dismiss="modal">Delete</button>
    </div>
</div>
<script>
(function($) {

    $body = $("body");
    $body.addClass("loading");

    // Set up some variables for our pagination
    var page = 1;
    var page_size = 5;
    var total_records = 0;
    var total_pages = 0;
    var rows = "";
    var col_head = "";

    /*
    products = {
        "columns":[
            0: "id"
            1: "category"
            2: "desc"
            3: "price"
            4: "img"
        ],
        "records": [
            [
                0: "1",
                1: "tablet",
                2: "Fire Tablet, 7" Display, Wi-Fi, 8 GB - Includes Special Offers, Black",
                3: "49.99",
                4: "https://..."
            ],
            [
            ...
            ]
        ]
    }
    */

    function loadTableData(page, page_size) {

        console.log(page);
        console.log(page_size);
        
        $body.addClass("loading");
        
        // Perform a get request to our api passing the page number and page size as parameters
        $.get("http://mwsu-webdev.xyz/api/api.php/products?order=id&page=" + page + "," + page_size)

        // The '.done' method fires when the get request completes
        .done(function(data) {
        
            console.log(data);

            // Pull the column names out of our json object 
            var cols = data.products.columns;

            // Start an html string with a row tag
            col_head = "<tr>";
            for (var i = 0; i < cols.length; i++) {

                // Continuously append header tags to our row
                col_head = col_head + "<th>" + cols[i] + "</th>";
            }

            // Finish off our row with an empty header tag 
            col_head = col_head + "<th style=\"width: 36px;\"></th></tr>";

            // Append our new html to this pages only 'thead' tag
            $('thead').html(col_head);

            // Pull the products out of our json object 
            var records = data.products.records;

            // Start an empty html string
            rows = "";
            for (var i = 0; i < records.length; i++) {

                //Start a new row for each product
                rows = rows + "<tr>";

                // Loop through each item for a product and append a table data tag to our row
                for (var j = 0; j < records[i].length; j++) {
                    if(j == records[i].length-1){
                        var result = records[i][j] .split(' ');
                        var img = result[0].replace("~","50");
                        records[i][j] = "<img src="+img+">";
                    }
                    rows = rows + "<td>" + records[i][j] + "</td>";
                }

                // Finish the row for a product
                rows = rows + "</tr>";
            }

            // At this point "rows" should have 'page_size' number of items in it,
            // so append all those rows to the body of the table.
            $('tbody').html(rows);
            
            addPagination(page,total_pages);
            
            $body.removeClass("loading");

        });
    }
    
    function getTotalPages(){
        $.get("http://mwsu-webdev.xyz/api/api.php/products")

        // The '.done' method fires when the get request completes
        .done(function(data) {

            total_records = data.products.records.length;
            total_pages = parseInt(total_records / page_size);
            loadTableData(1, 10);

        });
    }


    function addPagination(page,total_pages){


        console.log(page,total_pages);
        
        var i = parseInt(page) - 5;
        var j = parseInt(page) + 5;
    
        if(i < 0){
            i = 1;
        }
    
        if(j > total_pages){
            j = total_pages;
        }


        var html = "";
        for (; i <= j; i++) {
            html += "<li><a href=\"#\" class=\"pagenum\">" + i + "</a></li>";
        }

        $('#pagination').html(html);

        $('.pagenum').click(function() {
            var page = $(this).html()
            loadTableData(page, page_size);
        
            $('#pagination').children().each(function(){
                console.log($(this));
                if($(this).text() == page){
                    $(this).parent().addClass('active');
                }else{
                    $(this).parent().removeClass('active');
                }
            });
        
//             $(this).parent().addClass('active');
//             console.log($(this).parent());
        });
        $('tbody').html(rows);
        $body.removeClass("loading");

    }
    
    getTotalPages();


}(jQuery));
</script>

<div class="modal"></div>
</body>
</html>
