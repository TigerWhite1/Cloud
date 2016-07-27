<?php 
namespace App\Model\Table;
use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use Cake\Utility\Security;
use Cake\Auth\DefaultPasswordHasher;

class UploadsTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp',[
			'events' => [
			'Model.beforeSave' => [
			'created' => 'new',
			'updated' => 'existing',
			'mail' => 'new',
			'mail' => 'existing'
			]]]);
	}
	// public function validationDefault(Validator $validator)
	// {
	// 	$validator
	// 	->notEmpty('username');
	// 	debug($validator);
	// 	// $validator
	// 	// ->fileSize();
	// }
}