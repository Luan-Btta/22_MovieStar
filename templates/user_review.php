<?php

if($review->user->image == ""){
    $review->user->image = "user.png";
}

?>

<div class="col-md-12" id="review">
    <div class="row">
        <div class="col-md-1">
            <div class="profile-image-container" id="review-image"
                style="background-image: url('<?= $BASE_URL ?>img/users/<?= $review->user->image ?>');"></div>
        </div>
        <div class="col-md-9" id="author-details-container">
            <h4 class="author-name"><a href="<?= $BASE_URL ?>profile.php/?id=<?= $review->user->id ?>"><?= $review->user->getFullName($review->user) ?></a></h4>
            <p><i class="fas fa-star"></i> <?= $review->rating ?></p>
        </div>
        <div class="col-md-12">
            <p class="comment-title">Comentário: </p>
            <p><?= $review->review ?></p>
        </div>
    </div>
</div>