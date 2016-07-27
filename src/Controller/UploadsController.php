<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;
use Cake\Mailer\Email;
use Cake\I18n\Time;
use Cake\View\Helper;
use Cake\Core\App;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\I18n\Number;

class UploadsController extends AppController
{
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
	public function uploads() {

		// if ($this->request->referer() == "/" || $this->Auth->user('role') === 3) {
		//  $this->Flash->error(__('Ressource Interdite'));
		//  return $this->redirect(['action' => 'index']);
		// }
		$finfo = finfo_open(FILEINFO_MIME_TYPE);

		$query = $this->Uploads->find('all');
		$query = $query->select(['folder', 'rule'])->where(['id_user' =>  $this->Auth->user('id')]);
		$tbl = array();
		for ($i=0; $i < count($query->toArray()); $i++) { 
			if ($tbl != $query->toArray()[$i]->folder && $query->toArray()[$i]->rule == 0) {
				$tbl[$query->toArray()[$i]->folder] = $query->toArray()[$i]->folder;
			}
		}
		$this->set('tbl', $tbl);
		if ($this->request->is('post')) {
			$path = explode('/', $this->request->data['select']);
			unset($path[0]);
			$string = implode('/', $path);

			$query = $this->Uploads->find('all');
			$query = $query->select(['size' => $query->func()->sum('size')])->where(['id_user' =>  $this->Auth->user('id')])
			->group('size');
			$user = TableRegistry::get('users');
			$user = $user->find('all');
			$user = $user->select(['limit_upload', 'limit_file'])->where(['id' =>  $this->Auth->user('id')]);


			$size_file = $this->request->data['file']['size'];

			if (!empty($query->toArray()[0]->total_upload))
				$verif = $query->toArray()[0]->total_upload + $this->request->data['file']['size'];
			else
				$verif = 0;
			$limit = $user->toArray()[0]->limit_upload;
			$limit_file = $user->toArray()[0]->limit_file;



			$articlesTable = TableRegistry::get('uploads');
			$article = $articlesTable->newEntity();
			$article->id_user = $this->Auth->user('id');
			$article->folder = $this->request->data['select'];
			$article->file = $_FILES['file']['name'];
			$article->rule = 0;
			$article->size = $this->request->data['file']['size'];
			$article->mime = finfo_file($finfo, $_FILES['file']['tmp_name']);

			finfo_close($finfo);

			$result = file_exists(WWW_ROOT.'cloud/'.$this->Auth->user('id'));
			$file_ex = file_exists(WWW_ROOT.'cloud/'.$this->Auth->user('id').'/'.$string.'/'.$_FILES['file']['name']);
			$secu2 = preg_match('/^[A-Za-z][A-Za-z0-9._-]*$/', $_FILES['file']['name']);


			// debug($file_ex);
			if ($verif <= $limit && $size_file <= $limit_file && $file_ex == false && $secu2 == 1) {
				if ($articlesTable->save($article)) {
					if (!$result) {
						$folder = new Folder(WWW_ROOT.'cloud'. DS, true, 0777);
						$folder->create($this->Auth->user('id'));
						if(move_uploaded_file($_FILES['file']['tmp_name'], WWW_ROOT.'cloud/'.$this->Auth->user('id').'/'.$_FILES['file']['name'])){
							chmod(WWW_ROOT.'cloud/'.$this->Auth->user('id').'/'.$_FILES['file']['name'], 0777);
							echo $_POST['index'];
						}
						die();
					}
					if ($result) {
						if(move_uploaded_file($_FILES['file']['tmp_name'], WWW_ROOT.'cloud/'.$this->Auth->user('id').'/'.$string.'/'.$_FILES['file']['name'])){
							$dir = new Folder();
							chmod(WWW_ROOT.'cloud/'.$this->Auth->user('id').'/'.$string.'/'.$_FILES['file']['name'], 0777);
							echo $_POST['index'];
						}
						die();
					}
				}
			}
			echo 'erreurs';
			die();
		}
	}

