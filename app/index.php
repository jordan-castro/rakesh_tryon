<?php 

$json_content = file_get_contents(__DIR__ . "/assets/models.json");
$data = json_decode($json_content, true);

function getModelId($model) {
    return $model['type'] . "_" . $model['id'];
}

function convertJsonToHtml($json) {
    $html = "<";
    $children = [];
    foreach ($json as $key => $value) {
        if ($key == "tag") {
            $html .= $value . " ";
            continue;
        }

        if ($key == "html") {
            foreach ($value as $index => $htmlValue) {
                $element = convertJsonToHtml($htmlValue);
                $children[] = $element;
            }
            continue;
        }

        // add attributes
        $html .= " " . $key . "=\"" . $value . "\"";
    }
    // close top tag
    $html .= ">";

    // Add all children
    foreach ($children as $child) {
        $html .= $child;
    }
        
    // close taag
    $html .= "</" . $json['tag'] . ">";
    return $html;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AR Try-on Demo</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            z-index: 1000;
            transition: left 0.3s ease-in-out;
        }
        .sidebar.open {
            left: 0;
        }
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }
        .main-content.sidebar-open {
            margin-left: 250px;
        }
        .ar-scene-container {
            height: calc(100vh - 56px);
            position: relative;
        }
        iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>

    <!-- Scripts -->
    <script src="/assets/js/aframe.min.js"></script>
    <script src="/assets/js/mindar-aframe.js"></script>
    <script src="/assets/js/jsshare.js"></script>
    <script type="module" src="/scripts/main.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">AR Try-on Demo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="toggleSidebar()">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="capture()">Capture</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar bg-light p-3" id="sidebar">
        <h4 class="mb-3">Settings</h4>
        <div class="mb-3">
            <label for="brightness" class="form-label">Brightness</label>
            <input type="range" class="form-range" id="brightness" min="0" max="100" value="50">
        </div>
        <div class="mb-3">
            <label for="contrast" class="form-label">Contrast</label>
            <input type="range" class="form-range" id="contrast" min="0" max="100" value="50">
        </div>
        <div class="mb-3">
            <label for="filter" class="form-label">Filter</label>
            <select class="form-select" id="filter">
                <option value="none">None</option>
                <option value="grayscale">Grayscale</option>
                <option value="sepia">Sepia</option>
            </select>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="ar-scene-container">
                        <div class="ar-scene">
                            <iframe src="/tryon.php"></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Model Switcher</h5>
                        </div>
                        <div class="card-body">
                            <div class="row" id="model-switcher-buttons">
                                <!-- Model switcher buttons will be dynamically added here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.toggle('open');
            mainContent.classList.toggle('sidebar-open');
        }
        
        setTimeout(() => {
            // update canvas widht, etc.

        }, 1000);
    </script>
</body>
</html>