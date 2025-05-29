<?php
session_start();
include 'config.php';

// Ambil gambar profil user
$userImage = 'Assets/default.svg';
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT profile_pic FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch();
    if ($result && !empty($result['profile_pic'])) {
        $path = __DIR__ . '/uploads/' . $result['profile_pic'];
        if (file_exists($path)) {
            $userImage = 'uploads/' . $result['profile_pic'] . '?v=' . filemtime($path);
        }
    }
}

function getPaperMeta($url)
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return ['title' => 'Invalid URL', 'description' => ''];
    }

    $context = stream_context_create([
        'http' => ['timeout' => 5, 'user_agent' => 'Mozilla/5.0']
    ]);

    // PDF handling
    if (preg_match('/\.pdf($|\?)/i', $url)) {
        return [
            'title' => parse_url($url, PHP_URL_HOST),
            'description' => 'This is a PDF file. Open to view its contents.'
        ];
    }

    $html = @file_get_contents($url, false, $context);
    $title = parse_url($url, PHP_URL_HOST);
    $description = 'No description available.';

    if ($html) {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        libxml_clear_errors();

        // Title
        $titleNode = $dom->getElementsByTagName('title')->item(0);
        if ($titleNode && trim($titleNode->nodeValue) !== '') {
            $title = trim($titleNode->nodeValue);
        }

        // Description
        foreach ($dom->getElementsByTagName('meta') as $meta) {
            $name = strtolower($meta->getAttribute('name'));
            $prop = strtolower($meta->getAttribute('property'));
            $content = trim($meta->getAttribute('content'));

            if (($name === 'description' || $prop === 'og:description' || $name === 'twitter:description') && $content !== '') {
                $description = $content;
                break;
            }
        }

        // Fallback: Ambil <p>
        if ($description === 'No description available.') {
            $paragraphs = $dom->getElementsByTagName('p');
            foreach ($paragraphs as $p) {
                $text = trim($p->textContent);
                if (strlen($text) > 30) {
                    $description = $text;
                    break;
                }
            }
        }
    }

    return ['title' => $title, 'description' => $description];
}

$filter = $_GET['filter'] ?? '';
$papers = file('papers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Papers - PAW Tugas Akhir</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        @font-face {
            font-family: 'ATC Arquette';
            src: url("Fonts/ATCArquette-Regular.otf") format("opentype");
        }

        body {
            background-color: #FFF1D5;
            font-family: 'ATC Arquette', sans-serif;
        }

        .paper-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 20px 20%;
        }

        .paper-card {
            background: #fff9f9;
            border-radius: 10px;
            padding: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .paper-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #3B6790;
            text-decoration: none;
        }

        .paper-desc {
            font-size: 14px;
            color: #000000;
            margin-bottom: 10px;
        }

        .tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .tag {
            background-color: #9EC6F3;
            color: white;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 12px;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #BDDDE4;
            padding: 10px 18%;
        }

        .navbar img {
            height: 32px;
        }

        .menu {
            font-size: 24px;
            cursor: pointer;
            color: #fffaf9;
        }

        .side-panel {
            position: fixed;
            top: 0;
            right: -350px;
            width: 280px;
            height: 100%;
            background: #fffaf9;
            border-left: 2px solid #9EC6F3;
            padding: 20px;
            transition: right 0.3s ease;
            z-index: 1001;
        }

        .side-panel.show {
            right: 0;
        }

        .side-panel .find-paper {
            background: #3B6790;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
        }

        .side-panel input {
            padding: 6px;
            width: 100%;
            margin-top: 5px;
        }

        .side-panel button {
            margin-top: 10px;
            padding: 6px 12px;
            background: #3B6790;
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

        @media (max-width: 768px) {
            .paper-list {
                padding: 20px 10%;
            }

            .navbar {
                padding: 10px 10%;
            }
        }
    </style>
</head>

<body>

    <div class="overlay" id="overlay"></div>

    <header class="navbar">
        <img src="Assets/tol2bang.svg" alt="Logo" />
        <div class="menu">☰</div>
    </header>

    <div id="sidepanel" class="side-panel">
        <div class="user text-center mb-6">
            <a href="profile.php" style="text-decoration: none; color: inherit;">
                <img src="<?= $userImage ?>" alt="User Profile"
                    style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; cursor: pointer;" />
                <p><?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></p>
            </a>
        </div>

        <p>CAN’T FIND WHAT YOU’RE LOOKING FOR?</p>
        <a href="videos.php" class="find-paper">Find “Videos”</a>

        <p style="margin-top: 20px;">Or try using filters</p>
        <form method="get">
            <input name="filter" placeholder="e.g. UI/UX" value="<?= htmlspecialchars($filter) ?>" />
            <button type="submit">Apply</button>
        </form>
    </div>

    <div class="paper-list">
        <?php foreach ($papers as $line): ?>
            <?php
            $parts = explode('|', $line);
            if (count($parts) < 2)
                continue;

            $url = trim($parts[0]);
            $tagsStr = $parts[1];
            $tags = array_map('trim', explode(',', $tagsStr));

            if ($filter && !in_array($filter, $tags))
                continue;

            $meta = getPaperMeta($url);
            ?>
            <div class="paper-card">
                <a href="<?= htmlspecialchars($url) ?>" target="_blank"
                    class="paper-title"><?= htmlspecialchars($meta['title']) ?></a>
                <div class="paper-desc"><?= htmlspecialchars($meta['description']) ?></div>
                <div class="tags">
                    <?php foreach ($tags as $tag): ?>
                        <span class="tag"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
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
    </script>

</body>

</html>