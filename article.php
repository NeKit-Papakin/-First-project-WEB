<?php
    require_once 'functions.php';
    $postId = $_GET['article'];
    if (empty($postId)) {
        header('Location: index.php');
        exit();
    }
    $db = getDbConnection();
    $postId = SQLite3::escapeString($postId);
    $posts = $db->query("SELECT * from posts WHERE id='{$postId}'");
    if (false === $posts) {
        http_response_code(404);
        exit('Database query error');
    }

    $post = $posts->fetchArray(SQLITE3_ASSOC);
    if (!$post) {
        http_response_code(404);
        exit('Article not found');
    }
    $pageTitle = $post['title'];
    $currentPage = 'article';

    $commentErrors = [];
    $commentAuthor = '';
    $commentRate = 5;
    $commentText = '';

    if (isset($_POST['action']) && 'add-comment' === $_POST['action']) {
        $commentRate = (int)$_POST['rate'];
        $commentAuthor = trim((string)$_POST['author']);
        $commentText = trim((string)$_POST['comment']);
        $created = date('Y-m-d H:i:s');

        if ('' === $commentAuthor) {
            $commentErrors['author'] = 'Author can not be empty';
        } elseif (mb_strlen($commentAuthor) > 50) {
            $commentErrors['author'] = 'Author can not be more than 50 characters';
        }

        if ('' === $commentText) {
            $commentErrors['comment'] = 'Comment can not be empty';
        } elseif (mb_strlen($commentText) < 3) {
            $commentErrors['comment'] = 'Comment can not be less than 3 characters';
        } else if (mb_strlen($commentText) > 200) {
            $commentErrors['comment'] = 'Comment can not be more than 200 characters';
        }

        if ($commentRate > 5) {
            $commentErrors['rate'] = 'Rate can not be grater than 5';
        } elseif ($commentRate < 0) {
            $commentErrors['rate'] = 'Rate can not be lesser than 0';
        }

        if (0 === count($commentErrors)) {
            $result = $db->exec(sprintf(
                "INSERT INTO comments (post_id, author, rate, text, created) VALUES ('%d', '%s', '%s', '%s', '%s')",
                $postId, SQLite3::escapeString($commentAuthor), SQLite3::escapeString($commentRate), SQLite3::escapeString($commentText),
                SQLite3::escapeString($created)
            ));
            if (false === $result) {
                http_response_code(500);
                exit('Database insert error');
            }

            header("Location: article.php?article={$postId}");
            exit();
        }
    }

    $comments = $db->query("SELECT * from comments WHERE post_id='{$postId}'");
    if (false === $comments) {
        http_response_code(500);
        exit('Database query error');
    }

?>

<?php require 'header.php'; ?>


    <main>
        <?php { ?>
            <article class="Posts">
                <header>
                    <?php $created = $post['date']; ?>
                    <time class="time" datetime="<?=$created; ?>">
                        <?=date('d F Y', strtotime($created)); ?></time>
                    <h2 class="Title_Posts"><a href="article.php?article=<?= $post['id']; ?>">
                            <?= htmlspecialchars($post['title']); ?></a></h2>
                    <div class="article_link">Posted by
                        <a href="article.php">Dennis Brooks</a>
                    </div>
                    <img src="<?= $post['img']; ?>" alt="img_1">
                </header>
                <div class="article_content">
                    <?= nl2br(htmlspecialchars($post['content'])); ?>
                </div>
            </article>
        <?php } ?>

        <div>
            <form class="commentForm" action="" method="post">
                <input type="hidden" name="action" value="add-comment">
                <div class="authorName">
                    <label>Your name: <input name="author" type="text" value="<?= htmlspecialchars($commentAuthor);?>"></label>
                    <?php if (isset($commentErrors['author'])) { ?>
                        <div class="commentError"><?= $commentErrors['author']; ?></div>
                    <?php } ?>
                </div>
                <div class="rate">
                    <label>Rate article:
                        <select name="rate" >
                            <?php for ($i=5; $i>0; $i--) { ?>
                                <option value="<?= $i; ?>"
                                        <?php if ($i === $commentRate) { ?>selected<?php } ?>
                                >Rate <?= $i; ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <?php if (isset($commentErrors['rate'])) { ?>
                        <div class="commentError"><?= $commentErrors['rate'];?></div>
                    <?php } ?>
                </div>
                <div class="commentText">
                    <label>Comment:
                        <textarea name="comment" cols="30" rows="5"><?= htmlspecialchars($commentText);?></textarea>
                    </label>
                    <?php if (isset($commentErrors['comment'])) { ?>
                        <div class="commentError"><?= $commentErrors['comment']; ?></div>
                    <?php } ?>
                </div>
                <div class="submitButton"><input type="submit" value="Send"></div>
            </form>
        </div>

        <div class="comments">
            <?php while ($row = $comments->fetchArray(SQLITE3_ASSOC)) { ?>
                <article class="commentContainer">
                    <header>
                        <p >Author: <?= htmlspecialchars($row['author']);?></p>
                        <p >, rate: <?= $row['rate'];?></p>
                    </header>
                    <div class="commentContent"><?= nl2br(htmlspecialchars($row['text']));?></div>
                    <div class="commentTime">
                        Published on:
                        <time datetime="<?= $row['created']; ?>">
                            <?=date('Y-m-d, H:i:s', strtotime($row['created'])); ?>
                        </time>
                    </div>
                </article>
            <?php } ?>
        </div>
    </main>
<?php require 'footer.php'; ?>
