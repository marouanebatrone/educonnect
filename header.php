<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <div class="col-md-3 mb-2 mb-md-0">
          <a href="about.php" class="d-inline-flex link-body-emphasis text-decoration-none">
            <img src="styles/images/logo.png" alt="" >
          </a>
        </div>
  
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
          <li class="lielement">
            <a href="/educonnect/absence.php" class="nav-link px-2 <?php echo ($_SERVER['REQUEST_URI'] == '/educonnect/absence.php') ? 'link-secondary' : ''; ?>">Voir l'absence</a>
          </li>
          <?php if($_SESSION['user_role'] == 'surveillant') { ?>
            <li class="lielement"><a href="justificatifs.php" class="nav-link px-2 <?php echo ($_SERVER['REQUEST_URI'] == '/educonnect/justificatifs.php') ? 'link-secondary' : ''; ?>">Voir les justificatifs</a></li>
            <?php } else if($_SESSION['user_role'] == 'eleve') { ?>
              <li class="lielement"><a href="justification.php" class="nav-link px-2 <?php echo ($_SERVER['REQUEST_URI'] == '/educonnect/justification.php') ? 'link-secondary' : ''; ?>">Justification d'absence</a></li>
              <?php } ?>
              <li class="lielement">
                <a href="/educonnect/profile.php" class="nav-link px-2 <?php echo ($_SERVER['REQUEST_URI'] == '/educonnect/profile.php') ? 'link-secondary' : ''; ?>">Mon profil</a>
              </li>
         </ul>
  
        <div class="col-md-3 text-end">
          <a href="includes/logout.php"><button type="button" class="btn btnh btn-outline-primary me-2">Déconnexion</button></a>
        </div>
</header>
<style>
  header
{
  background-color: #fafdff;
}
.nav-link:hover
{
    background-color: #b5cef5;
}
.btnh:hover
{
    background-color: #356ac7;
    --bs-btn-hover-border-color: none;
}
.lielement
{
  margin-right: 20px;
}
.link-secondary {
  color: gray !important;
}
</style>