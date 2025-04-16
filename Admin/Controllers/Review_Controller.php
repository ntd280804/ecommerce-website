<?php
require_once './Models/Review_Model.php';

class ReviewController {
    public function index() {
        $reviewmodel = new ReviewModel();
        $reviews = $reviewmodel->getAllReviews();
        include './Views/ListReviews.php';
    }
}
?>
