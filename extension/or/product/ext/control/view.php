<?php
helper::importControl('product');
class myProduct extends product
{
    /**
     * 查看产品。
     * View a product.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function view($productID)
    {
        /* Get product requirement counts. */
        $this->view->productURs = $this->product->getStatByID($productID, 'requirement');

        parent::view($productID);
    }
}
