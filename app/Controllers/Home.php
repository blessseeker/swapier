<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{

		// load curl service
		$data['curl'] = service('curlrequest');

		// define people variable
		$people = [];

		// if a name posted
		if ($_POST) {
			// Validate input form
			if (! $this->validate([
				'person_name' => 'required',
			])){
				$data['validation'] = $this->validator;
			} else {
				// try to retrieve cache using posted name
				$person_name = $this->request->getPost('person_name'); 
				$people = cache()->get($person_name);
		
				// create new cache file if cache not found
				if (!$people = cache($person_name)) {
					$people = json_decode($data['curl']->request("get", "https://swapi.dev/api/people/?search=".$person_name, [
						"headers" => [
							"Accept" => "application/json"
						]
					])->getBody())->results;
					cache()->save($person_name, $people, 604800);
				}
				// handle not found error
				if (count($people) < 1) {
					$data['error'] = 'Person Not Found';
				}
			}
		}

	
		// send retrieved data to view
		$data['people'] = $people;

		return view('home', $data);
	}
}