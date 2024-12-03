
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- icon bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    .dataTables_filter {
            visibility: hidden;
        }
        .dataTables_paginate {
            position: absolute;
            right: 12px;
            margin-top: -2.5em; /* Sesuaikan dengan tinggi pagination */
        }

    .sidebar {
      height: 100%;
      width: 250px;
      position: fixed;
      top: 0;
      left: -250px;
      background-color: blue;
      padding-top: 60px;
      transition: all 0.3s ease;
    }

    .sidebar.show {
      left: 0;
    }

    .sidebar ul.components {
      padding: 20px 0;
    }

    .sidebar ul li {
      padding: 10px 15px;
      font-size: 1.2em;
    }

    .sidebar ul li ul.dropdown-menu {
      background-color: #444;
      border: none;
    }

    .sidebar ul li ul.dropdown-menu li a {
      color: #fff !important;
    }

    .navbar-toggler {
      color: #fff !important;
    }

    .search-icon {
      position: relative;
      cursor: pointer;
    }

    .search-icon input {
      display: none;
    }

    .search-icon i {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
    }

    .email-icon {
      position: relative;
      cursor: pointer;
    }

    .email-icon .dropdown-menu {
      max-height: 200px;
      overflow-y: auto;
    }

    .profile-icon img {
      width: 30px;
      height: 30px;
      border-radius: 50%;
    }
  </style>
</head>
<body>
    <header>
        <nav class="navbar bg-primary navbar-expand-lg shadow-sm sticky-top">
            <div class="container-fluid">
              <img src="Logo/logomedsa.png" alt="logo" width="50" class="me-5">
              <button class="navbar-toggler" type="button" id="tombol-navigasi-bar" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                    <a class="nav-link text-white fw-bold" id="dashboard-link" aria-current="page" href="https://medikaprakarsa.co.id/">Dashboard</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link text-white fw-bold" aria-current="page" href="/Absensi/login_absen.php">Event</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link text-white fw-bold" aria-current="page" href="https://karir.medikaprakarsa.co.id/">Karir</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                     Aplikasi
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="/Absensi/login_absen.php">Aplikasi Absen</a></li>
                      <li><a class="dropdown-item" href="/proc/login_proc.php">Aplikasi Proc</a></li>
                      <li><a class="dropdown-item" href="/asetbyadit/index.php">Inventaris Karyawan</a></a></li>
                     </li>
                      
                    </ul>
                  </li>
                </ul>
                  <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                      <a href="/login.php" class="nav-link text-white fw-bold">LOGIN</a>
                    </li>
                  </ul>
              </div>
            </div>
          </nav>
    </header>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
</body>
</html>
