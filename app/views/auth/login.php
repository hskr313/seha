<!-- app/views/auth/login.php -->
<div class="text-center">
    <h1 class="h4 text-gray-900 mb-4">Bonjour !</h1>
</div>
<form class="user" method="POST" action="/seha/public/auth/loginPost">
    <div class="form-group">
        <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" aria-describedby="emailHelp" placeholder="Enter Email Address...">
    </div>
    <div class="form-group">
        <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="password" placeholder="Password">
    </div>
    <button type="submit" class="btn btn-primary btn-user btn-block">
        Login
    </button>
</form>
<hr>
<div class="text-center">
    <a class="small" href="/seha/public/auth/register">Create an Account!</a>
</div>
