<?php
    require_once 'functions.php';
    $db = getDbConnection();
//    $query = 'SELECT * from posts';
    $query = 'SELECT posts.id, posts.title, posts.content, posts.img, posts.date,
       COUNT(comments.id) as number, AVG(comments.rate) as rate, comments.author as author from posts
           LEFT JOIN comments ON posts.id = comments.post_id GROUP BY posts.id';
    $posts = $db->query($query);
    if (false === $posts) {
        http_response_code(500);
        exit('Database query error!');
    }
    $pageTitle = 'Home';
    $currentPage = 'home';
    ?>

<?php require 'header.php'; ?>

    <main>
        <?php while ($tableRow = $posts->fetchArray(SQLITE3_ASSOC)) { ?>
            <article class="Posts">
                <header>
                    <?php $created = $tableRow['date']; ?>
                    <time class="time" datetime="<?=$created; ?>">
                        <?=date('d F Y', strtotime($created)); ?></time>
                    <h2 class="Title_Posts"><a href="article.php?article=<?= $tableRow['id']; ?>">
                            <?= htmlspecialchars($tableRow['title']); ?></a></h2>
                    <div class="article_link">Posted by
                        <a href="article.php">Dennis Brooks</a>
                    </div>
                    <img src="<?= $tableRow['img']; ?>" alt="img_1">
                </header>
                <div class="article_content">
                    <?= nl2br(htmlspecialchars($tableRow['content'])); ?>
                </div>
                <footer>
                    <div class="Continue_one">
                        <a href="article.php">Continue reading →</a>
                    </div>
                    <div class="articleSummary">
                        <p>Comments: <?= (int)$tableRow['number']; ?>, rate: <?=  (float)round($tableRow['rate'], 1);?></p>
                    </div>
                </footer>
            </article>
        <?php } ?>

        <article class="Page">
            <div class="div5">Page 1 of 2</div>
            <div class="Older_posts">
                <a href="#">Older posts ></a>
            </div>
        </article>

    </main>
</div>
<?php require 'footer.php'; ?>
