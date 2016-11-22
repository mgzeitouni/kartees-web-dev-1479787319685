
<div class="col-sm-4" style="float: none; margin: 0 auto; padding: 20px;">
<?php echo validation_errors(); ?>
<?php echo form_open('verifylogin'); ?>
     <label for="username">Username:</label>
     <input class="form-control" placeholder="example@kartees.com" type="text" size="20" id="username" name="username"/>
     <br/>
     <label for="password">Password:</label>
     <input class="form-control" type="password" placeholder="Password" size="20" id="passowrd" name="password"/>
     <br/>
     <input class="form-control" type="submit" value="Login"/>
   </form>
</div>