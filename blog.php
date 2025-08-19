<?php
$customCSS = "blog.css";
include 'includes/config.php';

// Fetch posts with error handling
try {
    $stmt = $conn->prepare("SELECT post_id, title, image_url, content, post_date 
                          FROM blog_posts 
                          ORDER BY post_date DESC 
                          LIMIT 6");
    $stmt->execute();
    $posts = $stmt->get_result();
} catch (Exception $e) {
    error_log("Error fetching blog posts: " . $e->getMessage());
    $posts = []; // Empty array if error occurs
}
?>

<?php include 'includes/header.php'; ?>

<section class="blog-section">
    <h1>Fitness Blog</h1>
    
    <div class="blog-grid">
        <?php if ($posts && $posts->num_rows > 0): ?>
            <?php while($post = $posts->fetch_assoc()): ?>
            <article class="blog-post">
                <img src="assets/imgs/blog/<?= htmlspecialchars($post['image_url']) ?>" 
                     alt="<?= htmlspecialchars($post['title']) ?>"
                     loading="lazy">
                <div class="post-content">
                    <h2><?= htmlspecialchars($post['title']) ?></h2>
                    <p class="meta">Posted on <?= date('F j, Y', strtotime($post['post_date'])) ?></p>
                    <p><?= htmlspecialchars(substr($post['content'], 0, 150)) ?>...</p>
                    <a href="blog-single.php?id=<?= (int)$post['post_id'] ?>" class="read-more">Read More</a>
                </div>
            </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-posts">No blog posts found. Check back later!</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>