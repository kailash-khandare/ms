<?php
session_start();

// Try session name
$name = isset($_SESSION['name']) ? $_SESSION['name'] : null;

$conn = new mysqli("mysql1003.site4now.net", "abc552_maheshd", "shaikh123", "db_abc552_maheshd");
// $conn = new mysqli("localhost", "root", "", "stencil");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fallback: fetch last registered user if session name not set
if (!$name) {
    $nameQuery = "SELECT name FROM signup ORDER BY id DESC LIMIT 1";
    $nameResult = $conn->query($nameQuery);
    if ($nameResult && $nameResult->num_rows > 0) {
        $rowName = $nameResult->fetch_assoc();
        $name = htmlspecialchars($rowName['name']);
    } else {
        $name = 'Guest';
    }
} else {
    $name = htmlspecialchars($name);
}

// Fetch drawings
$drawQuery = "SELECT * FROM upload ORDER BY id DESC";
$drawResult = $conn->query($drawQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Uploaded Drawings | Animated Gallery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@next/dist/aos.css" rel="stylesheet" />

    <style>
        /* Base & body */
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(270deg, #14002e, #ffc93c, #5d16a1);
            background-size: 600% 600%;
            animation: gradientShift 20s ease infinite;
            color: #f0f0f0;
            min-height: 100vh;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Navbar styling */
        .navbar {
            background: rgba(13, 17, 34, 0.85);
            backdrop-filter: blur(8px);
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.7);
            border-radius: 0 0 1rem 1rem;
        }
        .navbar-brand {
            font-weight: 700;
            color: #ffc93c;
            font-size: 1.75rem;
            letter-spacing: 0.05em;
        }
        .navbar-nav .nav-link {
            color: #fff;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #ffc93c;
        }
        .navbar-nav .dropdown-toggle {
            cursor: pointer;
        }
        .navbar-nav .nav-link img {
            border-radius: 50%;
            border: 2px solid #ffc93c;
            object-fit: cover;
        }

        /* Container heading */
        h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 700;
            font-size: 2.4rem;
            color: #fff;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.6);
        }

        /* Cards grid */
        .row.g-4 {
            gap: 24px;
        }

        /* Card design */
        .card {
            background: rgba(20, 15, 40, 0.9);
            border-radius: 1rem;
            border: none;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.6);
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            cursor: default;
            user-select: none;
            position: relative;
            overflow: hidden;
            min-height: 400px;
            display: flex;
            flex-direction: column;
        }
        .card:hover {
            transform: translateY(-15px) scale(1.05);
            box-shadow: 0 20px 40px rgba(255, 201, 60, 0.6);
            z-index: 5;
        }
        .card-img-top {
            height: 240px;
            object-fit: cover;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            transition: filter 0.4s ease;
            user-select: none;
        }
        .card:hover .card-img-top {
            filter: brightness(1.1) saturate(1.3);
        }
        .card-body {
            flex: 1;
            padding: 1.25rem;
            text-align: center;
            color: #ffeb99;
            user-select: text;
        }
        .card-title {
            font-weight: 700;
            margin-bottom: 0.7rem;
            font-size: 1.3rem;
            color: #ffc93c;
        }
        .card-text {
            font-size: 1rem;
            color: #e1e1e1;
            min-height: 75px;
        }
        .card-footer {
            background: transparent;
            color: #aaa;
            text-align: center;
            font-weight: 600;
            font-size: 0.9rem;
            padding-top: 0.5rem;
            border-top: 1px solid #444;
            user-select: none;
        }

        /* Delete button */
        form.p-2 {
            position: absolute;
            top: 12px;
            right: 12px;
            margin: 0;
            padding: 0;
            z-index: 10;
        }
        form.p-2 button {
            background-color: #db3a3a;
            border: none;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            font-size: 18px;
            color: white;
            box-shadow: 0 0 8px #db3a3acc;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            line-height: 28px;
            vertical-align: middle;
        }
        form.p-2 button:hover {
            background-color: #ff5555;
            box-shadow: 0 0 12px #ff5555cc;
        }

        /* Responsive columns */
        @media (max-width: 576px) {
            .card-img-top {
                height: 180px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top px-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">🎨 ArtZone</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="publised.php">Publishing</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($name); ?>&background=0D8ABC&color=fff&rounded=true"
                                alt="Avatar" width="36" height="36" />
                            <span class="ms-2"><?php echo $name; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Drawings Gallery -->
    <main class="container py-5">
        <h2 data-aos="fade-down" data-aos-duration="1000">🎨 All Uploaded Drawings</h2>
        <div class="row g-4">
            <?php if ($drawResult && $drawResult->num_rows > 0): ?>
                <?php while ($row = $drawResult->fetch_assoc()): ?>
                    <div class="col-12 col-sm-6 col-md-4" data-aos="zoom-in" data-aos-duration="800" data-aos-once="true">
                        <div class="card h-100 shadow">
                            <form action="delete_image.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this drawing?');" class="p-2" aria-label="Delete Drawing">
                                <input type="hidden" name="id" value="<?php echo intval($row['id']); ?>" />
                                <button type="submit" title="Delete Drawing" aria-label="Delete Drawing">&times;</button>
                            </form>

                            <?php if (!empty($row['image']) && file_exists('uploads/' . $row['image'])): ?>
                                <img src="<?php echo htmlspecialchars('uploads/' . $row['image']); ?>"
                                    class="card-img-top" alt="Drawing Image" loading="lazy" />
                            <?php else: ?>
                                <img src="placeholder.jpg" class="card-img-top" alt="No Image Available" />
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['type']); ?></h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($row['des'])); ?></p>
                            </div>
                            <div class="card-footer text-center">
                                <div><strong>By:</strong> <?php echo htmlspecialchars($row['name']); ?></div>
                                <div><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact']); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center w-100 mt-5" style="color:#ffc93c;">No drawings found.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true, // animations happen only once on scroll
            duration: 700,
            easing: 'ease-in-out',
        });
    </script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
