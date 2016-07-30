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
        body.loading {
            display: block;
        }
        
        .col-centered{
            float: none;
            margin: 0 auto;
        }
        
          
         
         
/* The Modal (background) */

#myModal{
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    border: 1px solid #888;
    width: 80%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s
}
}

/* Add Animation */
@-webkit-keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

@keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

/* The Close Button */
.close {
    color: white;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    padding: 2px 16px;
    background-color: #5cb85c;
    color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
    padding: 2px 16px;
    background-color: #5cb85c;
    color: white;
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

    
     
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">×</span>
      <h4>Are you sure you want to delete this item</h4>
      <p id = "modalData"></p>
    </div>
    <div id = "modelbodyID" class="modal-body">
    </div>
    <div class="modal-footer">
      <button id="delButton">Delete</button>
    </div>
  </div>

</div>



<script>
(function($) {
    //http://esimakin.github.io/twbs-pagination/

    var cols;
    var myWait;
     
    $('table').on('click', '.fa-trash-o', function(){
        //replace table selector with an id selector, if you are targetting a specific table
        var row = $(this).closest('tr'),
            cells = row.find('td'),
            btnCell = $(this).parent();
             console.log(cells[2]);
             document.getElementById("modalData").innerHTML = cells[2].innerHTML;
        //set to work, you have the cells, the entire row, and the cell containing the button.
        
        var modal1 = document.getElementById('myModal');
        var span = document.getElementsByClassName("close")[0];
        modal1.style.display = "block";

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal1.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal1) {
                modal1.style.display = "none";
            }
        }

       $('button').click(function(){
       var id = cells[0].innerHTML;
       console.log(id);
       
       //deleting the table row
         $.ajax({
                    url: ('http://198.199.67.218/api/api.php/products/'+id),
                    callback: 'callback',
                    crossDomain: true,
                    type: 'DELETE',				
                    traditional:true,
                    dataType: 'json',
                    success: function(result) {					
                        getTotalPages();
                    },
                    error: function(result){}				
                    });
         modal1.style.display = "none";
          myWait.show();
        });

    }); 
    
        
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


    function loadTableData(page, page_size,sort,order) {
	
		var sort = typeof sort !== 'undefined' ?  sort.trim() : "id";
		var order = typeof order !== 'undefined' ?  ","+order : "";
    
        //myWait.show();
                
        // Perform a get request to our api passing the page number and page size as parameters
		console.log("http://198.199.67.218/api/api.php/products?order="+sort+"&page=" + page + "," + page_size);
        $.get("http://198.199.67.218/api/api.php/products?order="+sort+order+"&page=" + page + "," + page_size)

        // The '.done' method fires when the get request completes
        .done(function(data) {
        
            //console.log(data);

            // Pull the column names out of our json object 
            cols = data.products.columns;

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
