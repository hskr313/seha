<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Profile</h5>
        </div>
        <div class="card-body">
            <form id="profileForm" method="POST" action="/seha/public/user/updateProfile">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="username"><strong>Username:</strong></label>
                            <input type="text" id="username" name="username" class="form-control-plaintext" value="<?php echo $user->username; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email"><strong>Email:</strong></label>
                            <input type="email" id="email" name="email" class="form-control-plaintext" value="<?php echo $user->email; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="time_credit"><strong>Time Credit:</strong></label>
                            <input type="text" id="time_credit" class="form-control-plaintext" value="<?php echo $user->time_credit; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- Add any additional profile information or actions here -->
                    </div>
                </div>
                <div id="editButtons" class="mt-3 d-none">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mt-3" id="editProfileBtn" onclick="editProfile()">Edit Profile</a>
            </form>
        </div>
    </div>
</div>

<script>
    function editProfile() {
        document.getElementById('username').removeAttribute('readonly');
        document.getElementById('username').classList.remove('form-control-plaintext');
        document.getElementById('username').classList.add('form-control');

        document.getElementById('email').removeAttribute('readonly');
        document.getElementById('email').classList.remove('form-control-plaintext');
        document.getElementById('email').classList.add('form-control');

        document.getElementById('editProfileBtn').classList.add('d-none');
        document.getElementById('editButtons').classList.remove('d-none');
    }

    function cancelEdit() {
        document.getElementById('username').setAttribute('readonly', 'readonly');
        document.getElementById('username').classList.add('form-control-plaintext');
        document.getElementById('username').classList.remove('form-control');

        document.getElementById('email').setAttribute('readonly', 'readonly');
        document.getElementById('email').classList.add('form-control-plaintext');
        document.getElementById('email').classList.remove('form-control');

        document.getElementById('editProfileBtn').classList.remove('d-none');
        document.getElementById('editButtons').classList.add('d-none');
    }
</script>
