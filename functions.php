// Example SEO functions
function check_page_speed() {
    // Your code to check page speed
}

function check_links() {
    // Your code to check links
}

function seo_page_info() {
    // Your code to display SEO information
}
// در فایل functions.php
function add_google_analytics() {
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=YOUR_ANALYTICS_ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'YOUR_ANALYTICS_ID');
    </script>
    <?php
}
add_action('wp_head', 'add_google_analytics');
