<?php
class ModelStoreCompare extends Model
{
    public function getCompare()
    {
        $result = array();

        if (!empty($_SESSION['compare'])) {
            $result = $_SESSION['compare'];
        }

        return $result;
    }

    public function addCompare($product_id)
    {
        if (!isset($_SESSION['compare'])) {
            $_SESSION['compare'] = array();
        }
        if (!in_array($product_id, $_SESSION['compare'])) {
            if (count($_SESSION['compare']) >= 4) {
                array_shift($_SESSION['compare']);
            }
            $_SESSION['compare'][] = (int)$product_id;
        }
    }

    public function deleteCompare($product_id)
    {
        if (!empty($_SESSION['compare'])) {
            $key = array_search($product_id, $_SESSION['compare']);

            if ($key !== false) {
                unset($_SESSION['compare'][$key]);
            }
        }
    }
}
