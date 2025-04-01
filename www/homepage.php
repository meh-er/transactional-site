<?php
session_start();
if(isset($_SESSION['loggedin'])){
    require_once("header-in.php");
	}else{  
    require_once("header.php");
	}


?>

<!doctype html>
<html>
	<head>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
<title>Homepage</title>
<link href="heading-style.css" rel="stylesheet" type="text/css">
	</head>
<body>

	<br>
	<br>
	<div class="subheading">
<h2> Popular Activities!</h2>
		</div>

<?php
$connection = mysqli_connect("localhost", "root", "root", "leisure-centre-booking");

$res = mysqli_query($connection, "SELECT * FROM activity");

echo "<main>";
echo "<button style='font-size:24px' class='button-backwards'><i class='fa fa-arrow-circle-left'></i></button>";
echo "<button style='font-size:24px' class='button-forward'><i class='fa fa-arrow-circle-right'></i></button>";
echo "<div class='card-selector'>";
echo "<ul class='carousel'>";

while ($row=mysqli_fetch_array($res, MYSQLI_ASSOC))
{
	echo "<li class='carousel_slide shownCard'>";
	echo "<form action='booking-index.php' method='POST'>";
	echo "<div id='Cards' class='firstCard'>";
	echo "<img class='card-img-top' src='data:image/png;base64,". base64_encode($row['image']) . "' height='100' width='100' alt='Card image'>";
	echo "<div class='card-body'>";
	echo "<h4>".$row['activity_name']."</h4>";
	echo "<p>".$row['description']."</p>";
	echo "<input type='hidden' name='activity_id' value ='".$row['activity_id']."'>";
	echo "<button type='submit' onClick='document.location=\"booking-index.php\"'>BOOK NOW</button>";
	echo "</div>";
	echo "</div>";
	echo "</form>";
	echo "</li>";

}
echo "</ul>";
echo "</div>";
echo "</main>";
?>

<script>
var carousel = document.querySelector('.carousel');
var carousel_slide = Array.from(carousel.children);
var nextButton = document.querySelector('.button-forward');
var backButton = document.querySelector('.button-backwards');
var cardSize = carousel_slide[0].getBoundingClientRect().width;
carousel_slide.forEach((slide, index) => {
	slide.style.left = cardSize * index + 'px';
});

const moveToCard = (carousel, currentCard, targetCard) => {
  carousel.style.transform = 'translateX(-' + targetCard.style.left + ')';
  currentCard.classList.remove('shownCard');
  targetCard.classList.add('shownCard');
}

nextButton.addEventListener('click', function() {
const currentCard = document.querySelector('.shownCard');
const targetCard = currentCard.nextElementSibling || carousel_slide[0];
  moveToCard(carousel, currentCard, targetCard);
});

backButton.addEventListener('click', function() {
const currentCard = document.querySelector('.shownCard');
const prevCard = currentCard.previousElementSibling|| carousel_slide[carousel_slide.length - 1];
  moveToCard(carousel, currentCard, prevCard);
});

</script>

	</body>
</html>
