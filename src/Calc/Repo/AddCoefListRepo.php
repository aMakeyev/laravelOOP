<?php namespace Calc\Repo;

use Calc\Model\AdditionalCoefficient;

class AddCoefListRepo extends Repo {
	protected $modelClassName = 'Calc\Model\AdditionalCoefficient';

	public function getList() {
		$categories = [];

		$_categories = AdditionalCoefficient::all();

		foreach($_categories as $c) {
			$categories[$c->id] = $c->title.' ('.$c->value.')';
		}

		return $categories;
	}
}
