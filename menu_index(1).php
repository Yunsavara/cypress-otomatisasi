<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Page Title</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>
    /* General Styles */
    body {
      background-color: #f0f0f0; /* Background color */
      color: black; /* Text color */
    }
    
    /* Header Styles */
    .header {
      width: 100vw;
      background-color: #ffffff;
      display: flex;
      align-items: center;
      padding: 20px;
      border-bottom: 1px solid #ddd;
    }
    .logo-container {
      display: flex;
      align-items: center;
      flex: 1;
    }
    .logo {
      width: 50px;
      height: auto;
      margin-right: 15px;
    }
    .logo-text {
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }
    .tagline {
      font-size: 14px;
      color: #777;
    }
    .cta-button {
      background-color: #d4af37;
      color: white;
      border: none;
      padding: 8px 15px;
      cursor: pointer;
      font-size: 14px;
      border-radius: 5px;
    }
    
    /* Navbar Styles */
    .navbar {
      background-color: #f0f0f0;
    }
    .nav-link {
      color: black;
    }
    .nav-link:hover {
      color: #0B6BA8;
    }
    .navbar-text {
      color: #003d5b;
      font-size: 0.9rem;
    }
    
    /* DataTables Styles */
    .dataTables_filter {
      visibility: hidden;
    }
    .dataTables_paginate {
      position: absolute;
      right: 12px;
      margin-top: -2.5em;
    }
  </style>
</head>
<body>
  
  <!-- Header Section -->
  <header class="header">
    <div class="logo-container">
      <img src="Logomedsa.png" alt="Logo" class="logo">
      <div>
        <div class="logo-text">MEDIKA PRAKARSA</div>
        <div class="tagline">Healthcare. Simplified.</div>
      </div>
    </div>
    <button class="cta-button">NIRWANA GROUP</button>
  </header>
  
  <!-- Navbar Section -->
  <header>
    <nav class="navbar navbar-expand-lg shadow-sm sticky-top">
      <div class="container-fluid">
        <img src="ig.png" alt="Logo" width="50" class="me-5">
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link fw-bold" aria-current="page" href="https://www.medikaprakarsa.co.id/">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold" href="/Absensi/login_absen.php">The Clinic</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold" href="/inventaris/index.php">Our Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold" href="#">Meet the Team</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold" href="#">The Subsidiary</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold" href="#">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold" href="https://karir.medikaprakarsa.co.id/">Careers</a>
            </li>
          </ul>
          
          <span class="navbar-text">
            Member of NIRWANA ABADI SENTOSA
          </span>
          
          <button class="cta-button ms-3" onclick="window.location.href='login.php'">LOGIN</button>
        </div>
      </div>
    </nav>
  </header>
  
  <!-- Add your page content here -->
  
  <!-- JavaScript Libraries -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

</body>
</html>
