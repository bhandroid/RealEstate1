<header id="header" class="transparent-header-modern fixed-header-bg-white w-100">
    <div class="top-header bg-secondary">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <ul class="top-contact list-text-white d-table">
                        <li><a href="#"><i class="fas fa-phone-alt text-success mr-1"></i> +1 243-765-4321</a></li>
                        <li><a href="#"><i class="fas fa-envelope text-success mr-1"></i> Group-1@DBMT.com</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="top-contact float-end">
                        <ul class="list-text-white d-table">
                            <li><i class="fas fa-user text-success mr-1"></i>
                            <?php if (isset($_SESSION['uid']) && isset($_SESSION['role'])): ?>
                                <?= htmlspecialchars($_SESSION['email']); ?> | <a href="logout.php" class="text-white">Logout</a>
                            <?php else: ?>
                                <a href="login.php" class="text-white">Login</a> &nbsp;&nbsp; | 
                            </li>
                            <li><i class="fas fa-user-plus text-success mr-1"></i><a href="register.php" class="text-white">Register</a></li>
                            <?php endif; ?>
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
                            <img class="nav-logo" src="images/logo/restatelg.png" alt="Logo">
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="property.php">Properties</a></li>

                                <!-- ✅ My Properties tab for Sellers/Agents only -->
                                <?php if (isset($_SESSION['role']) && in_array(strtolower($_SESSION['role']), ['seller', 'agent'])): ?>
                                    <li class="nav-item"><a class="nav-link" href="my_properties.php">My Properties</a></li>
                                <?php endif; ?>

                                <!-- ✅ Favorites tab for logged-in users -->
                                <?php if (isset($_SESSION['uid'])): ?>
                                    <li class="nav-item"><a class="nav-link" href="favorites.php">Favorites</a></li>
                                <?php endif; ?>

                                <!-- ✅ My Account Dropdown -->
                                <?php if (isset($_SESSION['uid']) && isset($_SESSION['role'])): ?>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                            My Account
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                            <li><a class="dropdown-item" href="feature.php">Your Property</a></li>
                                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                        </ul>
                                    </li>
                                <?php else: ?>
                                    <li class="nav-item"><a class="nav-link" href="login.php">Login/Register</a></li>
                                <?php endif; ?>
                            </ul>

                            <!-- ✅ Submit Property Button (only for Seller, Agent, Admin) -->
                            <?php if (isset($_SESSION['role']) && in_array(strtolower($_SESSION['role']), ['seller', 'agent', 'admin'])): ?>
                                <a class="btn btn-gradient-primary d-none d-xl-block me-2" style="border-radius: 30px;" href="submitproperty.php">
                                         Submit Property
                                </a>
                            <?php endif; ?>

                            <!-- 🎫 Raise Ticket Button (all logged-in users) -->
                            <?php if (isset($_SESSION['uid'])): ?>
                                <a class="btn btn-warning d-none d-xl-block" style="border-radius: 30px;" href="raise_ticket.php">
                                     Raise Ticket
                                </a>
                            <?php endif; ?>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>


<style>
    .btn-gradient-primary {
        background: linear-gradient(90deg,rgb(11, 144, 31) 0%,rgb(22, 165, 12) 100%);
        color: white;
        border: none;
    }
    .btn-gradient-primary:hover {
        background: linear-gradient(90deg, #0072ff 0%, #00c6ff 100%);
    }
    .dropdown-menu {
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>
