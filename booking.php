<?php

session_start();

// Connect to database
include("config.php");

$query = "
SELECT price
FROM Room_type
WHERE typeOfRoom = 'singleroom'
";

// Get singleroom price
$result = mysqli_query($db, $query);
$row = $result->fetch_row();
$singlePrice = (string)$row[0];

$query = "
SELECT price
FROM Room_type
WHERE typeOfRoom = 'doubleroom'
";

// Get doubleroom price
$result = mysqli_query($db, $query);
$row = $result->fetch_row();
$doublePrice = (string)$row[0];

$query = "
SELECT price
FROM Room_type
WHERE typeOfRoom = 'familyroom'";

// Get familyroom price
$result = mysqli_query($db, $query);
$row = $result->fetch_row();
$familyPrice = (string)$row[0];

$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];

$action = '';
$redirect = true;

if (isset($_POST['submit'])) {

	// Create cookie with all post values
	$_SESSION['booking'] = $_POST;

	$singlerooms = $_POST['singlerooms'];
	$doublerooms = $_POST['doublerooms'];
	$familyrooms = $_POST['familyrooms'];

	$checkin = $_POST['checkin'];
	$checkout = $_POST['checkout'];


	// Get vacant doublerooms
	$query = "SELECT *
						FROM Room_type AS rt
						WHERE rt.typeOfRoom = 'doubleroom'
						AND rt.roomType_id NOT IN
						(SELECT roomType_id FROM Reservation AS r
						WHERE (
						(checkIn BETWEEN '".$checkin."' AND '".$checkout."')
						OR (checkOut BETWEEN '".$checkin."' AND '".$checkout."')
						OR (checkIn = '".$checkin."')
						OR (checkOut = '".$checkout."')
						OR ('".$checkin."' >= checkIn AND '".$checkout."' < checkOut)
						)
						)";

	$vacantDoubleRooms = mysqli_query($db, $query);


	// Get vacant singlerooms
	$query = "SELECT *
						FROM Room_type AS rt
						WHERE rt.typeOfRoom = 'singleroom'
						AND rt.roomType_id NOT IN
						(SELECT roomType_id FROM Reservation AS r
						WHERE (
						(checkIn BETWEEN '".$checkin."' AND '".$checkout."')
						OR (checkOut BETWEEN '".$checkin."' AND '".$checkout."')
						OR (checkIn = '".$checkin."')
						OR (checkOut = '".$checkout."')
						OR ('".$checkin."' >= checkIn AND '".$checkout."' < checkOut)
						)
						)";

	$vacantSingleRooms = mysqli_query($db, $query);


	// Get vacant familyrooms
	$query = "SELECT *
						FROM Room_type AS rt
						WHERE rt.typeOfRoom = 'familyroom'
						AND rt.roomType_id NOT IN
						(SELECT roomType_id FROM Reservation AS r
						WHERE (
						(checkIn BETWEEN '".$checkin."' AND '".$checkout."')
						OR (checkOut BETWEEN '".$checkin."' AND '".$checkout."')
						OR (checkIn = '".$checkin."')
						OR (checkOut = '".$checkout."')
						OR ('".$checkin."' >= checkIn AND '".$checkout."' < checkOut)
						)
						)";

	$vacantFamilyRooms = mysqli_query($db, $query);


	function checkRooms() {
		GLOBAL $vacantDoubleRooms;
		GLOBAL $vacantSingleRooms;
		GLOBAL $vacantFamilyRooms;

		GLOBAL $doublerooms;
		GLOBAL $singlerooms;
		GLOBAL $familyrooms;

		if (mysqli_num_rows($vacantDoubleRooms) < $doublerooms || mysqli_num_rows($vacantSingleRooms) < $singlerooms || mysqli_num_rows($vacantFamilyRooms) < $familyrooms) {
			echo "<label class='error phperror'>Det finns inte tillräckligt många rum lediga på dina datum.</label>";
		} else {
			header('Location: confirmation.php');
		}
	}

	checkRooms();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Bokning</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" charset="utf-8" />
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="styles.css" />
	<script src="http://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
	<script src=" https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.1/jquery.validate.min.js"></script>
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css?family=Lato|Pacifico" rel="stylesheet">
</head>

<body>
	<!-- NAVBAR -->
	<?php
		include('nav.php');
	?>

	<!-- Content -->
	<div class="container main-cont">
		<div class="row">
			<section class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 calender text-center">

				<div class="row">
					<h2>Gästinformation</h2>
				</div>

				<form data-toggle="validator" role="form" action="<?php echo $action; ?>" method="post" autocomplete="off" class="form-inline booking col-sm-12" id="bookingForm">

					<!-- Formulär -->
					<div class="row">
						<div class="form-group">
							<label class="control-label">Incheckning:</label><br />
							<input class="form-control text-center" id="checkin" name="checkin" type="text">
						</div>

						<div class="form-group">
							<label class="control-label">Utcheckning:</label><br />
							<input class="form-control text-center" id="checkout" name="checkout" type="text">
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<label class="control-label">Dubbelrum:</label><br />
							<select id="doublerooms" class="form-control rooms" type="number" name="doublerooms">
								<option value=""> </option>
								<option selected value="1"> 1 </option>
								<option value="2"> 2 </option>
								<option value="3"> 3 </option>
							</select>
						</div>

						<div class="form-group">
							<label class="control-label">Enkelrum:</label><br />
							<select id="singlerooms" class="form-control rooms" type="number" name="singlerooms">
								<option value=""> </option>
								<option value="1"> 1 </option>
								<option value="2"> 2 </option>
							</select>
						</div>

						<div class="form-group">
							<label class="control-label">Familjerum:</label><br />
							<select id="familyrooms" class="form-control rooms" type="number" name="familyrooms">
								<option value=""> </option>
								<option value="1"> 1 </option>
								<option value="2"> 2 </option>
								<option value="3"> 3 </option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<label class="control-label" for="">Förnamn:</label><br />
							<input class="form-control" id="firstname" name="firstname" type="text" placeholder="Ange förnamn">
						</div>

						<div class="form-group">
							<label class="control-label" for="">Efternamn:</label><br />
							<input class="form-control" id="lastname" name="lastname" type="text" placeholder="Ange efternamn">
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<label class="control-label" for="email">Emailadress:</label><br />
							<input class="form-control" id="email" name="email" placeholder="Ange email">
						</div>

						<div class="form-group">
							<label class="control-label" for="phonenumber">Telefonnummer:</label><br />
							<input class="form-control" id="phonenumber" type="tel" placeholder="Ange telefonnummer" name="phonenumber">
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<label for="requests">Önskemål:</label><br>
							<textarea class="form-control" id="requests" name="requests" rows="3" cols="30" placeholder="T.ex barnsäng, extra kudde, etc"></textarea>
						</div>
					</div>

					<div id="errors"></div>

					<div id="priceContainer">
						<p id="price"><p>
					</div>

						<input class="btn btn-lg btn-default" name="submit" type="submit" value="Reservera rum" id="submitBooking">

				</form>

			</section>
		</div>
	</div>

	<!-- FOOTER -->
<?php
	include("footer.php");
?>
<?php
if (isset($_SESSION['admin'])) {
	echo '<script src="/scripts/script_change.js" type="text/javascript"></script>';
}
?>
	<script src="scripts/script_booking.js"></script>
<?php
	echo "
	<script>
		$(document).ready(calcPrice);
		$('.rooms').change(calcPrice);
		$('#checkin').change(calcPrice);
		$('#checkout').change(calcPrice);
		function calcNights() {
			var day = 1000 * 60 * 60 * 24;
			var checkin = Date.parse($('#checkin').val());
			var checkout = Date.parse($('#checkout').val());
			return Math.round((checkout - checkin) / day);
		}
	 	function calcPrice() {
			numNights = calcNights();
	    var singleTot = $singlePrice * $('#singlerooms').val() * numNights;
	    var doubleTot = $doublePrice * $('#doublerooms').val() * numNights;
	    var familyTot = $familyPrice * $('#familyrooms').val() * numNights;
	    var totalPrice = singleTot + doubleTot + familyTot;
			$('#price').html('Ditt pris: ' + totalPrice + ' kr');
		};
	</script>
	";
?>
</body>
</html>
