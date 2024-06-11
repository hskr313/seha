<div class="card">
    <div class="card-body">
        <h5 class="card-title">User Information</h5>
        <?php
        $userModel = new UserRepository();

        $userCount = $userModel->getUsersCount();

        $userID1 = $userModel->findById(1);
        ?>
        <p class="card-text">Number of users: <?php echo $userCount; ?></p>
        <p class="card-text">UserID 1: <?php if ($userID1) { echo $userID1->username; } else { echo 'User not found'; } ?></p>
    </div>
</div>
