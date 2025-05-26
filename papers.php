<?php
session_start();
include 'config.php';

function getPaperMeta($url) {
    $context = stream_context_create([
        'http' => ['timeout' => 5]
    ]);

    $html = @file_get_contents($url, false, $context);
    if (!$html) {
        return ['title' => 'Unknown Title', 'description' => 'No description available.'];
    }

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    libxml_clear_errors();

    $title = $dom->getElementsByTagName('title')->item(0)?->nodeValue ?? 'No title';
    $metaTags = $dom->getElementsByTagName('meta');
    $description = 'No description available.';

    foreach ($metaTags as $meta) {
        if (strtolower($meta->getAttribute('name')) === 'description') {
            $description = $meta->getAttribute('content');
            break;
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
            color: #b33c3c;
        }

        .paper-desc {
            font-size: 14px;
            color: #602f32;
            margin-bottom: 10px;
        }

        .tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .tag {
            background-color: #d44;
            color: white;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 12px;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #b33c3c;
            padding: 10px 18%;
        }

        .navbar img {
            height: 32px;
        }

        @media (max-width: 768px) {
            .paper-list {
                padding: 20px 15%;
            }

            .navbar {
                padding: 10px 15%;
            }
        }
    </style>
</head>

<body>
    <header class="navbar">
        <img src="Assets/logo.svg" alt="Logo" />
        <form method="get" style="margin: 0;">
            <input type="text" name="filter" placeholder="e.g. Graphic Design" value="<?= htmlspecialchars($filter) ?>"
                style="padding: 6px 10px; border-radius: 6px; border: none; width: 180px; font-family: 'ATC Arquette', sans-serif;" />
            <button type="submit"
                style="padding: 6px 12px; background-color: #fffaf9; color: #b33c3c; border-radius: 6px; border: none; margin-left: 5px; font-weight: bold; cursor: pointer;">Filter</button>
        </form>
    </header>

    <div class="paper-list">
        <?php foreach ($papers as $line): ?>
            <?php
            list($url, $tagsStr) = explode('|', $line);
            $tags = array_map('trim', explode(',', $tagsStr));

            if ($filter && !in_array($filter, $tags)) continue;

            $meta = getPaperMeta($url);
            ?>
            <div class="paper-card">
                <a href="<?= htmlspecialchars($url) ?>" target="_blank" class="paper-title"><?= htmlspecialchars($meta['title']) ?></a>
                <div class="paper-desc"><?= htmlspecialchars($meta['description']) ?></div>
                <div class="tags">
                    <?php foreach ($tags as $tag): ?>
                        <span class="tag"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>
