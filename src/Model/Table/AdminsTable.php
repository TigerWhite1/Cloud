<?php 
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AdminsTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp',[
			'events' => [
			'Model.beforeSave' => [
			'created' => 'new',
			'updated' => 'existing',
			]]]);
	}

	public function validationDefault(Validator $validator)
	{	

		$validator
		->notEmpty('title')
		->requirePresence('title')
		->notEmpty('content')
		->requirePresence('content')
		->notEmpty('tags')
		->requirePresence('tags');
		return $validator;
	}
	public function validationComment(Validator $validator)
	{

		$validator
		->notEmpty('content')
		->requirePresence('content');
		return $validator;
	}


}
?>