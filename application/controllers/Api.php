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
		
        // $this->load->database();
        // $this->load->helper('url');
        $this->_user = 'studbook';
		$this->_pass = 'hipodromo2020';
        //cargamos la libreria de json web token
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
	    // Use try-catch
	    try 
	    {
	    	// Accedemos al header
	    	$headers = $this->input->request_headers();
	    	//Si esta seteado el token
	    	if(isset($headers['Authorization']))
	    	{
	    		// Extraemos el token
		    	$token = $headers['Authorization'];
		        //Si es una valdacion sin tiempo de caducidad
		        /* $data = AUTHORIZATION::validateToken($token);*/
		        $data = AUTHORIZATION::validateTimestamp($token);

		        // Si el token es valido retornamos el user data, si no retorna false
		        if ($data === false) 
		        {
					$status   =  401;//REST_Controller::HTTP_UNAUTHORIZED;// response hhtp sin autorizacion
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

	/**
	* Funcion get la cual va a retornar el resultado de la peticion.
	*
	* @access public
	* @return response
	*/
	public function datos_get()
	{
	    //verificamos que la peticion sea valida
	    $data = $this->verify_request();

	    // Enviamos el ok
	    $status = 200;//REST_Controller::HTTP_OK;
	    $response = ['status' => $status, 'data' => $data];
	    $this->response($response, $status);
	}


	/**
	* Funcion que retorna los datos actuales de la reunion
	*
	* @access public
	* @return reunion
	*/
	public function reunion_get($fecha)
	{
		try 
	    {
		    //verificamos que la peticion sea valida
		    $this->verify_request();

		    // cargamos el modelo
	    	$this->load->model("api_model");

	    	$data = $this->api_model->getReunion($fecha);

		    // Enviamos el ok
		    $status = 200;//REST_Controller::HTTP_OK;
		    $response = ['status' => $status, 'data' => $data];
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