<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController
{
    public function isAuthorized($user)
    {
        if ($this->action == 'logout') {
            return true;
        }

        if (in_array($this->action, array('view', 'edit'))) {
            $user_id = (isset($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : false);
            if ($user_id == $user['id']) {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }

    public function index()
    {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null)
    {
        $this->User->id = $id;
        if ( ! $this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
        $access_roles = array(
            'admin'   => 'Administrator',
            'limited' => 'Limited'
        );
        $this->set(compact('access_roles'));
    }

    public function edit($id = null)
    {
        $this->User->id = $id;
        if ( ! $this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'edit', $id));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
        }
        $access_roles = array(
            'admin'   => 'Administrator',
            'limited' => 'Limited'
        );
        $this->set(compact('access_roles'));
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

    public function login()
    {
        if ($this->Session->read('Auth.User')) {
            $this->Session->setFlash('You are logged in!');
            $this->redirect(array('controller' => 'databases', 'action' => 'index'), null, false);
        }

        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash('Your username or password was incorrect.');
            }
        }
    }

    public function logout()
    {
        $this->Session->setFlash('Good-Bye');
        $this->redirect($this->Auth->logout());
    }
}
