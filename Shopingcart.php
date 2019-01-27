<?php
session_start();
$connect = mysqli_connect("localhost", "root", "", "office");
 $AUTH_USER = 'admin';
    $AUTH_PASS = 'admin';
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
    $is_not_authenticated = (
        !$has_supplied_credentials ||
        $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
        $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
    );
    if ($is_not_authenticated) {
        header('HTTP/1.1 401 Authorization Required');
        header('WWW-Authenticate: Basic realm="Access denied"');
        exit;
    }
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Dancing+Script" rel="stylesheet">

</head>
<body class="container">
     
   <h1 class="text-center text-success mb-5" 
    style="font-family: 'Abril Fatface', cursive; background-color: black"> ONLINE SHOPPING BUY AS YOU WANT</h1>
    
   
    <div class="row">
    <?php
    $query = "SELECT * FROM products ORDER BY id ASC";
    $result = mysqli_query($connect, $query);
    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            ?>
            <div class="col-lg-3 col-md-3 col-sm-12">
            <form method="post" action="shop.php?action=add&id=<?php echo $row["id"]; ?>">
            <!--<div style="border: 1px solid #eaeaec; margin: -1px 19px 3px -1px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); padding:10px;" align="center"> -->
                <div class="card">
                    <h5 class="card-title bg-info text-white p-2 text-uppercase"><?php echo $row["p_name"]; ?></h5>
            <img src="<?php echo $row["image"]; ?>" >
            <div class="card-body">
            <h5 class="text-danger">$ <?php echo $row["price"]; ?></h5>
            <h5 class="badge badge-success"> 4.4 <i class="fa fa-star"> </i> </h5>
            <input type="text" name="quantity" placeholder="Select Quantity" class="form-control" >

            <input type="hidden" name="hidden_name" value="<?php echo $row["p_name"]; ?>">
            <input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>">
            <div class="btn-group d-flex">
            <input type="submit" name="add" style="margin-top:5px;" class="btn btn-success flex-fill" value="Add to Cart">
        </div>
        </div>
            </div>
            </form>
            </div>
            <?php
        }
    }
    ?>
    <div style="clear:both"></div>
    <h2 class="text-center text-danger mb-5" 
    style="font-family: 'Abril Fatface', cursive; background-color: black"> YOUR SHOPING ITEMS AND PRICE DETAIL</h2>
    <div class="table-responsive">
    <table class="table table-bordered">
    <tr>
    <th width="40%">Product Name</th>
    <th width="10%">Quantity</th>
    <th width="20%">Price Details</th>
    <th width="15%">Order Total</th>
    <th width="5%">Remove</th>
    </tr>
    <?php
    if(!empty($_SESSION["cart"]))
    {
        $total = 0;
        foreach($_SESSION["cart"] as $keys => $values)
        {
            ?>
            <tr>
            <td><?php echo $values["item_name"]; ?></td>
            <td><?php echo $values["item_quantity"] ?></td>
            <td>$ <?php echo $values["product_price"]; ?></td>
            <td>$ <?php echo number_format($values["item_quantity"] * $values["product_price"], 2); ?></td>
            <td><a href="shop.php?action=delete&id=<?php echo $values["product_id"]; ?>"><span class="text-danger">X</span></a></td>
            </tr>
            <?php 
            $total = $total + ($values["item_quantity"] * $values["product_price"]);
        }
        ?>
        <tr>
        <td colspan="3" align="right">Total</td>
        <td align="right">$ <?php echo number_format($total, 2); ?></td>
        <td></td>
        </tr>
        <?php
    }
    ?>

    </table>
    </div>
    </div>
 </body>
</html>