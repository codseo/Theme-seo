<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body>

<header>
    <div class="container">
        <h1><?php echo bloginfo('name'); ?></h1>
        <p><?php echo bloginfo('description'); ?></p>
    </div>
</header>
<!-- در قسمت مناسب در فایل index.php -->
<div id="seo-analysis-tool">
    <h3>SEO Analysis Tool</h3>
    <p>Enter your website URL to analyze:</p>
    <input type="url" id="websiteUrl" placeholder="https://example.com">
    <button onclick="analyzeWebsite()">Analyze</button>
    <div id="analysisResult"></div>
</div>
<!-- در قسمت مناسب در فایل index.php -->
<div id="seo-analysis-tool">
    <h3>SEO Analysis Tool</h3>
    <p>Enter your website URL to analyze:</p>
    <input type="url" id="websiteUrl" placeholder="https://example.com">
    <button onclick="analyzeWebsite()">Analyze</button>
    <div id="analysisResult"></div>
</div>
