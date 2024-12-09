<?php
require_once 'functies/db_connectie.php';
session_start();

// Haal producten op uit de database
function haalProductenOp() {
    $conn = maakVerbinding();
    $sql = "SELECT * FROM product"; // Haal alle producten op uit de tabel 'product'
    try {
        $stmt = $conn->query($sql);
        $producten = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $producten;
    } catch (PDOException $e) {
        echo "Fout bij ophalen producten: " . $e->getMessage();
        exit;
    }
}

$producten = haalProductenOp(); // Haal de producten op
?>

<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EindOpdracht Pizzeria Sole Machina</title>
  <link rel="stylesheet" href="css/bb.css" />
  <link rel="stylesheet" href="css/normalize.css" />
  <link rel="stylesheet" href="css/les03_grid.css" />
  <link rel="stylesheet" href="css/tijdelijke.css" />
  <link rel="stylesheet" href="css/nw.css" />
</head>

<body>
  <?php
    include_once('functies/header.php');
    include_once('functies/navbar.php');
  ?>

  <main>
    <div class="container">
      <h2>Producten</h2>
    </div>

    <div class="card-container">
      <?php foreach ($producten as $product): ?>
        <div class="card">
          <img src="fotos/BBQChicken.png" alt="Product" class="card-image"> <!-- Dit wordt gebruikt als standaardafbeelding -->
          <h3 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h3>
          <p class="card-price">€ <?php echo number_format($product['price'], 2, ',', '.'); ?></p>
          <form method="POST" action="winkelmand.php">
            <input type="hidden" name="product" value="<?php echo htmlspecialchars($product['name']); ?>">
            <input type="hidden" name="prijs" value="<?php echo htmlspecialchars($product['price']); ?>">
            <input type="hidden" name="aantal" value="1">
            <button type="submit" class="card-button">TOEVOEGEN</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <?php
    include_once('functies/footer.php');
  ?>
</body>

</html>
