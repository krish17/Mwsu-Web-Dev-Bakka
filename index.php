<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8 />
	<title>Client Side Pagination</title>
	<link rel="stylesheet" type="text/css" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="./css/font-awesome.css" />
	<script   src="https://code.jquery.com/jquery-2.2.4.js"   integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="   crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script src="./plugin/jquery.twbsPagination.js"></script>
	
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<style>

        #pleaseWaitDialog {
            width: 400px;
            height: 50px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -25px;
            margin-left: -200px;
            padding: 20px;
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
        
        .col-centered{
            float: none;
            margin: 0 auto;
        }
        
	</style>
</head>
<body>


<div class="row">
  <div class="col-xs-18 col-sm-12">
      <table class="table table-striped">
      <thead>
      </thead>
      <tbody>
      </tbody>
     </table>
  </div>
</div>
<div class="row">
  <div class="col-xs-2 col-sm-1"></div>
  <div class="col-xs-14 col-sm-10"><center>
      <ul id="pagination-demo" class="pagination-sm"></ul>
      </center>
  <div class="col-xs-2 col-sm-1"></div>
</div>

<div class="modal hide" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-header">
            <h1>Processing...</h1>
        </div>
        <div class="modal-body">
            <div class="progress progress-striped active">
                <div class="bar" style="width: 100%;"></div>
            </div>
        </div>
    </div>


<script>
(function($) {
    //http://esimakin.github.io/twbs-pagination/

    var myWait;
    myWait = myWait || (function () {
        var waitDiv = $('<div class="modal" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false"><div class="progress"> <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:98%"> LOADING... </div></div></div>');
        return {
            show: function() {
                waitDiv.modal();
            },
            hide: function () {
                waitDiv.modal('hide');
            },

        };
    })();


    myWait.show();

    // Set up some variables for our pagination
    var page = 1;
    var page_size = 10;
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




    function loadTableData(page, page_size,sort,order) {
	
		var sort = typeof sort !== 'undefined' ?  sort.trim() : "id";
		var order = typeof order !== 'undefined' ?  ","+order : "";
    
        myWait.show();
                
        // Perform a get request to our api passing the page number and page size as parameters
		console.log("http://198.199.67.218/api/api.php/products?order="+sort+"&page=" + page + "," + page_size);
        $.get("http://198.199.67.218/api/api.php/products?order="+sort+order+"&page=" + page + "," + page_size)

        // The '.done' method fires when the get request completes
        .done(function(data) {
        
            console.log(data);

            // Pull the column names out of our json object 
            var cols = data.products.columns;

            // Start an html string with a row tag
            col_head = "<tr>";
            for (var i = 0; i < cols.length; i++) {

                // Continuously append header tags to our row
                col_head += "<th> <button> <i class=\"fa fa-caret-down sort\" aria-hidden=\"true\"></i> " + cols[i];
				col_head += ' <i class="fa fa-caret-up sort" aria-hidden="true"></i>';
				col_head += " </button></th>";
				
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
                        var img = result[0].replace("~","25");
                        records[i][j] = "<img src="+img+">";
                    }
                    rows = rows + "<td>" + records[i][j] + "</td>";
                }
                rows = rows + '<td style="vertical-align:middle" nowrap><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <i class="fa fa-trash-o" aria-hidden="true"></i></td>';
                // Finish the row for a product
                rows = rows + "</tr>";
            }

            // At this point "rows" should have 'page_size' number of items in it,
            // so append all those rows to the body of the table.
            $('tbody').html(rows);
            
            myWait.hide();
			
/* 			$('.sort-up').click(function(){
				$this = $(this);
				var column = $this.parent().text();
				
				console.log($this.parent().text());
				loadTableData(page,10,column);
			});
			
			$('.sort-down').click(function(){
				$this = $(this);
				var column = $this.parent().text();
				
				console.log($this.parent().text());
				loadTableData(page,10,column,"desc");
			}); */
            
       
            
                
            $('button').click(function(){
				$this = $(this);
				var column = $this.parent().text();
				console.log($this.parent().text());
                $('.sort').toggleClass(loadTableData(page,10,column), loadTableData(page,10,column,"desc"));
                
                
			});
            
            
            
            
            

        });
    }
    
    function getTotalPages(){
        $.get("http://198.199.67.218/api/api.php/products")

        // The '.done' method fires when the get request completes
        .done(function(data) {

            total_records = data.products.records.length;
            total_pages = parseInt(total_records / page_size);
            loadTableData(1, 10);
            $('#pagination-demo').twbsPagination({
                totalPages: total_pages,
                visiblePages: 15,
                onPageClick: function (event, page) {
                    $('#page-content').text('Page ' + page);
                    loadTableData(page,10);
                }
            });
			
        });
    }
	

    
    getTotalPages();


}(jQuery));
</script>
</body>
</html>
