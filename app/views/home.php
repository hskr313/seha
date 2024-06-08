<div class="card my-4">
    <div class="card-body">
        <h5 class="card-title">User Information</h5>
        <?php
        $userModel = new User();
        $userCount = $userModel->getUsersCount();
        ?>
        <p class="card-text">Number of users: <?php echo $userCount['count']; ?></p>
    </div>
</div>
