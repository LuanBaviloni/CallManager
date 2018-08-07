<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->load->database();
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->load->helper(array('url', 'language'));

        $this->load->library('grocery_CRUD');
        $this->load->model('listas_model');

        $this->lang->load('auth');
    }

    public function _admin_output($output = null) {
        $user = $this->ion_auth->user()->row();

        if ($this->ion_auth->is_admin()) {
            $menuListas = $this->listas_model->getAll();
        } else {
            $menuListas = $this->listas_model->getAll($user->id);
        }
        
        $output->menu = $menuListas;
        $output->user = $user;
        $this->load->view('admin.php', (array) $output);
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
            $this->_admin_output((object) array(
                        'output' => '',
                        'js_files' => array(),
                        'css_files' => array(),
                        'pageTitle' => 'Bem-vindo',
                        'pageSubTitle' => ''
            ));
        }
    }

    function unique_join_name($field_name) {
        return 'j' . substr(md5($field_name), 0, 8); //This j is because is better for a string to begin with a letter and not with a number
    }

    function unique_field_name($field_name) {
        return 's' . substr(md5($field_name), 0, 8);
    }

    public function pessoas($listaID = NULL) {
        if (!$this->ion_auth->logged_in()) {

            redirect('auth/login', 'refresh');
        } else {
            $crud = new grocery_CRUD();
            $crud->unset_bootstrap();
            
            $user = $this->ion_auth->user()->row();

            $title = "";
            $subtitle = "";
            if (($listaID != NULL) && ((int) $listaID != 0)) {
                $crud->where('pessoas.lista_id', (int) $listaID);

                $title = "Contatos";
                $subtitle = 'Contatos disponiveis na lista cod #' . $listaID;
            } else {
                $title = "Todos os contatos";
                $subtitle = 'Listando todos os contatos disponiveis';
            }

            $crud->set_model('Pessoas_model');
            $crud->set_table('pessoas');
            $crud->set_subject('Contato');

            $crud->columns('pessoa_nome', 'pessoa_telmain', 'pessoa_alerta', 'pessoa_detalhes', 'status_id', 'ligar');
            $crud->set_relation('status_id', 'status', 'status_nome');
            $crud->set_relation('lista_id', 'listas', 'lista_nome', array('login_id' => $user->id));
            $crud->where($this->unique_join_name('lista_id') . '.login_id', $user->id);

            $crud->callback_column('ligar', array($this, '_callback_ligar'));
            $crud->callback_column($this->unique_field_name('status_id'), array($this, '_callback_status'));


            $crud->display_as('pessoa_nome', 'Nome');
            $crud->display_as('pessoa_telmain', 'Telefone');
            $crud->display_as('pessoa_agencia', 'Agencia');
            $crud->display_as('pessoa_conta', 'Conta');
            $crud->display_as('pessoa_cnpj', 'CNPJ');
            $crud->display_as('pessoa_responsavel', 'Tratar com');
            $crud->display_as('pessoa_telsec', 'Telefone 2');
            $crud->display_as('pessoa_cel', 'Celular');
            $crud->display_as('pessoa_alerta', 'Data de Retorno');
            $crud->display_as('pessoa_detalhes', 'Detalhes');
            $crud->display_as('status_id', 'Status');
            $crud->display_as('status_nome', 'Status');
            $crud->display_as('lista_id', 'Lista');

            $crud->set_rules('pessoa_nome', 'Nome', 'required');
            $crud->set_rules('pessoa_telmain', 'Telefone', 'required');
            $crud->set_rules('lista_id', 'Lista', 'required');
            $crud->set_rules('status_id', 'Status', 'required');

            $crud->unset_edit_fields('lista_id');            
            $crud->unset_texteditor('pessoa_detalhes', 'full_text');

            $output = $crud->render();
            $output->pageTitle = $title;
            $output->pageSubTitle = $subtitle;

            $this->_admin_output($output);
        }
    }

    public function _callback_ligar($value, $row) {
        $dev = '<a id="call' . $row->pessoa_id . '" class="btn btn-info" onclick="javascript: openInNewTab(\'' . base_url('admin/pessoas/edit/' . $row->pessoa_id) . '\', \'skype:+55' . preg_replace("/[^0-9]/", "", $row->pessoa_telmain) . '\')"><i class="fa fa-skype"></i> Ligar</a>';
        return $dev;
    }

    public function _callback_status($value, $row) {
        $dev = "";
        $dev = '<b id="statuspessoa' . $row->pessoa_id . '" class="' . $row->status_cor . '">' . $value . '</b>';
        return $dev;
    }

    public function status() {
        if (!$this->ion_auth->logged_in()) {

            redirect('auth/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {

            return show_error('You must be an administrator to view this page.');
        } else {
            $crud = new grocery_CRUD();
            $crud->unset_bootstrap();

            $crud->set_table('status');
            $crud->set_subject('Status');
            $crud->unset_columns('status_id');

            $crud->display_as('status_nome', 'Nome');
            $crud->display_as('status_cor', 'Classe');

            $crud->unset_read();

            $output = $crud->render();
            $output->pageTitle = 'Status';
            $output->pageSubTitle = 'Adicione e modifique os <b>status</b> que um contato pode possuir.';

            $this->_admin_output($output);
        }
    }

    public function grupos() {
        if (!$this->ion_auth->logged_in()) {

            redirect('auth/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {

            return show_error('You must be an administrator to view this page.');
        } else {
            $crud = new grocery_CRUD();
            $crud->unset_bootstrap();

            $crud->set_table('groups');
            $crud->set_subject('Grupos');
            $crud->unset_columns('id');

            $crud->display_as('name', 'Nome');
            $crud->display_as('description', 'Descri&ccedil;&atilde;o');

            $crud->unset_read();

            $output = $crud->render();
            $output->pageTitle = 'Grupos';
            $output->pageSubTitle = 'Adicione e modifique os <b>grupos</b> que um <b>usu&aacute;rio</b> pode pertencer.';

            $this->_admin_output($output);
        }
    }

    public function logins() {
        if (!$this->ion_auth->logged_in()) {

            redirect('auth/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {

            return show_error('You must be an administrator to view this page.');
        } else {
            $crud = new grocery_CRUD();
            $crud->unset_bootstrap();

            $crud->set_table('logins');
            $crud->set_subject('Usuários');


            $crud->columns('first_name', 'email', 'username', 'login_level');
            $crud->set_relation_n_n('login_level', 'users_groups', 'groups', 'user_id', 'group_id', 'description');

            $crud->unset_read();
            $crud->fields('first_name', 'last_name', 'username', 'email', 'password', 'passconf', 'login_level', 'active');

            $crud->field_type('password', 'password');
            $crud->field_type('passconf', 'password');
            //$crud->field_type('login_level',    'dropdown', array('1' => 'Administrador', '2' => 'Usuario'));

            $crud->display_as('first_name', 'Nome');
            $crud->display_as('last_name', 'Sobrenome');
            $crud->display_as('username', 'Usuario');
            $crud->display_as('password', 'Senha');
            $crud->display_as('login_level', 'Grupos');
            $crud->display_as('passconf', 'Confirma&ccedil;&atilde;o de Senha');
            $crud->display_as('active', 'Status');


            $crud->set_rules('first_name', 'Nome', 'required');
            $crud->set_rules('last_name', 'Sobrenome', 'required');
            $crud->set_rules('email', 'Email', 'required');
            $crud->set_rules('username', 'Usuario', 'required');


            $crud->set_rules('password', 'Senha', 'required|trim');
            $crud->set_rules('passconf', 'Confirma&ccedil;&atilde;o de Senha', 'required|trim|matches[password]');

            $crud->callback_before_insert(array($this, 'encrypt_password_callback'));
            $crud->callback_before_update(array($this, 'encrypt_password_callback'));

            $crud->callback_edit_field('password', function ($value, $primary_key) {
                return '<input id="field-password" class="form-control" name="password" type="password" value="" maxlength="255">';
            });

            $crud->callback_read_field('password', function ($value, $primary_key) {
                return "********************************";
            });

            $output = $crud->render();
            $output->pageTitle = 'Usu&aacute;rios';
            $output->pageSubTitle = 'Usuários cadastrados no sistema'; // <span class="alert alert-warning">Atenção: <b>NÃO</b> remova o Administrador.</span>';

            $this->_admin_output($output);
        }
    }

    function encrypt_password_callback($post_array, $primary_key = null) {
        unset($post_array['passconf']);

        $this->load->model('Ion_auth_model');


        $salt = $this->Ion_auth_model->store_salt ? $this->Ion_auth_model->salt() : FALSE;
        $password = $this->Ion_auth_model->hash_password($post_array['password'], $salt);
        $post_array['password'] = $password;
        if ($this->store_salt) {
            $post_array['salt'] = $salt;
        }

        $post_array['ip_address'] = $this->input->ip_address();
        return $post_array;
    }

    public function listas() {
        if (!$this->ion_auth->logged_in()) {

            redirect('auth/login', 'refresh');
        } else {
            $crud = new grocery_CRUD();
            $crud->unset_bootstrap();

            $user = $this->ion_auth->user()->row();

            $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'txt');

            $crud->set_table('listas');
            $crud->set_subject('Listas');
                                   
            $crud->add_fields('lista_nome', 'login_id');
            $crud->edit_fields('lista_id', 'lista_nome', 'login_id', 'lista_arquivo');

            if (!$this->ion_auth->is_admin()) {
                $crud->columns('lista_nome');
                $crud->field_type('login_id', 'invisible');
                $crud->where('login_id', $user->id);                
            } else {
                $crud->columns('lista_nome', 'login_id');
                $crud->set_relation('login_id', 'logins', '{first_name} {last_name} ({username})');
            }
            $crud->field_type('lista_id', 'hidden');

            $crud->set_field_upload('lista_arquivo');
            $crud->callback_after_upload(array($this, 'lista_after_upload'));

            $crud->display_as('lista_nome', 'Nome da lista');
            $crud->display_as('lista_arquivo', 'Arquivo');
            $crud->display_as('login_id', 'Usuario');

            $crud->set_rules('lista_nome', 'Nome da lista', 'required');
            $crud->unset_read();

            $crud->callback_before_insert(array($this, 'lista_before_insert'));

            $output = $crud->render();
            $output->pageTitle = 'Listas';
            $output->pageSubTitle = 'Cadastre e altere as listas de contatos';

            $this->_admin_output($output);
        }
    }

    function lista_before_insert($post_array, $primary_key = null) {
        $user = $this->ion_auth->user()->row();

        if (!$this->ion_auth->is_admin()) {
            $post_array['login_id'] = $user->id;
        }
        return $post_array;
    }

    function lista_after_upload($uploader_response, $field_info, $files_to_upload) {
        $file_uploaded = $field_info->upload_path . '/' . $uploader_response[0]->name;

        if ($file = fopen($file_uploaded, "r")) {
            $batch = array();
            while (!feof($file)) {
                $line = fgets($file);

                $dados = explode('|', $line);

                if (count($dados) >= 5) {
                    $insert = array(
                        'pessoa_agencia' => $dados[0],
                        'pessoa_conta' => $dados[1],
                        'pessoa_nome' => $dados[2],
                        'pessoa_cnpj' => $dados[3],
                        'pessoa_telmain' => $dados[4],
                        'status_id' => 1,
                        'lista_id' => $this->input->post('lista_id'),
                    );
                    array_push($batch, $insert);
                }
            }
            $this->listas_model->insert_lista($batch);
            fclose($file);
        }

        return true;
    }
    
    function alarms()
    {
        $user = $this->ion_auth->user()->row();
        $alarms = $this->listas_model->getAlarms($user->id);
        echo $alarms;
    }

}
