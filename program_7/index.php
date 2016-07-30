<?php
session_start();
$sid = session_id();
?>

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
	<script src="./js/cookies.js"></script>
	
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
        
        .modal-header {
            padding-bottom: 5px;
        }

        .modal-footer {
                padding: 0;
            }
    
        .modal-footer .btn-group button {
            height:40px;
            border-top-left-radius : 0;
            border-top-right-radius : 0;
            border: none;
            border-right: 1px solid #ddd;
        }
    
        .modal-footer .btn-group:last-child > button {
            border-right: 0;
        }
		
		body {
			padding:20px;
		}
        
	</style>
</head>
<body>


<div class="row">
  <div class="col-xs-12 col-sm-8">
      <table class="table table-striped">
      <thead>
      </thead>
      <tbody>
      </tbody>
     </table>
  </div>
	<div class="col-xs-6 col-sm-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="panel-title">
						<div class="row">
							<div class="col-xs-6">
								<h5><span class="glyphicon glyphicon-shopping-cart"></span> Shopping Cart</h5>
							</div>
							<div class="col-xs-6">
								<button type="button" class="btn btn-primary btn-sm btn-block">
									<span class="glyphicon glyphicon-share-alt"></span> Continue shopping
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-body" >
					<div id="cart-body">
					</div>

					<div class="row">
						<div class="text-center">
							<div class="col-xs-8">
								<h6 class="text-right">Added items?</h6>
							</div>
							<div class="col-xs-4">
								<button type="button" class="btn btn-default btn-sm btn-block" id="updateCart">
									Update cart
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row text-center">
						<div class="col-xs-8">
							<h4 class="text-right">Total $<span id="cart-total">0.00</span></h4>
						</div>
						<div class="col-xs-4">
							<button type="button" class="btn btn-success btn-block">
								Checkout
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-8"><center>
      <ul id="pagination-demo" class="pagination-sm"></ul>
      </center>
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
</div>

<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			<h3 class="modal-title" id="lineModalLabel">Edit Product</h3>
		</div>
		<div class="modal-body">
			
            <!-- content goes here -->
			<form id="productEditForm">
              <div class="form-group">
                <label for="pid">Id</label>
                <input type="text" class="form-control" id="pid" placeholder="Product Id">
              </div>
              <div class="form-group">
                <label for="category">Category</label>
                <input type="text" class="form-control" id="category" placeholder="Category">
              </div>
              <div class="form-group">
                <label for="desc">Description</label>
                <input type="text" class="form-control" id="desc" placeholder="Description">
              </div>
              <div class="form-group">
                <label for="price">Price</label>
                <input type="text" class="form-control" id="price" placeholder="$0.00">
              </div>
              <div class="form-group">
                <label for="imgurl">Img Url</label>
                <input type="text" class="form-control" id="imgurl" placeholder="Url">
              </div>
			  <input type="hidden" id="old_id" value="">
            </form>
		</div>
		<div class="modal-footer">
			<div class="btn-group btn-group-justified" role="group" aria-label="group button">
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-default" data-dismiss="modal"  role="button">Close</button>
				</div>
				<div class="btn-group btn-delete hidden" role="group">
					<button type="button" id="delImage" class="btn btn-default btn-hover-red" data-dismiss="modal"  role="button">Delete</button>
				</div>
				<div class="btn-group" role="group">
					<button type="button" id="saveImage" class="btn btn-default btn-hover-green" data-action="save" role="button">Save</button>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>
<script>

