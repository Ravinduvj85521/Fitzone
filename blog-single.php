<?php
include 'includes/config.php';

// Validate and sanitize the ID
$post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$post_id) {
    header("Location: blog.php");
    exit();
}

// Fetch the specific post with prepared statement
try {
    $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    
    if (!$post) {
        header("Location: blog.php");
        exit();
    }
} catch (Exception $e) {
    error_log("Error fetching blog post: " . $e->getMessage());
    header("Location: blog.php");
    exit();
}

$pageTitle = htmlspecialchars($post['title']) . " | FitZone Blog";
$customCSS = "blog-single.css";
include 'includes/header.php';
?>

<section class="single-post-section">
    <article class="full-post">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <p class="post-meta">
            Posted on <?= date('F j, Y', strtotime($post['post_date'])) ?>
            <?php if (!empty($post['author'])): ?>
                | By <?= htmlspecialchars($post['author']) ?>
            <?php endif; ?>
        </p>
        
        <img src="assets/images/blog/<?= htmlspecialchars($post['image_url']) ?>" 
             alt="<?= htmlspecialchars($post['title']) ?>" 
             class="featured-image">
             
        <div class="post-content">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
        
        <a href="blog.php" class="back-to-blog">← Back to Blog</a>
    </article>
</section>

<?php include 'includes/footer.php'; ?>