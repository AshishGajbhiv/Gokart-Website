<?php
// includes/header.php
?>
<style>
  #check-status{
    color: white;
    text-decoration: none;
  }
  #check-status:hover{
    color: #ff3c00; 
  }
</style>
<header>
  <div class="logo">
    <img src="/gokart/assets/images/logoo-bg.png" alt="Logo">
  </div>

  <nav>
    <ul id="navMenu">
      <li><a href="/gokart/index.php">Home</a></li>
      <li><a href="/gokart/pages/about.php">About</a></li>
      <!-- <li><a href="/gokart/pages/register.php">Register</a></li> -->
      <li><a href="/gokart/pages/contact.php">Contact</a></li>
      <li><a href="/gokart/pages/events.php">Events</a></li>
      <a id="check-status" href="/gokart/pages/check_status.php">Check Status</a>

      <li><a href="/gokart/admin/login.php" class="nav-btn">Admin Login</a></li>

      
    </ul>
  </nav>

  <div class="menu-toggle" onclick="toggleMenu()">☰</div>
</header>
