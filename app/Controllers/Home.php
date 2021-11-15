<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$data['curl'] = service('curlrequest');
		$people = [];

		if ($_POST) {
			if (! $this->validate([
				'person_name' => 'required',
			])){
				$data['validation'] = $this->validator;
			} else {
				$person_name = $this->request->getPost('person_name'); 
				$people = cache()->get($person_name);
		
				if (!$people = cache($person_name)) {
					$people = json_decode($data['curl']->request("get", "https://swapi.dev/api/people/?search=".$person_name, [
						"headers" => [
							"Accept" => "application/json"
						]
					])->getBody())->results;
					cache()->save($person_name, $people, 604800);
				}
			}
		}

	
		$data['people'] = $people;

		return view('home', $data);
	}
}