	public $paginate = [
	'maxLimit' => 10
	];
	public function find() {
		// $dir = new Folder(WWW_ROOT.'cloud/'.$this->Auth->user('id').DS);
		// $files = $dir->findRecursive('.*\.*', false);
		$this->set('username', $this->Auth->user('username'));
		$this->set('files', $this->paginate($this->Uploads->find('all')->where(['id_user' => $this->Auth->user('id')])->order(['created' => 'DESC'])));
	}
	public function mail($post) {
		debug($post);
		if (!empty($post)) {
			if (!empty($post['file'])) {
				$link = $post['file'];
				$file = $this->Uploads->find('all')->where(['file' => $post['file'], 'id_user' => $this->Auth->user('id')]);
				debug($file->toArray()[0]->mime);
				$true = true;
				unset($post[0]);
				unset($post[1]);
				unset($post[2]);
			} else {
				$true = false;
				$link = $post['choix'].'/'.$post['folder'];
				unset($post[0]);
				unset($post[1]);

			}
			$tbl_mail = array();
			for ($i = 1; $i < count($post)-2; $i++) {

				$articlesTable = TableRegistry::get('users');
				$article1 = $articlesTable->find('all')->where(['email' => $post['mail'.$i]]);

				if (!empty($article1->toArray()[0])) {
					$tbl_mail[$article1->toArray()[0]->email] = $article1->toArray()[0]->email;

					$articlesTable = TableRegistry::get('uploads');
					$article = $articlesTable->newEntity();
					$article->id_user = $this->Auth->user('id');
					if ($true) {
						$article->file = $link;
						$article->folder = $post['folder'];
						$article->size = $file->toArray()[0]->size;
						$article->mime = $file->toArray()[0]->mime;
					}
					else {
						$article->folder = $link;
					}
					$article->rule = $post['rule'];
					
					$article->auth_user_id = $article1->toArray()[0]->id;


				}
			}
			if (!empty($tbl_mail) && $articlesTable->save($article)) {
				$username = $this->Auth->user('username');
				$email = new Email();
				$email->template('default')
				->viewVars(['username' => $username, 'link' => $link])
				->emailFormat('html')
				->to($tbl_mail)
				->from('webacademie.php@gmail.com')
				->send();
			}
		}
	}
	public function display() {
		// $uploads = $this->Uploads->find('all')->where(['auth_user_id' => $this->Auth->user('id')])->orWhere(['rule' => 2]);
		// $uploads = $uploads->toArray();
		$this->set('uploads', $this->paginate($this->Uploads->find('all')->where(['auth_user_id' => $this->Auth->user('id')])->orWhere(['rule' => 2])));

	}
	public function newfolder(){


		$path = explode('/', $this->request->data['choix']);
		unset($path[0]);
		// debug($path);
		$string = implode('/', $path);

		$this->mail($this->request->data);

		$articlesTable = TableRegistry::get('uploads');
		$article = $articlesTable->newEntity();
		$article->id_user = $this->Auth->user('id');
		$article->folder = $this->request->data['choix'].'/'.$this->request->data['folder'];
		$article->rule = 0;

		$result = file_exists(WWW_ROOT.'cloud/'.$this->Auth->user('id'));

		$folder_ex = file_exists(WWW_ROOT.'cloud/'.$this->Auth->user('id').'/'.$string.'/'.$this->request->data['folder']);
		$secu = strpos($this->request->data['folder'], "../");
		$secu2 = preg_match('/^[A-Za-z][A-Za-z0-9._-]*$/', $this->request->data['folder']);

		if (!$folder_ex && $secu === false || $secu2 == 1) {
			if (!$result) {
				$folder = new Folder(WWW_ROOT.'cloud'. DS, true, 0777);
				$folder->create($this->Auth->user('id'));
				chmod(WWW_ROOT.'cloud/'.$this->Auth->user('id').'/'.$string.DS.$this->request->data['folder'], 0777);
				$folder = new Folder(WWW_ROOT.'cloud'. DS .$this->Auth->user('id'). DS, true, 0777);
				$result = $folder->create($this->request->data['folder']);

			}
			if ($result) {
				$folder = new Folder(WWW_ROOT.'cloud'. DS .$this->Auth->user('id'). DS.$string.DS, true, 0777);
				$result = $folder->create($this->request->data['folder']);
			}
			if ($result && $articlesTable->save($article)) {
				$this->mail($this->request->data);
				$this->Flash->success(__('Nouveau dossier créer.'));
				return $this->redirect(['action' => 'uploads']);
			}
			$this->Flash->error(__('Impossible de créer votre dossier.'));
			return $this->redirect(['action' => 'uploads']);
		}
		$this->Flash->error(__('Impossible de créer votre dossier.'));
		return $this->redirect(['action' => 'uploads']);
	}
	public function droit() {
		$this->set('uploads', $this->paginate($this->Uploads->find('all')
			->select(['users.username', 'Uploads.id' , 'Uploads.id_user', 'Uploads.folder', 'Uploads.file', 'Uploads.rule', 'Uploads.size', 'Uploads.mime', 'Uploads.auth_user_id', 'Uploads.created', 'Uploads.updated'])
			->join([
				'table' => 'users',
				'conditions' => 'users.id = Uploads.auth_user_id',
				])->where(['Uploads.id_user' => $this->Auth->user('id')])));

	}
	public function editfolder($id) {

		$query = $this->Uploads->find('all');
		$query = $query->select(['folder'])->where(['id_user' =>  $this->Auth->user('id')]);
		$tbl = array();
		for ($i=0; $i < count($query->toArray()); $i++) { 
			if ($tbl != $query->toArray()[$i]->folder) {
				$tbl[$query->toArray()[$i]->folder] = $query->toArray()[$i]->folder;
			}
		}
		$this->set('tbl', $tbl);

		$file = $this->Uploads->get($id);
		$this->set('file', $file);
		if ($file->id_user === $this->Auth->user('id')) {
			if ($this->request->is(['post', 'put'])) {

				$path = explode('/', $file['folder']);
				$name = end($path);
				unset($path[0]);
				$string = implode('/', $path);

				$path = explode('/', $string);
				unset($path[count($path)]);
				$string2 = implode('/', $path);

				// $articlesTable = TableRegistry::get('uploads');
				// $article = $this->Uploads->find('all')->where(['id' => $this->Auth->user('id'), 'folder' => $file['folder']]);
				// $article = $this->Uploads->newEntity();
				$file->id_user = $this->Auth->user('id');
				$file->folder = $this->Auth->user('username').'/'.$this->request->data['folder'];
				// $this->Uploads->patchEntity($file, $this->request->data);
				// debug($this->request->data);
				// debug($this->Auth->user('username').'/'.$this->request->data['folder']);
				// debug($string2);
				$tbl = array('folder' => $this->Auth->user('username').'/'.$string2.'/'.$this->request->data['folder']);
				$secu2 = preg_match('/^[A-Za-z][A-Za-z0-9._-]*$/', $this->request->data['folder']);

				if ($secu2 == 1 && $this->Uploads->save($file)) {
					$this->mail($this->request->data);
					rename(WWW_ROOT.'cloud/'.$this->Auth->user('id')."/".$string, WWW_ROOT.'cloud/'.$this->Auth->user('id')."/".$this->request->data['folder']);
					$this->Flash->success(__('Votre dossier a été mis à jour.'));
					return $this->redirect(['action' => 'files']);
				}
				$this->Flash->error(__('Impossible de mettre à jour votre dossier.'));
				return $this->redirect(['action' => "editfolder/$id"]);
			}

			$this->set('file', $file);
		} else {
			$this->Flash->error(__('Impossible de mettre à jour votre dossier.'));
			return $this->redirect(['action' => 'files']);
		}


	}
	public function edit($id) {
		$query = $this->Uploads->find('all');
		$query = $query->select(['folder'])->where(['id_user' =>  $this->Auth->user('id')]);
		$tbl = array();
		for ($i=0; $i < count($query->toArray()); $i++) { 
			if ($tbl != $query->toArray()[$i]->folder) {
				$tbl[$query->toArray()[$i]->folder] = $query->toArray()[$i]->folder;
			}
		}

		$this->set('tbl', $tbl);
		$file = $this->Uploads->get($id);
		// $this->mail($this->request->data);
		$name = $file['file'];
		$path = explode('/', $file['folder']);
		unset($path[0]);
		$string = implode('/', $path);
		// $this->mail($this->request->data);
		$name_folder = $file['folder'];

		if ($file->id_user === $this->Auth->user('id')) {
			if ($this->request->is(['post', 'put'])) {
				$secu2 = preg_match('/^[A-Za-z][A-Za-z0-9._-]*$/', $this->request->data['file']);
				$path = explode('/', $this->request->data['folder']);
				unset($path[0]);
				$name_folder = implode('/', $path);
				$file->rule = 0;
				$this->Uploads->patchEntity($file, $this->request->data);
				if ($secu2 == 1 && $this->Uploads->save($file)) {
					if($name_folder != $this->request->data['folder']) {
						rename(WWW_ROOT.'cloud/'.$this->Auth->user('id')."/".$string.'/'.$name, WWW_ROOT.'cloud/'.$this->Auth->user('id')."/".$name_folder.'/'.$this->request->data['file']);
					} else {
						rename(WWW_ROOT.'cloud/'.$this->Auth->user('id')."/".$string.'/'.$name, WWW_ROOT.'cloud/'.$this->Auth->user('id')."/".$name_folder.'/'.$this->request->data['file']);

					}
					$this->mail($this->request->data);
					$this->Flash->success(__('Votre fichier a été mis à jour.'));
					return $this->redirect(['action' => 'files']);
				}
				$this->Flash->error(__('Impossible de mettre à jour votre fichier.'));
				return $this->redirect(['action' => "edit/$id"]);
			}

			$this->set('file', $file);
		} else {
			$this->Flash->error(__('Impossible de mettre à jour votre article.'));
			return $this->redirect(['action' => 'files']);
		}
	}
	public function automail() {
		$articlesTable = TableRegistry::get('users');
		$article = $articlesTable->find('all')->select(['value' => 'email'])->where(['email LIKE' => $this->request->query['term'].'%']);
		echo json_encode($article->toArray());
		die();

	}
	public function delete($id)
	{
		if ($this->request->referer() == "/") {
			$this->Flash->error(__('Ressource Interdite'));
			return $this->redirect(['action' => 'index']);
		}
		$this->request->allowMethod(['get','post', 'delete']);
		$file = $this->Uploads->get($id);
		$name = $file['file'];
		$folder = $file['folder'];
		$path = explode('/', $file['folder']);
		unset($path[0]);
		$string = implode('/', $path);
		if ($file->id_user === $this->Auth->user('id')) {
			if ($this->Uploads->delete($file)) {
				if ($folder == $this->Auth->user('username')) {
					unlink(WWW_ROOT.'cloud/'.$this->Auth->user('id').'/'.$file['file']);
					$this->Uploads->deleteAll(['folder' => $file['file']]);
					
				} else {
					array_map('unlink', glob(WWW_ROOT.'cloud/'.$this->Auth->user('id').'/'.$string."/*"));
					rmdir(WWW_ROOT.'cloud/'.$this->Auth->user('id').'/'.$string);
					$this->Uploads->deleteAll(['folder' => $file['folder']]);
					
				}
				$this->Flash->success(__("Le fichier été supprimé.", h($id)));
				return $this->redirect(['action' => 'files']);
			}
		} else {
			$this->Flash->error(__('Impossible supprimé le fichier.'));
			return $this->redirect(['action' => 'files']);
		}
	}
	public function deletedroit($id)
	{
		if ($this->request->referer() == "/") {
			$this->Flash->error(__('Ressource Interdite'));
			return $this->redirect(['action' => 'index']);
		}
		$this->request->allowMethod(['get','post', 'delete']);
		$file = $this->Uploads->get($id);
		$name = $file['file'];
		if ($file->id_user === $this->Auth->user('id')) {
			if ($this->Uploads->delete($file)) {
				$this->Flash->success(__("Le droit été supprimé.", h($id)));
				return $this->redirect(['action' => 'droit']);
			}
		} else {
			$this->Flash->error(__('Impossible supprimé le droit.'));
			return $this->redirect(['action' => 'droit']);
		}
	}
}