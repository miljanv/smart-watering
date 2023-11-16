<?php
include('db/connect.php');
include('db/sensorService.php');
include('db/deviceService.php');

if (!isset($_SESSION['id'])) {
    header('location: login.php');
    exit();
}

$result_devices = getDevices($conn);
$selected_device_id = getSelectedDeviceId();
$result_latest_humidity_data = getLatestData($conn, $selected_device_id, 'humidity');
$result_latest_ph_data = getLatestData($conn, $selected_device_id, 'ph');

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>Dashboard</title>
    <meta name="description" content=""/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="robots" content="all,follow"/>
    <!-- Google fonts - Poppins -->
    <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Poppins:300,400,700"
    />
    <!-- Choices CSS-->
    <link
            rel="stylesheet"
            href="vendor/choices.js/public/assets/styles/choices.min.css"
    />
    <!-- theme stylesheet-->
    <link
            rel="stylesheet"
            href="css/style.default.css"
            id="theme-stylesheet"
    />
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="css/custom.css"/>
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/favicon.ico"/>
    <!-- Tweaks for older IEs-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script
    ><![endif]-->
</head>
<body>
<div class="page">
    <!-- Main Navbar-->
    <header class="header z-index-50">
        <nav
                class="nav navbar py-3 px-0 shadowid=" shadow-host-companion
        "-sm text-white position-relative"
        >
        <!-- Search Box-->
        <div class="search-box shadow-sm">
            <button class="dismiss d-flex align-items-center">
                <svg class="svg-icon svg-icon-heavy">
                    <use xlink:href="#close-1"></use>
                </svg>
            </button>
            <form id="searchForm" action="#" role="search">
                <input
                        class="form-control shadow-0"
                        type="text"
                        placeholder="What are you looking for..."
                />
            </form>
        </div>
        <div class="container-fluid w-100">
            <div
                    class="navbar-holder d-flex align-items-center justify-content-between w-100"
            >
                <!-- Navbar Header-->
                <div class="navbar-header">
                    <!-- Navbar Brand --><a
                            class="navbar-brand d-none d-sm-inline-block"
                            href="index.php"
                    >
                        <div class="brand-text d-none d-lg-inline-block">
                            <span>Smart </span> <strong>Watering</strong>
                        </div>
                        <div
                                class="brand-text d-none d-sm-inline-block d-lg-none"
                        >
                            <strong>BD</strong>
                        </div>
                    </a
                    >
                    <!-- Toggle Button--><a
                            class="menu-btn active"
                            id="toggle-btn"
                            href="#"
                    ><span></span><span></span><span></span
                        ></a>
                </div>
                <!-- Navbar Menu -->
                <ul
                        class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center"
                >
                    <!-- Search-->
                    <li class="nav-item d-flex align-items-center">
                        <a id="search" href="#">
                            <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                                <use xlink:href="#find-1"></use>
                            </svg
                            >
                        </a>
                    </li>

                    <!-- Logout    -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href='logout.php'>
                            <span class="d-none d-sm-inline">Logout</span>
                            <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                                <use xlink:href="#security-1"></use>
                            </svg
                            >
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        </nav>
    </header>
    <div class="page-content d-flex align-items-stretch">
        <!-- Side Navbar -->
        <nav class="side-navbar z-index-40">
            <!-- Sidebar Navidation Menus--><span
                    class="text-uppercase text-gray-400 text-xs letter-spacing-0 mx-3 px-2 heading"
            >Main</span
            >
            <ul class="list-unstyled py-4">
                <li class="sidebar-item active">
                    <a class="sidebar-link" href="index.php">
                        <svg
                                class="svg-icon svg-icon-sm svg-icon-heavy me-xl-2"
                        >
                            <use xlink:href="#real-estate-1"></use>
                        </svg
                        >
                        Dashboard
                    </a>
                </li>
            </ul>
        </nav>
        <div class="content-inner w-100">
            <!-- Page Header-->
            <header class="bg-white shadow-sm px-4 py-3 z-index-20">
                <div class="container-fluid px-0">
                    <h2 class="mb-0 p-1">Dashboard</h2>
                    <div class="d-flex p-1 gap-2">
                        <label for="device">Choose Device:</label>
                        <form method="get">
                            <select id="device" name="device" onchange="this.form.submit()">
                                <?php while ($row_device = mysqli_fetch_assoc($result_devices)) : ?>
                                    <option value="<?php echo $row_device['id']; ?>" <?php echo ($selected_device_id == $row_device['id']) ? 'selected' : ''; ?>>
                                        <?php echo $row_device['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </form>
                    </div>
                </div>
            </header>
            <!-- Dashboard Counts Section-->
            <section class="pb-0">
                <div class="d-flex justify-content-around flex-wrap">
                    <div class="card align-items-center" style="min-width: 300px">
                        <!-- Item Humidity-->
                        <?php while ($row_latest_data = mysqli_fetch_assoc($result_latest_humidity_data)) : ?>
                            <div
                                    class="col-xl-6 col-sm-6 py-4"
                            >
                                <div class="d-flex align-items-center">

                                    <div class="mx-3">
                                        <h6 class="h4 fw-light text-gray-600 mb-3" style="width: 5rem">
                                            Humidity
                                        </h6>
                                        <div class="progress" style="height: 4px">
                                            <div
                                                    class="progress-bar bg-blue"
                                                    role="progressbar"
                                                    style="width: <?php echo $row_latest_data['value'] ?>%; height: 4px"
                                                    aria-valuenow="0"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"
                                            ></div>
                                        </div>
                                    </div>
                                    <div class="number">
                                        <strong class="text-lg"><?php echo $row_latest_data['value']; ?></strong>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="card align-items-center" style="min-width: 300px">
                        <!-- Item Ph-->
                        <?php while ($row_latest_data = mysqli_fetch_assoc($result_latest_ph_data)) : ?>
                            <div
                                    class="col-xl-6 col-sm-6 py-4"
                            >
                                <div class="d-flex align-items-center">

                                    <div class="mx-3">
                                        <h6 class="h4 fw-light text-gray-600 mb-3 text-center" style="width: 5rem">
                                            Ph
                                        </h6>
                                        <div class="progress" style="height: 4px">
                                            <div
                                                    class="progress-bar bg-red"
                                                    role="progressbar"
                                                    style="width: <?php echo $row_latest_data['value'] * 10 ?>%; height: 4px"
                                                    aria-valuenow="0"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"
                                            ></div>
                                        </div>
                                    </div>
                                    <div class="number">
                                        <strong class="text-lg"><?php echo $row_latest_data['value']; ?></strong>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
            <!-- Dashboard Header Section    -->
            <section class="pb-0 text-center">

                <div class="container-fluid" style="margin-bottom: 5rem">

                    <div class="row justify-content-center">

                        <!-- Line Chart -->
                        <div class="col-lg-6 col-10" style="margin-bottom: 7rem">
                            <div class="mb-2">
                                <input type="text" name="dateRangeHumidity" value="01/01/2023 - 01/12/2023"
                                       style="margin-bottom: 1rem; width: 80%; text-align:center"/>
                                <button id="clearRangeHumidity" type="button" class="btn btn-info"
                                >CLEAR
                                </button>
                            </div>
                            <div class="card mb-0 h-100">
                                <div class="card-body">
                                    <canvas id="lineChartHumidity"></canvas>
                                </div>
                                <button id="exportButtonHumidity" type="button" class="btn btn-info"
                                        style="border-radius: unset">Export CSV
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-6 col-10" style="margin-bottom: 7rem">
                            <div class="mb-2">
                                <input type="text" name="dateRangePh" value="01/01/2023 - 01/12/2023"
                                       style="margin-bottom: 1rem; width: 80%; text-align:center"/>
                                <button id="clearRangePh" type="button" class="btn btn-danger"
                                >CLEAR
                                </button>
                            </div>
                            <div class="card mb-0 h-100">
                                <div class="card-body">
                                    <canvas id="lineChartPh"></canvas>
                                </div>
                                <button id="exportButtonPh" type="button" class="btn btn-danger"
                                        style="border-radius: unset">Export CSV
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <footer
                    class="position-absolute bottom-0 bg-darkBlue text-white text-center py-3 w-100 text-xs"
                    id="footer"
            >
                <div class="container-fluid">
                    <div class="row gy-2">
                        <div class="col-sm-6 text-sm-start">
                            <p class="mb-0">Smart Watering &copy; 2017-2023</p>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <p class="mb-0">
                                Design by
                                <a
                                        href="https://smartwatering.rs/"
                                        target="_blank"
                                        class="text-white text-decoration-none"
                                >Smart Watering</a
                                >
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>
<!-- JavaScript files-->
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="vendor/just-validate/js/just-validate.min.js"></script>
<script src="vendor/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="js/charts-home.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<!-- Main File-->
<script src="js/front.js"></script>
<script>
    // ------------------------------------------------------- //
    //   Inject SVG Sprite -
    //   see more here
    //   https://css-tricks.com/ajaxing-svg-sprite/
    // ------------------------------------------------------ //
    function injectSvgSprite(path) {
        var ajax = new XMLHttpRequest();
        ajax.open('GET', path, true);
        ajax.send();
        ajax.onload = function (e) {
            var div = document.createElement('div');
            div.className = 'd-none';
            div.innerHTML = ajax.responseText;
            document.body.insertBefore(
                div,
                document.body.childNodes[0]
            );
        };
    }

    // this is set to BootstrapTemple website as you cannot
    // inject local SVG sprite (using only 'icons/orion-svg-sprite.svg' path)
    // while using file:// protocol
    // pls don't forget to change to your domain :)
    injectSvgSprite(
        'https://bootstraptemple.com/files/icons/orion-svg-sprite.svg'
    );
</script>
<!-- FontAwesome CSS - loading as last, so it doesn't block rendering-->
<link
        rel="stylesheet"
        href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
        crossorigin="anonymous"
/>
</body>
</html>
