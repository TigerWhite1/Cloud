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

class UsersTable extends Table
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
	public function validationDefault(Validator $validator)
	{
		$validator
		->notEmpty('username')
		->requirePresence('username')
		->notEmpty('password')
		->requirePresence('password')
		->notEmpty('name')
		->requirePresence('name')
		->notEmpty('lastname')
		->requirePresence('lastname')
		->notEmpty('email')
		->requirePresence('email');
		return $validator;
	}
	public function buildRules(RulesChecker $rules) {
		$rules->add($rules->isUnique(['username']));
		$rules->add($rules->isUnique(['email']));
		return $rules;
	}
	public function validationContact(Validator $validator)
	{
		$validator
		->notEmpty('Demande')
		->requirePresence('Demande')
		->notEmpty('Message')
		->requirePresence('Message');
		return $validator;
	}
}
?>