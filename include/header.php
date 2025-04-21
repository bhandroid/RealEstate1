<header id="header" class="transparent-header-modern fixed-header-bg-white w-100">
    <div class="top-header bg-secondary">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <ul class="top-contact list-text-white  d-table">
                        <li><a href="#"><i class="fas fa-phone-alt text-success mr-1"></i>+1 243-765-4321</a></li>
                        <li><a href="#"><i class="fas fa-envelope text-success mr-1"></i>codeastro@realestatest.com</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="top-contact float-right">
                        <ul class="list-text-white d-table">
                            <li><i class="fas fa-user text-success mr-1"></i>
                            <?php  
                            if (isset($_SESSION['uid']) && isset($_SESSION['role'])) { ?>
                                <?= $_SESSION['email'] ?> | <a href="logout.php">Logout</a>
                            <?php } else { ?>
                                <a href="login.php">Login</a>&nbsp;&nbsp; | 
                            </li>
                            <li><i class="fas fa-user-plus text-success mr-1"></i><a href="register.php">Register</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-nav secondary-nav hover-success-nav py-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light p-0"> 
                        <a class="navbar-brand position-relative" href="index.php">
                            <img class="nav-logo" src="images/logo/restatelg.png" alt="">
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                            <span class="navbar-toggler-icon"></span> 
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item"> <a class="nav-link" href="index.php">Home</a></li>
                                <li class="nav-item"> <a class="nav-link" href="about.php">About</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="contact.php">Contact</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="property.php">Properties</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="agent.php">Agent</a> </li>

                                <?php  
                                if (isset($_SESSION['uid']) && isset($_SESSION['role'])) {
                                ?>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            My Account
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="nav-item"> <a class="nav-link" href="profile.php">Profile</a> </li>
                                            <li class="nav-item"> <a class="nav-link" href="feature.php">Your Property</a> </li>
                                            <li class="nav-item"> <a class="nav-link" href="logout.php">Logout</a> </li>	
                                        </ul>
                                    </li>
                                <?php } else { ?>
                                    <li class="nav-item"> <a class="nav-link" href="login.php">Login/Register</a> </li>
                                <?php } ?>
                            </ul>

                            <!-- Submit Property Button -->
                            <a class="btn btn-success d-none d-xl-block" style="border-radius:30px;" href="submitproperty.php">Submit Property</a> 

                            <!-- 🎫 Raise Ticket Button (Only for logged-in users) -->
                            <?php if (isset($_SESSION['uid'])): ?>
                                <a class="btn btn-warning d-none d-xl-block ml-2" style="border-radius:30px;" href="raise_ticket.php">🎫 Raise Ticket</a>
                            <?php endif; ?>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
