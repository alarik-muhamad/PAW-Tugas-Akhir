<?php
session_start();
include 'config.php';

$userImage = 'Assets/default.svg';

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT profile_pic FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch();

    if ($result && !empty($result['profile_pic'])) {
        $path = __DIR__ . '/uploads/' . $result['profile_pic'];
        if (file_exists($path)) {
            // Add timestamp to avoid browser cache
            $userImage = 'uploads/' . $result['profile_pic'] . '?v=' . filemtime($path);
        }
    }
}

function getYouTubeData($url)
{
    parse_str(parse_url($url, PHP_URL_QUERY), $query);
    $videoId = $query['v'] ?? '';

    $apiUrl = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=$videoId&format=json";
    $json = @file_get_contents($apiUrl);
    if (!$json) {
        return ['title' => 'Title not found', 'description' => 'No description available.', 'videoId' => $videoId];
    }

    $data = json_decode($json, true);
    return [
        'title' => $data['title'] ?? 'No title',
        'description' => $data['author_name'] ?? 'No description',
        'videoId' => $videoId
    ];
}

$filter = $_GET['filter'] ?? '';
$videos = file('videos.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>PAW Tugas Akhir</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .side-panel {
            position: fixed;
            top: 0;
            right: -350px;
            width: 280px;
            height: 100%;
            background: #fffaf9;
            border-left: 2px solid #bc3a41;
            padding: 20px;
            transition: right 0.3s ease;
            z-index: 1001;
        }

        .side-panel.show {
            right: 0;
        }

        .side-panel .find-paper {
            display: inline-block;
            background: #f9232c;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
        }

        .side-panel input {
            padding: 6px;
            width: 100%;
            margin-top: 5px;
        }

        .side-panel button {
            margin-top: 10px;
            padding: 6px 12px;
            background: #bc3a41;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .user {
            text-align: center;
            margin-bottom: 20px;
        }

        .user p {
            margin: 10px 0 0;
            font-weight: bold;
        }

        header img {
            height: 32px;
        }

        header .menu {
            font-size: 24px;
            cursor: pointer;
            color: #fffaf9;
        }

        .overlay {
            opacity: 0;
            visibility: hidden;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1000;
            transition: opacity 0.3s ease;
        }

        .overlay.show {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body>
    <div class="overlay" id="overlay"></div>

    <header class="navbar">
        <img src="Assets/logo.svg" alt="Logo" />
        <div class="menu">☰</div>
    </header>

    <div id="sidepanel" class="side-panel">
        <div class="user text-center mb-6">
            <a href="profile.php" style="text-decoration: none; color: inherit;">
                <img src="<?= $userImage ?>" alt="User Profile"
                    style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; cursor: pointer;" />
                <p style="margin-top: 10px; font-weight: bold;">
                    <?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?>
                </p>
            </a>
        </div>

        <p>CAN’T FIND WHAT YOU’RE LOOKING FOR?</p>
        <a href="papers.php" class="find-paper">Find “Papers”</a>

        <p style="margin-top: 20px;">Or try using filters</p>
        <form method="get">
            <input name="filter" placeholder="e.g. UI/UX" value="<?= htmlspecialchars($filter) ?>" />
            <button type="submit">Apply</button>
        </form>
    </div>

    <div class="container video-list">
        <?php foreach ($videos as $line): ?>
            <?php
            list($url, $tagsStr) = explode('|', $line);
            $tags = array_map('trim', explode(',', $tagsStr));
            if ($filter && !in_array($filter, $tags)) continue;
            $data = getYouTubeData($url);
            $thumbUrl = "https://img.youtube.com/vi/{$data['videoId']}/hqdefault.jpg";
            ?>
            <div class="video-card" onclick="openVideo('<?= $url ?>')">
                <div class="video-thumb">
                    <img src="<?= $thumbUrl ?>" alt="Thumbnail">
                </div>
                <div class="video-info">
                    <div class="video-title"><?= htmlspecialchars($data['title']) ?></div>
                    <div class="video-desc"><?= htmlspecialchars($data['description']) ?></div>
                    <div class="tags">
                        <?php foreach ($tags as $tag): ?>
                            <span class="tag"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        const sidepanel = document.getElementById('sidepanel');
        const overlay = document.getElementById('overlay');
        const menuBtn = document.querySelector('.menu');

        menuBtn.addEventListener('click', () => {
            const isShown = sidepanel.classList.toggle('show');
            if (isShown) {
                overlay.classList.add('show');
            } else {
                overlay.classList.remove('show');
            }
        });

        document.addEventListener('click', (e) => {
            if (sidepanel.classList.contains('show')) {
                if (!sidepanel.contains(e.target) && !menuBtn.contains(e.target)) {
                    sidepanel.classList.remove('show');
                    overlay.classList.remove('show');
                }
            }
        });

        function openVideo(url) {
            overlay.classList.add('show');
            setTimeout(() => {
                window.location.href = url;
            }, 150);
        }
    </script>
</body>
</html>
