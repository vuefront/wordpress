<?php
class ModelStoreWishlist extends Model
{
    public function getWishlist()
    {
        $result = array();

        if (!empty($_SESSION['wishlist'])) {
            $result = $_SESSION['wishlist'];
        }
       
        return $result;
    }

    public function addWishlist($product_id)
    {
        if (!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = array();
        }
        if (!in_array($product_id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = (int)$product_id;
        }
    }

    public function deleteWishlist($product_id)
    {
        if (!empty($_SESSION['wishlist'])) {
            $key = array_search($product_id, $_SESSION['wishlist']);

            if ($key !== false) {
                unset($_SESSION['wishlist'][$key]);
            }
        }
    }
}
