<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>SWAPIer Enemy Finder</title>
  </head>
  <body>
  <nav class="navbar navbar-light bg-light">
	<div class="container-fluid">
		<form class="d-flex" method="post">
		<input class="form-control me-2" name="person_name" type="search" placeholder="Type a Person Name" aria-label="Search" required>
		<button class="btn btn-outline-success" type="submit">Search</button>
		</form>
	</div>
	</nav>
	<div class="container"> 
		<?php if (isset($validation)) { ?>
			<div class="p-3 mb-2 bg-danger text-white"><?= $validation->listErrors(); ?></div>
		<?php } 
		if (isset($error)) { ?>
			<div class="p-3 mb-2 bg-danger text-white"><?= $error; ?></div>
		<?php } 
			foreach ($people as $person) {
				// retrieve person data from cache
				$person_cache_name = str_replace(array( '{', '}',
				'(' , ')', '/', '\'', '@', ':' ), '', $person->url);
				$personal_data = cache()->get($person_cache_name);

				// cache person data if it not found
				if (!$personal_data = cache($person_cache_name)) {
					$personal_data = json_decode($curl->request("get", $person->url, [
						"headers" => [
							"Accept" => "application/json"
						]
					])->getBody());
					cache()->save($person_cache_name, $personal_data, 604800);
				}

				?>
			<div class="card border-success mb-3">
				<div class="card-header bg-transparent border-success">Enemy Information</div>
				<div class="card-body text-success">
					<h5 class="card-title">Name : <?= $personal_data->name; ?></h5>
					<p class="card-text">
					<ul class="list-group list-group-flush">
						<li class="list-group-item">Gender : <?= $personal_data->gender; ?></li>
						<li class="list-group-item">Starships :
							<ol>
								<?php foreach ($personal_data->starships as $starship_url) {
									// retrieve starship data from cache
									$starship_cache_name = str_replace(array( '{', '}',
									'(' , ')', '/', '\'', '@', ':' ), '', $starship_url);
									$starship_data = cache()->get($starship_cache_name);

									// cache starship data to file if it's not found
									if (!$starship_data = cache($starship_cache_name)) {
										$starship_data = json_decode($curl->request("get", $starship_url, [
											"headers" => [
												"Accept" => "application/json"
											]
										])->getBody());
										cache()->save($starship_cache_name, $starship_data, 604800);
									}
									?>
									<li>
										<ul>
											<li>Model : <?= $starship_data->model; ?></li>
											<li>Class : <?= $starship_data->starship_class; ?></li>
											<li>Hyperdrive Rating : <?= $starship_data->hyperdrive_rating; ?></li>
											<li>Cost in Credits : <?= $starship_data->cost_in_credits; ?></li>
											<li>Manufacturer : <?= $starship_data->manufacturer; ?></li>
										</ul>
									</li>
								<?php } ?>
							</ol>
						</li>
						<li class="list-group-item">Vehicles :
							<ol>
								<?php foreach ($personal_data->vehicles as $vehicle_url) {
									// retrieve vehicle data from file
									$vehicle_cache_name = str_replace(array( '{', '}',
									'(' , ')', '/', '\'', '@', ':' ), '', $starship_url);
									$vehicle_data = cache()->get($vehicle_cache_name);

									// cache vehicle data to file if it's not found
									if (!$vehicle_data = cache($vehicle_cache_name)) {
										$vehicle_data = json_decode($curl->request("get", $vehicle_url, [
											"headers" => [
												"Accept" => "application/json"
											]
										])->getBody());
										cache()->save($vehicle_cache_name, $vehicle_data, 604800);
									}?>
								<li>
									<ul>
										<li>Name : <?= $vehicle_data->name; ?></li>
										<li>Model : <?= $vehicle_data->model; ?></li>
										<li>Cost in Credits : <?= $vehicle_data->cost_in_credits; ?></li>
									</ul>
								</li>
								<?php } ?>
							</ol>
						</li>
						<li class="list-group-item">Homeworld :
									<?php 

									// retrieve homeworld data from cache
									$homeworld_cache_name = str_replace(array( '{', '}',
									'(' , ')', '/', '\'', '@', ':' ), '', $person->homeworld);
									$homeworld_data = cache()->get($homeworld_cache_name);

									// cache homeworld data if it;s not cached yet
									if (!$homeworld_data = cache($homeworld_cache_name)) {
										$homeworld_data = json_decode($curl->request("get", $person->homeworld, [
											"headers" => [
												"Accept" => "application/json"
											]
										])->getBody());
										cache()->save($homeworld_cache_name, $homeworld_data, 604800);
									}?>
									<ul>
										<li>Name : <?= $homeworld_data->name; ?></li>
										<li>Population : <?= $homeworld_data->population; ?></li>
										<li>Climate : <?= $homeworld_data->climate; ?></li>
									</ul>
						</li>
					</ul>
					</p>
				</div>
			</div>
			<?php } ?>
	</div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
  </body>
</html>
