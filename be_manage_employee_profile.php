<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$employeeDetails = [];
$result_all_employees = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$emp_id = $_POST['employee_id'];

	$sql_employee_details = "SELECT Emp_ID, Full_Name, Contact_No, Email, Username, Password, Employee_Category FROM employees WHERE Emp_ID = ?";
	$stmt = $conn->prepare($sql_employee_details);
	$stmt->bind_param("i", $emp_id);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$employeeDetails = $result->fetch_assoc();
	} else {
		echo "<script>alert('No employee found with the provided Employee ID'); window.location.href='be_manage_employee_profile.php';</script>";
		$stmt->close();
	}
} else {

	$sql_all_employees = "SELECT Emp_ID, Full_Name, Contact_No, Email, Username, Password, Employee_Category FROM employees";
	$result_all_employees = $conn->query($sql_all_employees);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>Admin Panel - Locks&Curls</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
	<link rel="stylesheet" href="styles.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
		rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
	<style>
		.container {
			width: 100%;
		}

		.form-container,
		.table-container {
			max-width: 1200px;
			margin: 0 auto;
		}

		.form-container {
			padding: 30px 50px 45px 50px;
			margin-top: 20px;
			border-radius: 20px;
			background-color: #f0d4fe;
		}

		.form-group {
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.table-container {
			overflow-x: auto;
		}

		.table {
			width: 100%;
			border-collapse: collapse;
		}

		.table th,
		.table td {
			white-space: nowrap;
			padding: 15px;
			text-align: center;
			border: 1px solid #000000;
		}

		.table th {
			background-color: #f9f3f3;
		}

		.table td:last-child,
		.table th:last-child {
			border-right: 1px solid #000000;
		}
	</style>
</head>

<!--Manage Client Profile Sub Component Page-->

<body class="home">
	<div class="profile-bar">
		<div class="sec3">
			<h5 id="back-end-title">
				<center>RESERVATION MANAGEMENT SYSTEM - SALON LOCKS & CURLS</center>
			</h5>
		</div>
		<div class="sec4">
			<a class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown">Admin</a>
			<ul class="dropdown-menu settings-dropdown-menu">
				<li class="dropdown-tab">
					<a class="dropdown-items dropdown-link settings-dropdown-items" href="index.php">Logout</a>
				</li>
				<br>
				<li class="dropdown-tab">
					<a class="dropdown-items dropdown-link settings-dropdown-items"
						href="be_employee_change_password.php" id="change-password-link">Change
						Password</a>
				</li>
			</ul>
		</div>
	</div>

	<!--Backend Admin Panel Menu Guide-->
	<div class="main-container">
		<div class="left-Panel">
			<nav class="navbar navbar-light justify-content-start vertical-menu">
				<a class="nav-item nav-link backend-nav" href="be_dashboard.php">Dashboard</a>

				<!--Appointment Management Tab-->
				<div class="dropdown">

					<!--Main Component Link-->
					<a class="nav-item nav-link backend-nav btn dropdown-toggle" href="" role="button"
						id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
						Appointment Management
					</a>

					<!--Sub Components Link-->
					<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<li>
							<a class="dropdown-item" href="be_appointment.php">Manage Appointments</a>
						</li>
						<li>
							<a class="dropdown-item" href="be_accepted_app.php">Accepted Appointments</a>
						</li>
						<li>
							<a class="dropdown-item" href="be_rejected_app.php">Rejected Appointments</a>
						</li>
					</ul>
				</div>
				<br />
				<br />

				<!--Invoice Management Tab-->
				<a class="nav-item nav-link backend-nav" href="be_system_invoice.php">Invoice Management</a>

				<!--Inquiry Management Tab-->
				<div class="dropdown">

					<!--Main Component Link-->
					<a class="nav-item nav-link backend-nav btn dropdown-toggle" href="be_inquiry_mgt.php" role="button"
						id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
						Inquiry Management
					</a>

					<!--Sub Components Link-->
					<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<li>
							<a class="dropdown-item" href="be_inquiry_mgt.php">Manage Inquiries</a>
						</li>
						<li>
							<a class="dropdown-item" href="be_inquiry_view.php">View Inquiries</a>
						</li>
					</ul>
				</div>
				<br />
				<br />

				<!--Content Management Tab-->
				<div class="dropdown">
					<!--Main Component Link-->
					<a class="nav-item nav-link backend-nav btn dropdown-toggle" href="be_manage_services.php"
						role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
						Content Management
					</a>

					<!--Sub Components Link-->
					<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<li>
							<a class="dropdown-item" href="be_manage_services.php">Manage Services</a>
						</li>
						<li>
							<a class="dropdown-item" href="be_manage_services_view.php">View Services</a>
						</li>
						<li>
							<a class="dropdown-item" href="be_content_mgt.php">Manage Page Content</a>
						</li>
					</ul>
				</div>

				<div class="dropdown">
					<!--Main Component Link-->
					<a class="nav-item nav-link backend-nav btn dropdown-toggle active"
						href="be_manage_system_users.php" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
						aria-expanded="false">
						Profile Management
					</a>

					<!--Sub Components Link-->
					<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<li>
							<a class="dropdown-item" href="be_manage_system_users.php">Manage System Users</a>
						</li>
						<li>
							<a class="dropdown-item active" href="be_manage_employee_profile.php">Manage Employee
								Profile</a>
						</li>
						<li>
							<a class="dropdown-item" href="be_manage_client_profile.php">Manage Client Profile</a>
						</li>
					</ul>
				</div>
			</nav>
		</div>

		<!--Right panel-->
		<div class="right-Panel">
			<br>
			<center>
				<h2>Manage Employee Profile</h2>
			</center>
			<br>
			<div class="form-container display-font">
				<form method="post" action="">
					<div class="form-group">
						<label id="employee_id_label" for="employee_id" style="width: 16%">Employee ID:</label>
						<input type="text" class="form-control" placeholder="xxxxxxxx" name="employee_id"
							id="employee_id" required="true" style="height: 50px; width: 64%; flex-grow: 1" />
						<button type="submit" class="btn btn-primary btn-lg buttonBE">
							Search
						</button>
					</div>
				</form>
				<br />
				<div class="table-container">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="text-center">Employee ID</th>
								<th class="text-center">Full Name</th>
								<th class="text-center">Contact Number</th>
								<th class="text-center">Email</th>
								<th class="text-center">Username</th>
								<th class="text-center">Employee Category</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if ($result_all_employees) {
								while ($row = $result_all_employees->fetch_assoc()) {
									echo "<tr>";
									echo "<td class='text-center'>" . htmlspecialchars($row['Emp_ID']) . "</td>";
									echo "<td class='text-center'>" . htmlspecialchars($row['Full_Name']) . "</td>";
									echo "<td class='text-center'>" . htmlspecialchars($row['Contact_No']) . "</td>";
									echo "<td class='text-center'>" . htmlspecialchars($row['Email']) . "</td>";
									echo "<td class='text-center'>" . htmlspecialchars($row['Username']) . "</td>";
									echo "<td class='text-center'>" . htmlspecialchars($row['Employee_Category']) . "</td>";
									echo "</tr>";
								}
							} elseif (!empty($employeeDetails)) {
								// Display employee details if searched
								echo "<tr>";
								echo "<td class='text-center'>" . htmlspecialchars($employeeDetails['Emp_ID']) . "</td>";
								echo "<td class='text-center'>" . htmlspecialchars($employeeDetails['Full_Name']) . "</td>";
								echo "<td class='text-center'>" . htmlspecialchars($employeeDetails['Contact_No']) . "</td>";
								echo "<td class='text-center'>" . htmlspecialchars($employeeDetails['Email']) . "</td>";
								echo "<td class='text-center'>" . htmlspecialchars($employeeDetails['Username']) . "</td>";
								echo "<td class='text-center'>" . htmlspecialchars($employeeDetails['Employee_Category']) . "</td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
</body>

</html>