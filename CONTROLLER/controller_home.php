<?php

require_once("../MODEL/connect.php");
require_once("../MODEL/Product.php");

$productModel = new Product($conn);

// lấy NFT nổi bật
$featuredNFT = $productModel->getFeaturedNFT();

?>