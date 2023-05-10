<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="styles/images/favicon.png"/>
    <link rel="stylesheet" href="styles/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>EduConnect</title>
</head>
<body>
<!-- Section: Design Block -->
<section class="background-radial-gradient overflow-hidden">
    <style>
      .background-radial-gradient {
        background-color: hsl(218, 41%, 15%);
        background-image: radial-gradient(650px circle at 0% 0%,
            hsl(218, 41%, 35%) 15%,
            hsl(218, 41%, 30%) 35%,
            hsl(218, 41%, 20%) 75%,
            hsl(218, 41%, 19%) 80%,
            transparent 100%),
          radial-gradient(1250px circle at 100% 100%,
            hsl(218, 41%, 45%) 15%,
            hsl(218, 41%, 30%) 35%,
            hsl(218, 41%, 20%) 75%,
            hsl(218, 41%, 19%) 80%,
            transparent 100%);
      }
  
      #radius-shape-1 {
        height: 220px;
        width: 220px;
        top: -60px;
        left: -130px;
        background: radial-gradient(#44006b, #ad1fff);
        overflow: hidden;
      }
  
      #radius-shape-2 {
        border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
        bottom: -60px;
        right: -110px;
        width: 300px;
        height: 300px;
        background: radial-gradient(#44006b, #ad1fff);
        overflow: hidden;
      }
  
      .bg-glass {
        background-color: hsla(0, 0%, 100%, 0.9) !important;
        backdrop-filter: saturate(200%) blur(25px);
      }
      .btn-primary {
    --mdb-btn-bg: #3b71ca;
    --mdb-btn-color: #fff;
    --mdb-btn-box-shadow: 0 4px 9px -4px #3b71ca;
    --mdb-btn-hover-bg: #386bc0;
    --mdb-btn-hover-color: #fff;
    --mdb-btn-focus-bg: #386bc0;
    --mdb-btn-focus-color: #fff;
    --mdb-btn-active-bg: #3566b6;
    --mdb-btn-active-color: #fff;
}
    </style>
  
    <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
      <div class="row gx-lg-5 align-items-center mb-5">
        <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
          <h1 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
            Bienvenue sur  <br />
            <span style="color: hsl(218, 81%, 75%)">EduConnect</span>
          </h1>
          <p class="mb-4 opacity-70" style="color: hsl(218, 81%, 85%)">
            La plateforme  qui simplifie l'enregistrement et la justification d'absence dans un établissement scolaire le plus possible.
          </p>
        </div>
  
        <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
          <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
          <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>
  
          <div class="card bg-glass">
            <div class="card-body px-4 py-5 px-md-5">

                <form method="post" action="includes/login-schedule.checks.php">
                    <h5 class="inputsbox">Veuillez vous connecter à votre espace personnel:</h5>
                    
                <div class="select-box">
                    <select id="user-role" name="user_role">
                      <option value="surveillant">
                        Surveillant général
                      </option>
                      <option value="eleve">
                        Elève
                      </option>
                      <option value="teacher">
                        Enseignant
                      </option>
                    </select>
                </div>
                    <div class="form-outline">
                      <input type="text" name="username" placeholder="Nom d'utilisateur/E-mail" id="form3Example2" class="form-control" />
                    </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                  <input type="password" name="password" placeholder="Password" id="form3Example4" class="form-control" />
                </div>
  
                <!-- Submit input -->
                <input type="submit" class="btnn" value="Se connecter">

                <div class="forgot-password-link">
                <a href="#">
                    Mot de passe oublié?
                </a>
                </div>

            </form>    

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</body>
</html>