(function($) {
    //http://esimakin.github.io/twbs-pagination/
	
	var guid = <?php echo"\"{$sid}\";";?>
	
	//Cookies.set('test_cookie', guid,{ expires: Infinity , domain: 'mwsu-webdev.xyz'});
	
	console.log(Cookies.get('test_cookie'));
	
	console.log(guid);

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
		//console.log("http://mwsu-webdev.xyz/api/api.php/products?order="+sort+"&page=" + page + "," + page_size);
        $.get("http://mwsu-webdev.xyz/api/api.php/products?order="+sort+order+"&page=" + page + "," + page_size)

        // The '.done' method fires when the get request completes
        .done(function(data) {
        
           // console.log(data);

            // Pull the column names out of our json object 
            var cols = data.products.columns;

            // Start an html string with a row tag
            col_head = "<tr>";
            for (var i = 0; i < cols.length; i++) {

                // Continuously append header tags to our row
                col_head += "<th nowrap> " + cols[i] +"</th>";
				
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

                //Start a new row for each product and put the product id in a data-element
                rows = rows + "<tr data-id="+records[i][0]+">";

                // Loop through each item for a product and append a table data tag to our row
                for (var j = 0; j < records[i].length; j++) {
                
                                
                    // This is the last item in the record set so it's the img url.
                    if(j == records[i].length-1){
                        var result = records[i][j] .split(' ');
                        var img = result[0].replace("~","25");
                        records[i][j] = "<img src="+img+">";
                    }
                    rows = rows + "<td>" + records[i][j] + "</td>";
                }
                rows = rows + '<td style="vertical-align:middle" nowrap><i class="fa fa-shopping-cart" aria-hidden="true"></i></td>';
                // Finish the row for a product
                rows = rows + "</tr>";
            }

            // At this point "rows" should have 'page_size' number of items in it,
            // so append all those rows to the body of the table.
            $('tbody').html(rows);
            
            myWait.hide();
			
		
            $('.fa-shopping-cart').click(function(){
                console.log($(this).closest('tr').data( "id" ));
                
				var item = [];
                $(this).closest('tr').find('td').each(function(){
					console.log($(this).text());
					item.push($(this).html());
				});
				console.log(item);
				addCartItem(item);
            })
    

        });
    } // End .done
	
    $('.btn').click(function(){
        var delbtn = document.getElementsByClassName("btn btn-link btn-xs");
            console.log("clicked");        
    });
    
    
	$('#updateCart').click(function(){
        var total = 0;
		$('.cart-item').each(function(){
			//console.log($(this).find('.price').text());
			//console.log($(this).find('.count').val());
            var refPrice = $(this).find('.price').text().replace(/\$/g,'');
            total = total + ( refPrice * parseFloat($(this).find('.count').val()));
		});
        $('#cart-total').text(total);
	});
    
    function getTotalPages(){
        $.get("http://mwsu-webdev.xyz/api/api.php/products")

        // The '.done' method fires when the get request completes
        .done(function(data) {

            total_records = data.products.records.length;
            total_pages = parseInt(total_records / page_size);
            loadTableData(1, 10);
            $('#pagination-demo').twbsPagination({
                totalPages: total_pages,
                visiblePages: 10,
                onPageClick: function (event, page) {
                    $('#page-content').text('Page ' + page);
                    loadTableData(page,10);
                }
            });
			
        });

    }
    
	function addCartItem(item){
		
		var row=''+
		'<div class="row cart-item" id="item-'+item[0]+'">'+
			'<div class="col-xs-2">'+ item[4] +
			'</div>'+
			'<div class="col-xs-3">'+
			'	<h4 class="product-name"><strong>'+item[1]+'</strong></h4>'+
			'</div>'+
			'<div class="col-xs-7">'+
			'	<div class="col-xs-4 text-right">'+
			'		<h6><strong><span class="price">$'+item[3]+'</span><span class="text-muted"> x </span></strong></h6>'+
			'	</div>'+
			'	<div class="col-xs-5">'+
			'		<input type="text" class="form-control input-sm count" value="1">'+
			'	</div>'+
			'	<div class="col-xs-2">'+
			'		<button type="button" class="btn btn-link btn-xs">'+
			'			<span class="glyphicon glyphicon-trash"> </span>'+
			'		</button>'+
			'	</div>'+
			'</div>'+
		'</div>'+
		'<hr>';
		
		var postData = {};
		postData['uid']=guid;
		postData['pid']=item[0];
		postData['count']=1;
		postData['description']=item[1];
		postData['price']=item[3];
		postData['time-added']=Math.floor(Date.now() / 1000);
		
		console.log(postData);
		var cartTotal = parseFloat($('#cart-total').text());
		if(isNaN(cartTotal))
			cartTotal = 0;

		cartTotal += parseFloat(item[3]);
		console.log(cartTotal);
		$('#cart-body').append(row);
		$('#cart-total').text(cartTotal)
		$.post("http://mwsu-webdev.xyz/api/api.php/shopping_cart/",postData);
        
        $('.btn').click(function()
		{
		console.log('deleted');
        var row = $(this).closest('.row');
            row.remove();
         var total = 0;
		$('.cart-item').each(function(){
			//console.log($(this).find('.price').text());
			//console.log($(this).find('.count').val());
            var refPrice = $(this).find('.price').text().replace(/\$/g,'');
            total = total + ( refPrice * parseFloat($(this).find('.count').val()));
		});
        $('#cart-total').text(total);    
        
     /*    
         $.ajax({
                    url: ('http://198.199.67.218/api/api.php/shopping_cart/'+cells),
                    callback: 'callback',
                    crossDomain: true,
                    type: 'DELETE',				
                    traditional:true,
                    dataType: 'json',
                    success: function(result) {					
                        getTotalPages();
                    },
                    error: function(result){}				
                    });  */
        
        
        
        
	});
        
        
        
        
	}

 
    
	 function guid() {
	  function s4() {
		return Math.floor((1 + Math.random()) * 0x10000)
		  .toString(16)
		  .substring(1);
	  }
	  return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
		s4() + '-' + s4() + s4() + s4();
	}
	
    getTotalPages();


}(jQuery));
</script>
</body>
</html>
