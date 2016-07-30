<?php
	include('dbconnect.php');
	
	if(isset($_GET['page_size'])){
		$page_size = $_GET['page_size'];
	}else{
		$page_size = 10;
	}
	
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 1;
	}
	
	
	$sql = "SELECT count(*) as count FROM products";
	$result = $conn->query($sql);
	
	$row = $result->fetch_assoc();
	$num_products = $row['count'];
	
	$tot_pages = (int)($num_products / $page_size);	

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8 />
	<title>Server Side Pagination</title>
	<link rel="stylesheet" type="text/css" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="./css/font-awesome.css" />
	<script   src="https://code.jquery.com/jquery-2.2.4.js"   integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="   crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<style>
		.pagination>li>a, .pagination>li>span { border-radius: 50% !important;margin: 0 5px;}
		.pagination>li>a>.fa {
		    font-size:18px
		}
	</style>
</head>
<body>
    <div class="well">
        <table class="table table-striped">
          <thead id="product_head">
          </thead>
          <tbody>
          <?php
            if($page == 1){
                $start = 0;
            }else{
                $start = $page_size * $page;
            }
            
            $sql = "SELECT * FROM products LIMIT {$start},{$page_size}";
            $result = $conn->query($sql);
    
            while($row = $result->fetch_assoc()){
                $thumb = str_replace('~','25',$row['img']);
                list($thumb,$null) = explode(' ',$thumb);
                echo"
                <tr>
                  <td>{$row['id']}</td>
                  <td>{$row['category']}</td>
                  <td>{$row['desc']}</td>
                  <td>{$row['price']}</td>
                  <td><img src=\"{$thumb}\"></td>    
                </tr>";
            }
            ?>
          </tbody>
        </table>
    </div>
    <div class="container">
    <ul class="pagination">
				  <!--Goes to beginning of list -->
                  <li>
					<a href="<?php echo"{$_SERVER['PHP_SELF']}?page=1&page_size={$page_size}"?>">
						<i class="fa fa-angle-double-left" aria-hidden="true"></i>
					</a>
				  </li>
                  <?php
                  if($page == 1){
                      $prev_page = $tot_pages;
                  }else{
                     $prev_page = $page - 1;
                  }
                  if($page == $tot_pages){
                      $next_page = 1;
                  }else{
                     $next_page = $page + 1;
                  }
                  ?>
				  <!--Goes to previous of list -->
                  <li><a href="<?php echo"{$_SERVER['PHP_SELF']}?page={$prev_page}&page_size={$page_size}"?>"><i class="fa fa-angle-left"></i></a></li>
                 
                  
                  <?php
                  $i = $page - 5;
                  if($i < 0){
                    $i = 1;
                  }
                  
                  $j = $page + 5;
                  if($j > $tot_pages){
                    $j = $tot_pages;
                  }
                  
                  for(;$i<=$j;$i++){
                    if($i == $page){
                        $li_class = "active";
                    }else{
                        $li_class = "";
                    }
                    echo"<li class=\"{$li_class}\"><a href=\"{$_SERVER['PHP_SELF']}?page={$i}&page_size={$page_size}\">{$i}</a></li>";
                  }
                  ?>
                                   
                  <li><a href="<?php echo"{$_SERVER['PHP_SELF']}?page={$next_page}&page_size={$page_size}"?>"><i class="fa fa-angle-right"></i></a></li>
                  <li><a href="<?php echo"{$_SERVER['PHP_SELF']}?page={$tot_pages}&page_size={$page_size}"?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>
                </ul>
    </div>
</body>
</html>
