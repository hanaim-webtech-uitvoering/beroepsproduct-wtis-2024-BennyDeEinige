<?php
require_once 'functies/db_connectie.php';
session_start();

// Initialiseer het winkelmandje
if (!isset($_SESSION['winkelmandje'])) {
    $_SESSION['winkelmandje'] = [];
}

// Verwerk toevoegingen aan het winkelmandje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pizza'])) {
    $pizzaNaam = $_POST['pizza'];
    $pizzaPrijs = floatval($_POST['prijs']);

    // Controleer of de pizza al in het winkelmandje zit
    $gevonden = false;
    foreach ($_SESSION['winkelmandje'] as &$item) {
        if ($item['naam'] === $pizzaNaam) {
            $item['aantal']++; // Verhoog het aantal
            $gevonden = true;
            break;
        }
    }

    // Als de pizza niet bestaat, voeg het als nieuw item toe
    if (!$gevonden) {
        $_SESSION['winkelmandje'][] = [
            'naam' => $pizzaNaam,
            'prijs' => $pizzaPrijs,
            'aantal' => 1,
        ];
    }
}
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
      <h2>Pizza's</h2>
    </div>
    <!-- <div class="container">
      <div class="search-bar">
        <label for="Vluchtnummer"></label>
        <input required type="text" name="Vluchtnummer" id="Vluchtnummer" placeholder="Type hier uw Vluchtnummer" />
        <input type="submit" value="Zoeken" />
      </div>
    </div> -->
    <div class="card-container">
      <div class="card">
        <img src="fotos/LoadedPepperoni.png" alt="Loaded Pepperoni" class="card-image">
        <h3 class="card-title">Loaded Pepperoni</h3>
        <p class="card-description">
         Een pizza voor echte pepperoni-liefhebbers! Belegd met tomatensaus, mozzarella en extra pepperoni.
        </p>
        <p class="card-price">Vanaf € 12,99</p>
        <form method="POST" class="add-to-cart-form">
          <input type="hidden" name="pizza" value="Loaded Pepperoni">
          <input type="hidden" name="prijs" value="12.99">
          <button type="submit" class="card-button">TOEVOEGEN</button>
        </form>
      </div>


      <div class="card">
          <img src="fotos/DoubleTasty.png" alt="Double Tasty" class="card-image">
          <h3 class="card-title">Double Tasty: 2 smaken, 1 pizza</h3>
          <p class="card-description">
              Combineer je twee favoriete smaken op één pizza voor een unieke smaakervaring.
          </p>
          <p class="card-price">Vanaf € 10,99</p>
          <form method="POST" class="add-to-cart-form">
              <input type="hidden" name="pizza" value="Double Tasty">
              <input type="hidden" name="prijs" value="10.99">
              <button type="submit" class="card-button">TOEVOEGEN</button>
          </form>
      </div>

      <div class="card">
          <img src="fotos/FourTasty.png" alt="Four Tasty" class="card-image">
          <h3 class="card-title">Four Tasty: 4 smaken, 1 pizza</h3>
          <p class="card-description">
              Geniet van vier smaken in één pizza: een feestje voor je smaakpapillen!
          </p>
          <p class="card-price">Vanaf € 19,99</p>
          <form method="POST" class="add-to-cart-form">
              <input type="hidden" name="pizza" value="Four Tasty">
              <input type="hidden" name="prijs" value="19.99">
              <button type="submit" class="card-button">TOEVOEGEN</button>
          </form>
      </div>

      <div class="card">
          <img src="fotos/HotHoneyPepperoni.png" alt="Pepperoni Hot Honey" class="card-image">
          <h3 class="card-title">Pepperoni Hot Honey</h3>
          <p class="card-description">
              Pepperoni, mozzarella en een vleugje pittige honing voor een unieke combinatie van zoet en hartig.
          </p>
          <p class="card-price">Vanaf € 10,99</p>
          <form method="POST" class="add-to-cart-form">
              <input type="hidden" name="pizza" value="Pepperoni Hot Honey">
              <input type="hidden" name="prijs" value="10.99">
              <button type="submit" class="card-button">TOEVOEGEN</button>
          </form>
      </div>

      <div class="card">
          <img src="fotos/UpsideDownMargherita.png" alt="Upside Down Margherita" class="card-image">
          <h3 class="card-title">Upside Down Margherita</h3>
          <p class="card-description">
              Een twist op de klassieke Margherita: de toppings zitten onder de kaas!
          </p>
          <p class="card-price">Vanaf € 9,99</p>
          <form method="POST" class="add-to-cart-form">
              <input type="hidden" name="pizza" value="Upside Down Margherita">
              <input type="hidden" name="prijs" value="9.99">
              <button type="submit" class="card-button">TOEVOEGEN</button>
          </form>
      </div>

      <div class="card">
          <img src="fotos/TruffleSpecial.png" alt="Truffle Special" class="card-image">
          <h3 class="card-title">Truffle Special</h3>
          <p class="card-description">
              Luxe en smaakvol met truffelolie, champignons en Parmezaanse kaas.
          </p>
          <p class="card-price">Vanaf € 14,99</p>
          <form method="POST" class="add-to-cart-form">
              <input type="hidden" name="pizza" value="Truffle Special">
              <input type="hidden" name="prijs" value="14.99">
              <button type="submit" class="card-button">TOEVOEGEN</button>
          </form>
      </div>

      <div class="card">
          <img src="fotos/VeganDelight.png" alt="Vegan Delight" class="card-image">
          <h3 class="card-title">Vegan Delight</h3>
          <p class="card-description">
              Een 100% plantaardige pizza met vegan kaas en verse groenten.
          </p>
          <p class="card-price">Vanaf € 11,99</p>
          <form method="POST" class="add-to-cart-form">
              <input type="hidden" name="pizza" value="Vegan Delight">
              <input type="hidden" name="prijs" value="11.99">
              <button type="submit" class="card-button">TOEVOEGEN</button>
          </form>
      </div>

      <div class="card">
          <img src="fotos/BBQChicken.png" alt="BBQ Chicken" class="card-image">
          <h3 class="card-title">BBQ Chicken</h3>
          <p class="card-description">
              Heerlijke kip in BBQ-saus met mozzarella en rode ui op een knapperige bodem.
          </p>
          <p class="card-price">Vanaf € 13,49</p>
          <form method="POST" class="add-to-cart-form">
              <input type="hidden" name="pizza" value="BBQ Chicken">
              <input type="hidden" name="prijs" value="13.49">
              <button type="submit" class="card-button">TOEVOEGEN</button>
          </form>
      </div>

      <div class="card">
          <img src="fotos/HawaiianClassic.png" alt="Hawaiian Classic" class="card-image">
          <h3 class="card-title">Hawaiian Classic</h3>
          <p class="card-description">
              Tomatensaus, ham, ananas en mozzarella. De pizza voor liefhebbers van zoet en hartig.
          </p>
          <p class="card-price">Vanaf € 10,49</p>
          <form method="POST" class="add-to-cart-form">
              <input type="hidden" name="pizza" value="Hawaiian Classic">
              <input type="hidden" name="prijs" value="10.49">
              <button type="submit" class="card-button">TOEVOEGEN</button>
          </form>
      </div>

      <div class="card">
          <img src="fotos/FourCheese.png" alt="Four Cheese" class="card-image">
          <h3 class="card-title">Four Cheese</h3>
          <p class="card-description">
              Romige vierkazenpizza met mozzarella, Parmezaan, gorgonzola en cheddar.
          </p>
          <p class="card-price">Vanaf € 12,49</p>
          <form method="POST" class="add-to-cart-form">
              <input type="hidden" name="pizza" value="Four Cheese">
              <input type="hidden" name="prijs" value="12.49">
              <button type="submit" class="card-button">TOEVOEGEN</button>
          </form>
      </div>


  </main>
  <?php
    include_once('functies/footer.php');
  ?>
  
</body>

</html>