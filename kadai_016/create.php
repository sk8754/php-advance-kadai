<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

// submitボタンが押された時の処理
if (isset($_POST['submit'])) {
    try {
        $pdo = new PDO($dsn, $user, $password);

        $sql_insert = 'INSERT INTO books (book_code, book_name, price, stock_quantity, genre_code)
                       VALUES (:book_code, :book_name, :price, :stock_quantity, :genre_code)
                    ';

        $stmt_insert = $pdo->prepare($sql_insert);

        $stmt_insert->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
        $stmt_insert->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);

        $stmt_insert->execute();

        $count = $stmt_insert->rowCount();

        $message = "書籍を{$count}件追加しました。";

        header("Location: read.php?message={$message}");
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}

// selectボックスにジャンルコードを反映させるための配列を取得する。
try {
    $pdo = new PDO($dsn, $user, $password);

    $sql_get_genrecode = 'SELECT genre_code FROM genres';

    $stmt_get_genrecode = $pdo->query($sql_get_genrecode);

    $genres = $stmt_get_genrecode->fetchAll(PDO::FETCH_COLUMN);
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
    <title>書籍登録</title>
</head>

<body>
    <header>
        <nav>
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="container">
            <h1>書籍登録</h1>

            <div class="edit-container">
                <div class="back-btn">
                    <a href="read.php">↩戻る</a>
                </div>


                <!-- 登録フォーム -->
                <form action="create.php" method="post" class="edit-form">
                    <label for="book_code">書籍コード</label>
                    <input type="number" id="book_code" name="book_code" required>

                    <label for="book_name">書籍名</label>
                    <input type="text" id="book_name" name="book_name" required>

                    <label for="price">単価</label>
                    <input type="number" id="price" name="price" required>

                    <label for="stock_quantity">在庫数</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" required>

                    <label for="genre_code">ジャンルコード</label>
                    <select id="genre_code" name="genre_code" required>
                        <option disabled selected value>選択してください</option>

                        <?php
                        foreach ($genres as $genre) {
                            echo "<option value='{$genre}'>{$genre}</option>";
                        }
                        ?>

                    </select>

                    <button type="submit" name="submit" value="create" class="submit-btn">登録</button>
                </form>
            </div>
        </article>
    </main>
    <footer>
        <p>&copy 書籍管理アプリAll rights reserved.</p>
    </footer>
</body>

</html>