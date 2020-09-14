  <form method="POST" v-if="!showLoginForm  && showForgotPassword" action="">
    <!-- <div class="alert alert-success" role="alert">
    </div> -->
    <div class="aligncenter"><h1>Reset Password</h1></div>
        
    <div class="form-group">
        <input id="email" type="email" class="form-control "  placeholder="Email" name="email" value="" required autocomplete="email" autofocus>
        <span class="invalid-feedback" role="alert">
        <strong></strong>
        </span>
    </div>
    
    <!-- close .container-fluid -->
    <div class="form-group aligncenter">
    <button type="submit" class="btn"> 
        Send Password Reset Link
    </button>
    </div>
    <div class="aligncenter"><a class="not-a-member-pro" @click="showLogin"  href="#">Don't have account? <span>Signup</span></a></div>
</form>