<?php 
namespace App\Controller;
use App\Controller\AppController;
use Cake\View\Helper\PaginatorHelper;
use Cake\Network\Request;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Cake\ORM\Query;

class AdminsController extends AppController
{
	public function role() {
		$query = TableRegistry::get('users');
		$query = $query->find()
		->select(['role', 'actif'])
		->where(["id =" => $this->Auth->user('id')]);
		if ($this->Auth->user('role') !== 1 || $query->toArray()[0]['role'] !== 1 || $query->toArray()[0]['actif'] !== 1) {
			$this->Flash->error(__('Ressource Interdite'));
			return $this->redirect($this->Auth->redirectUrl('/users'));
		}

	}
	public function initialize()
	{	
		parent::initialize();
		$this->loadComponent('Auth', [
			'authenticate' => [
			'Form' => [
			'fields' => ['email' => 'email', 'password' => 'password']
			]
			],
			'storage' => 'Session'
			]);
	}
	public $paginate = [
	'maxLimit' => 5
	];
	public function index()
	{
		$this->role();
		$query = TableRegistry::get('users');
		$query = $query->find()
		->order(['created' => 'DESC'])
		->limit(10);
		$query = $query->toArray();
		$this->set('query', $query);

		$billets = TableRegistry::get('uploads');
		$billets = $billets->find()->where(['rule' => 0])
		->order(['created' => 'DESC'])
		->limit(10);
		$billets = $billets->toArray();
		$this->set('billets', $billets);

		// $comments = TableRegistry::get('comments');
		// $comments = $comments->find()
		// ->order(['id' => 'DESC'])
		// ->limit(10);
		// $comments = $comments->toArray();
		// $this->set('comments', $comments);

	}
	public function change($actif = null, $post) {
		$this->role();
		$table = TableRegistry::get('users');
		if ($this->request->is(['post', 'put'])) {
			$data = [
			'actif' => intval($post)
			];
			$record = $table->get($actif);
			$table->patchEntity($record, $data, [
				'validate' => false
				]);
			if ($table->save($record)) {
				$this->Flash->success(__('Status de l\'user changé.'));
				return $this->redirect(['action' => 'users']);
			}
			$this->Flash->error(__('Impossible de changé les statut de l\'user.'));
		}

	}
	public function rule($actif = null, $post) {
		$this->role();
		$table = TableRegistry::get('users');
		if ($this->request->is(['post', 'put'])) {
			$data = [
			'role' => intval($post)
			];
			$record = $table->get($actif);
			$table->patchEntity($record, $data, [
				'validate' => false
				]);
			if ($table->save($record)) {
				$this->Flash->success(__('Status de l\'user changé.'));
				return $this->redirect(['action' => 'users']);
			}
			$this->Flash->error(__('Impossible de changé les statut de l\'user.'));
		}

	}
	public function users($actif = null, $post = null) {
		$this->role();
		$table = TableRegistry::get('users');
		$this->set('query', $this->paginate($table->find('all')));
		$this->change($actif, $post);

	}
	public function files() {
		$this->role();
		$billets = TableRegistry::get('uploads');
		$billets = $billets->find('all');
		$this->set('billets', $this->paginate($billets->find('all')));
		
	}
	public function comments() {
		$this->role();
		$comments = TableRegistry::get('comments');
		$comments = $comments->find('all');
		$this->set('comments', $this->paginate($comments->find('all')));
		
	}
	public function user($user) {
		$articlesTable = TableRegistry::get('users');
		$user = $articlesTable->find('all')->where(['username' => $user]);
		$uploadsTable = TableRegistry::get('uploads');
		$this->set('user', $this->paginate($article = $uploadsTable->find('all')->where(['id_user' =>$user->toArray()[0]->id])));

	}


}



?>