<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Air Traffic Control</title>
	<style type="text/css">
		.status-text{
			display: block;
			margin: 10px 0;
		}
		table.data, table.data tr, table.data td, table.data th{
			border: 1px solid black;
			border-collapse: collapse;
		}
		table.data td, table.data th {
			padding: 5px 10px;
			text-transform: capitalize;
		}
		strong > span{
			font-style: italic;
		}
		.col {
		    padding: 50px;
		}
		.row {
		    display: flex;
		    justify-content: center;
		}
		.group select {
		    display: block;
		    width: 100%;
		    height: 30px;
		    text-transform: capitalize;
		    margin: 10px 0;
		}
		.group label {
			font-weight: bold;
		}
	</style>
</head>
<body>

	<div class="row">

		<div class="col">
			<h2>Air Traffic Control System</h2>
			<p>
				<strong class="status-text">Current status: <span class="current-status"></span></strong>
				
				<button disabled class="btn-boot"></button>
			</p>

			<table class="data">
					<thead>
						<tr>
							<th>id</th>
							<th>priority</th>
							<th>size</th>
							<th>type</th>
							<th></th>
						</tr>
					</thead>
					<tbody></tbody>
			</table>
		</div>

		<div class="col">
			<h3>Add Aircraft to queue</h3>
			<div class="group">
				<label for="type">Type</label>
				<select name="type" id="type">
					<option value="passenger">passenger</option>
					<option value="cargo">cargo</option>
					<option value="vip">vip</option>
					<option value="emergency">emergency</option>
				</select>
			</div>

			<div class="group">
				<label for="size">Size</label>
				<select name="size" id="size">
					<option value="large">large</option>
					<option value="small">small</option>
				</select>
			</div>

			<div class="group">
				<button disabled class="btn-queue">Queue</button>
			</div>
			
		</div>	
	</div>	

	<script type="text/javascript" src="./js/atc.js"></script>
</body>
</html>