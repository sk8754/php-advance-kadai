<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);

    // 並び替えを押したときの処理
    if (isset($_GET['order'])) {
        $order = $_GET['order'];
    } else {
        $order = NULL;
    }

    // 検索ボックスで絞込検索をした時の処理
    if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
    } else {
        $keyword = NULL;
    }

    if ($order === 'desc') {
        $sql_order = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at DESC ';
    } else {
        $sql_order = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at ASC ';
    }

    $stmt_order = $pdo->prepare($sql_order);

    $patical_match = "%{$keyword}%";

    $stmt_order->bindValue(':keyword', $patical_match, PDO::PARAM_STR);

    $stmt_order->execute();

    $books = $stmt_order->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <title>書籍一覧</title>
</head>

<body>
    <header>
        <nav>
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="container">
            <h1>書籍一覧</h1>

            <?php
            if (isset($_GET['message'])) {
                echo "<p class='success'>{$_GET['message']}</p>";
            }
            ?>

            <div class="ui">
                <!-- 並び替えボタンと検索ボックス -->
                <div class="left-ui">
                    <a href="read.php?order=desc&keyword=<?= $keyword ?>"><img src="images/desc.png" class="desc-icon"></a>
                    <a href="read.php?order=asc&keyword=<?= $keyword ?>"><img src="images/asc.png" class="asc-icon"></a>
                    <form action="read.php" method="get" class="serch-form">
                        <input type="hidden" name="order" value="<?= $order ?>">
                        <input type="text" placeholder="書籍名で検索" id="serch" name="keyword" value="<?= $keyword ?>">
                    </form>

                </div>
                <!-- 書籍登録ボタン -->
                <div class="right-ui">
                    <a href="create.php">書籍登録</a>
                </div>
            </div>

            <table class="table">
                <tr>
                    <th>書籍コード</th>
                    <th>書籍名</th>
                    <th>単価</th>
                    <th>在庫数</th>
                    <th>ジャンルコード</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>

                <?php
                foreach ($books as $book) {
                    echo "<tr>
                    <td>{$book['book_code']}</td>
                    <td>{$book['book_name']}</td>
                    <td>{$book['price']}</td>
                    <td>{$book['stock_quantity']}</td>
                    <td>{$book['genre_code']}</td>
                    <td><a href='edit.php?id={$book['id']}'><img src='images/edit.png' class='edit-icon'></a></td>
                    <td><a href='delete.php?id={$book['id']}'><img src='images/delete.png' class='delete-icon'></a></td>
                    </tr>";
                }
                ?>
            </table>
        </article>
    </main>
    <footer class="read-footer">
        <p>&copy 書籍管理アプリAll rights reserved.</p>
    </footer>
</body>

</html>