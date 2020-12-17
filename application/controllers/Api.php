<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

class Api extends REST_Controller {

	public function __construct() {
        parent::__construct();
		
        $this->_user = '';
		$this->_password = '';
        $this->load->helper(['jwt', 'authorization','date']); 
    }

    public function index()
    {
    	$this->login_post();
    }
	/**
	* funcion error
	*
	* @access private
	* @return json status
	*/
	private function error() 
	{
        // Token es invalido	        
		$status   =  401;//REST_Controller::HTTP_UNAUTHORIZED;// response hhtp sin autorizacion
		$response = ['status' => $status, 'msg' => 'Acceso no autorizado'];
        return $this->response($response, $status);
    }


	/**
	* Virificamos que el user y pass sean correctos para la generacion del token
	*
	* @access public
	* @return token
	*/
    public function login_post()
	{
	    // extraemos el POST del request
	    $username = $this->post('username');
	    $password = $this->post('password');
	    // Verificamos si usuario y contraseña coinciden
	    if ($username === $this->_user && $password === $this->_pass) 
	    {	        
	        // Creamos el token con el username y enviamos (reponse)
	        $token = AUTHORIZATION::generateToken(['username' => $this->_user,'timestamp'=>now()]);
	        // Preparamos la respuesta
	        $status = 200;//REST_Controller::HTTP_OK;
	        $response = ['status' => $status, 'token' => $token, 'ruta' => $_SERVER['SERVER_SOFTWARE']];
	        $this->response($response, $status);
	    }
	    else 
	    {
	        $this->response(['msg' => 'Usuario y contraseña invalidos'], 401);
	    }
	}

	/**
	* Validamos que el token generado sea valido
	*
	* @access private
	* @return validation
	*/
	private function verify_request()
	{
	    try 
	    {
	    	
	    	$headers = $this->input->request_headers();
	    	
	    	if(isset($headers['Authorization']))
	    	{
	    		
		    	$token 	= $headers['Authorization'];		        
		        $data 	= AUTHORIZATION::validateTimestamp($token);

		        if ($data === false) 
		        {
					$status   =  401;
					$response = ['status' => $status, 'msg' => 'Acceso no autorizado'];
		            $this->response($response, $status);
		            exit();
		        } 
		        else 
		        {
		            return $data;
		        }
	    	}
	    	else
	    	{
	    		$this->error();
	    	}
	    	
	    } 
	    catch (Exception $e) 
	    {
	        $this->error();
	    }
	}

	public function datos_get()
	{
	    $data = $this->verify_request();

	    $status = 200;//REST_Controller::HTTP_OK;
	    $response = ['status' => $status, 'data' => $data];
	    $this->response($response, $status);
	}

	public function Meeting_get($fecha)
	{
		try 
	    {
		    $this->verify_request();
	    	$this->load->model("api_model");

	    	$data = $this->api_model->getReunion($fecha);

		    $status 	= 200;
		    $response 	= ['status' => $status, 'data' => $data];
		    $this->response($response, $status);
		}
		catch (Exception $e) 
	    {
	        $this->error();
	    }
	}
}
/* End of file api_slots.php */
/* Location: ./application/controllers/api_slots.php */