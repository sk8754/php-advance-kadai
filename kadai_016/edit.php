<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';


// 更新ボタンを押したときの処理　
if (isset($_POST['submit'])) {
    try {
        $pdo = new PDO($dsn, $user, $password);

        $sql_update = 'UPDATE books
                       SET book_code = :book_code,
                       book_name = :book_name,
                       price = :price,
                       stock_quantity = :stock_quantity,
                       genre_code = :genre_code
                       WHERE id = :id
                    ';

        $stmt_update = $pdo->prepare($sql_update);

        $stmt_update->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_INT);
        $stmt_update->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
        $stmt_update->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
        $stmt_update->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
        $stmt_update->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);
        $stmt_update->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

        $stmt_update->execute();

        $count = $stmt_update->rowCount();

        $message = "書籍を{$count}件更新しました。";

        header("Location:read.php?message={$message}");
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}
if (isset($_GET['id'])) {
    try {
        $pdo = new PDO($dsn, $user, $password);

        $sql_select = 'SELECT * FROM books WHERE id = :id';

        $stmt_select = $pdo->prepare($sql_select);

        $stmt_select->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

        $stmt_select->execute();

        $product = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($product === false) {
            exit('idパラメータの値が不正です。');
        }


        $sql_select_genre_codes = 'SELECT genre_code FROM genres';

        $stmt_select_genre_codes = $pdo->query($sql_select_genre_codes);

        $vendor_codes = $stmt_select_genre_codes->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}

try {
    // selectボックスにジャンルコードを反映させるための配列を取得する。
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
    <title>書籍編集</title>
</head>

<body>
    <header>
        <nav>
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="container">
            <h1>書籍編集</h1>

            <div class="edit-container">
                <div class="back-btn">
                    <a href="read.php">↩戻る</a>
                </div>


                <!-- 編集フォーム -->
                <form action="edit.php?id=<?= $_GET['id'] ?>" method="post" class="edit-form">
                    <label for="book_code">書籍コード</label>
                    <input type="number" id="book_code" name="book_code" value="<?= $product['book_code'] ?>" required>

                    <label for="book_name">書籍名</label>
                    <input type="text" id="book_name" name="book_name" value="<?= $product['book_name'] ?>" required>

                    <label for="price">単価</label>
                    <input type="number" id="price" name="price" value="<?= $product['price'] ?>" required>

                    <label for="stock_quantity">在庫数</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" value="<?= $product['stock_quantity'] ?>" required>

                    <label for="genre_code">ジャンルコード</label>
                    <select id="genre_code" name="genre_code" required>
                        <option disabled selected value>選択してください</option>

                        <?php
                        foreach ($genres as $genre) {
                            if ($genre === $product['genre_code']) {
                                echo "<option value='{$genre}' selected>{$genre}</option>";
                            } else {
                                echo "<option value='{$genre}'>{$genre}</option>";
                            }
                        }
                        ?>

                    </select>

                    <button type="submit" name="submit" value="update" class="submit-btn">更新</button>
                </form>
            </div>
        </article>
    </main>
    <footer>
        <p>&copy 書籍管理アプリAll rights reserved.</p>
    </footer>
</body>

</html>