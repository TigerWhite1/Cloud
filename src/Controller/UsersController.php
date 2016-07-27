<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;
use Cake\Mailer\Email;
use Cake\I18n\Time;

class UsersController extends AppController
{

	public function check() {
		$query = TableRegistry::get('users');
		$query = $query->find()
		->where(['id =' => $this->Auth->user('id')]);
		$query = $query->toArray();
		$this->set('query', $query);
		if (count($query) == 0 || $query[0]['actif'] == 0) {
			$this->Flash->error(__('Votre compte à été supprimé ou bloquer'));
			return $this->redirect($this->Auth->redirectUrl('/users/logout'));
		}

	}
	public function beforeFilter(\Cake\Event\Event $event)
	{
		$this->Auth->allow('formulaire');
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
	public function login()
	{
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				return $this->redirect($this->Auth->redirectUrl('/users'));
			} else {
				$this->Flash->error(__('Nom d\'utilisateur ou mot de passe incorrect'), [
					'key' => 'auth'
					]);
			}
		}
	}

	public function logout()
	{	
		$this->Flash->success('Vous êtes maintenant déconnecté.');
		$this->redirect($this->Auth->logout());
	}
	public function index()
	{	

		$this->check();
		$chekTable = TableRegistry::get('uploads');
		$chek = $chekTable->find('all')->where(['id_user' => $this->Auth->user('id'), 'folder' => $this->Auth->user('username')]);
		if (empty($chek->toArray())) {
			$articlesTable = TableRegistry::get('uploads');
			$article = $articlesTable->newEntity();
			$article->id_user = $this->Auth->user('id');
			$article->folder = $this->Auth->user('username');
			$article->rule = 0; 
			$articlesTable->save($article);
		}
		$this->set('role', $this->Auth->user('role'));

	}
	public function mail($post) {

		$admin = $this->Users->find()
		->select(['email'])
		->where(['role =' => 1]);
		$tbl = $admin->toArray();
		$tblmail = array();
		for ($i=0; $i < count($tbl) ; $i++) { 
			$tblmail[$tbl[$i]['email']] = $tbl[$i]['email'];
		}
		$id = $this->Auth->user('id');
		$mail = $this->Auth->user('email');
		$username = $this->Auth->user('username');
		$email = new Email();
		$email->template('admin')
		->viewVars(['id' => $id, 'mail' => $mail, 'username' => $username, 'demande' => $post['Demande'],'message' => $post['Message']])
		->emailFormat('html')
		->to($tblmail)
		->from('webacademie.php@gmail.com')
		->send();
	}
	public function time() {

		$query = $this->Users->find('all');
		$query->select(['mail']);
		$query->where(["id = " => $this->Auth->user('id')]);
		$dateFrom = new \DateTime('now');
		$dateNow = new \DateTime($query->toArray()[0]['mail']);
		$interval = $dateNow->diff($dateFrom);
		if ($interval->format('%y') >= '0' && $interval->format('%m') >= '0' && $interval->format('%h') >= '0' && $interval->format('%i') >= '30' || is_null($query->toArray()[0]['mail'])) {
			debug('toto');
			return true;
		}
		return false;

	}
	public function contact() {
		$this->check();
		$mail = $this->Users->get($this->Auth->user('id'));
		if ($this->request->is('post')) {
			$this->Users->patchEntity($mail, $this->request->data, [
				'validate' => 'Contact',
				]);
			if ($this->time() && $this->Users->save($mail)) {
				$this->mail($this->request->data);
				$this->Flash->success(__('Votre message a été envoyé.'));
				return $this->redirect(['action' => ""]);
			}
			$this->Flash->error(__('Impossible d\'envoyer votre message.'));
		}
	}
	public function formulaire($parrainage = null)
	{
		$this->set('parrainage', $parrainage);
		if ($this->request->is('post')) {
			if ($this->add($this->request->data)) {
				$this->Flash->success('Vous êtes maintenant inscrit.');
				return $this->redirect($this->Auth->redirectUrl('/login'));
			}
			return $this->Flash->error(__('Inscription impossible merci de verifier les champs'));
		}

	}
	public function add($data) {
		if ($data['username'] == 'files' || $data['username'] == 'users') {

			return false;
		} else {

			if (!empty($data['parrainage'])) {
				$tbl = explode('_', $data['parrainage']);
				if (count($tbl) == 2) {
					$resutl = $this->Users->find('all')->where(['username' => $tbl[0], 'birthdate' => $tbl[1]]);
					if (!empty($resutl->toArray()[0])) {
						$user = $this->Users->get($resutl->toArray()[0]->id); 
						$user->limit_file = $resutl->toArray()[0]->limit_file+7864320;
						$user->limit_upload = $resutl->toArray()[0]->limit_upload+26214400; 
						$this->Users->save($user);
					} else {
						return false;

					}

				} else {
					return false;
					
				}

			}
			$birthdate = $data['birthdate']['year']."-".$data['birthdate']['month']."-".$data['birthdate']['day'];
			$articles = TableRegistry::get('users');
			$article = $articles->newEntity(array_merge($this->request->data, ['birthdate' => $birthdate]), [
				'associated' => ['Comments'], 
				'validate' => 'Default',
				'_setPassword' => $data['password']
				]);
			if (!empty($resutl->toArray()[0])) {
			// debug('ttoto');
				$article->limit_file = 10485760+7864320;
				$article->limit_upload = 52428800+26214400; 
			}
			return $articles->save($article);

		}

	}

	public function parrainage() {

		if ($this->request->is('post')) {
			// debug(!empty($this->request->data['mail1']));
			if (!empty($this->request->data['mail1'])) {
				
				$birthdate = $this->Users->find('all')->select(['birthdate'])->where(['id' => $this->Auth->user('id')]);
				$link = $this->Auth->user('username').'_'.$birthdate->toArray()[0]->birthdate;
				$tbl_mail = array();
				for ($i = 1; $i < count($this->request->data)+1; $i++) {
					if (!empty($this->request->data['mail'.$i])) {
						$tbl_mail[$this->request->data['mail'.$i]] = $this->request->data['mail'.$i];
						
					}
				}

				$username = $this->Auth->user('username');
				$email = new Email();
				$email->template('parrainage')
				->viewVars(['username' => $username, 'link' => $link])
				->emailFormat('html')
				->to($tbl_mail)
				->from('webacademie.php@gmail.com')
				->send();
				$this->Flash->success('Votre mail de parrainage à bien été envoyer.');
				return $this->redirect($this->Auth->redirectUrl('/users/parrainage'));
			}
			return $this->Flash->error(__('Impossible d\'envoyer votre mail de parrainage.'	));
		}
	}

}


?>