<?php
/*
Template Name: Login Template
*/

get_header();

?>
<style>
    <?php include './../style.css'; ?>
</style>

<div class="wrapper">

    <div id="primary" class="content-area" >
        <main id="main" class="site-main">

    <div class="row" style="height: 100vh;">
        <div class="col-md-5" >
         <div class="login-main-text">
            <h1>Intranet<br> Pedidos y reformas</h1>
         </div>
        <div>


        </div>

      </div>
      <div class="col-md-7  bg-black">
         <div class="col-md-6 col-sm-12">
            <div class="login-form">
                <div class="dt"></div>
               <form id="login-form">
                  <div class="form-group">
                     <label>Nombre de usuario o correo electrónico</label>
                     <input type="text" name="username" class="form-control" placeholder="User Name"/>
                  </div>
                  <div class="form-group">
                     <label>Contraseña</label>
                     <input type="password" name="password" class="form-control" placeholder="Password"/>
                  </div>
                  <button type="submit" class="btn btn-submit">Acceder</button>
                  
               </form>

               <div id="login-error"></div>
            </div>
         </div>
      </div>
      </div>
        </main><!-- .site-main -->
    </div><!-- .content-area -->
</div><!-- .container -->

<script>

jQuery(document).ready(function($) {
    $('#login-form').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: formData + '&action=custom_login',
            success: function(response) {
                if (response.success) {
                    $('#login-error').html('Inicio de sesión exitoso');
                    // Aquí puedes redirigir a otra página si es necesario
                    location.href = base_url;
                } else {
                    $('#login-error').html(response.data.message);
                }
            },
            error: function(xhr, status, error) {
                
                $('#login-error').html(xhr.responseText);
            }
        });
    });
});

</script>


<?php get_footer(); ?>


<style>




.main-head{
    height: 150px;
    background: #FFF;
   
}

.sidenav {
    height: 100%;
    overflow-x: hidden;
    padding-top: 20px;
}


.main {
    padding: 0px 10px;
    background-color: black !important;
    height: 100% !important;

}

@media screen and (max-height: 450px) {
    .sidenav {padding-top: 15px;}
}

@media screen and (max-width: 450px) {
    .login-form{
        margin-top: 10%;
    }

    .register-form{
        margin-top: 10%;
    }
}

@media screen and (min-width: 768px){
    .main{
        margin-left: 40%; 
    }

    .sidenav{
        width: 40%;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
    }

    .login-form{
        margin-top: 80%;
    }

    .register-form{
        margin-top: 20%;
    }
}


.login-main-text{
    margin-top: 20%;
    padding: 60px;
    color: #FFF;
}

.login-main-text h2{
    font-weight: 400;
}

.btn-submit{
    background-color: #FFF !important;
    color: #000;
}

.bg-black{
    background-color: #000;
    color:#FFF;
}

.login-main-text h1{
    color: #000;
    font-style: italic;
}

#login-error{
    color: #FFF;
    width: 100%;
    margin-top: 10px;
    text-align: center;
}
</style>