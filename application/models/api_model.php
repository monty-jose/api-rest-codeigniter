<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class api_model extends CI_Model {

	/**
	* [__construct description]
	*/
	function __construct() {
		parent::__construct();

		$this->_schema = '';
		$this->_table  = 'mhipo_reunion';
	}
	
	/**
    * Obtener los datos del usuario
    *
    * @access public
    * @param  date $fecha
    * @return array
    */
	public function getReunion($fecha)
	{
		//Obtenemos todas las carreras para la fecha de reunion recibida por parametros
	    $sql = "SELECT mc.id, mc.nombre, mc.idReunion, mc.nroCarrera, mc.nroLlamado,
				       mc.distancia, mc.hora, mc.edadDesde , mc.edadHasta , mc.sexo , 
				       mc.ganadas , mc.descripcion, mc.condicion, mc.condicionAbreviada, 
				       mc.peso, mc.cupo, mc.mejorTiempo,mc.peso, mc.mejorTiempo, mc.record,
				       mc.detalle,r.fecha
				FROM mhipo_reunion r JOIN mhipo_carrera mc ON (r.id=mc.idReunion)
				WHERE r.fecha = '".$fecha."'
				ORDER BY nroCarrera";
		$result = $this->db->query($sql);

		foreach( $result->result() as $row )
	    {
	    	$id_reunion 	= (int)$row->idReunion;
	    	$fecha_reunion  = $row->fecha;
	    	//cargamos los premios del id carrera
			$premios      = $this->getPremios((int)$row->id);
			// obtenemos los competidores del id carrera
			$competidores = $this->getCompetidores((int)$row->id);

			$tiempo   = explode("'", $row->mejorTiempo);
			$minutos  = 0;
			$segundos = 0;
			$decimas  = 0;
			//dividimos el mejor tiempo de la carrera.
			if(count($tiempo)==4)
			{
				$minutos  = $tiempo[0];
				$segundos = $tiempo[1];
				$decimas  = $tiempo[3];
			}
			else
			{
				if(count($tiempo)==3)
				{
					$segundos = $tiempo[0];
					$decimas  = $tiempo[2];
				}
			}

	    	$carreras [] = 	array( 	"id" 		=> null,
						    		"estado"	=> 4,//1 - programa oficial 4- Resultado cargados
						    		"numero"	=> 1, 
						    		"horario"	=> $row->hora,
						    		"premio"	=> $row->nombre,
						    		"distancia"	=> $row->distancia,
		    						"tipo_carrera" 	=> array("id"	 => 1,
		    												"nombre" => "Condicional"),
		    						"tipo_pista" 	=> array("id"	 => 3,
		    												"nombre" => "Arena"),
		    						"estado_pista" 	=> array("id"	 => 5,
		    												"nombre" => "Pesada"),
		    						"tipo_codo" 	=> array("id"	 => null,
		    												"nombre" => null),
		    						"condicion" 	=> array(	"texto"  => $row->condicion,
			    												"edaddesde"  => $row->edadDesde,
			    												"edadhasta"   => $row->edadHasta,
			    												"sexo" 		  => $row->sexo,
			    												"ganadadesde" => $row->ganadas,//aca hay que hacer un split (1-2)
				    											"ganadahasta" => $row->ganadas,
				    											"kilos" 	  => $row->peso),
		    						"tiempo" 		=> array( "minutos"	 => $minutos,
		    												  "segundos" => $segundos,
		    												  "decimas"  => $decimas),
		    						"premios" 	=> array($premios),
		    						"video" 	=> null,
		    						"competidores_cantidad" 	=> null,
		    						"competidores"	=> array($competidores)
	    						);
	    	
	    }

		$reunion  	= array(	"id" => (int)$row->idReunion,
	    						"fecha" => array("date"			 => $row->fecha,
	    										 "timezone_type" => 3,
	    										 "timezone"		 => "America/Argentina/Buenos_Aires"),
	    						"hipodromo" => array("id"	 	=> null,
	    											 "nombre"	=> "La Punta",
	    											 "numero" 	=> null),
	    						"carreras" => $carreras);

		// json_encode($items); 
	    return $reunion;
	}

	/**
    * Obtener los premios cargados para el id carrera
    *
    * @access private
    * @param  int $id
    * @return array
    */
	private function getPremios($id)
	{
		//traemos los premios de la carrera
    	$sql = "SELECT mp.id, mp.monto, mp.nroPremio 
				FROM mhipo_premio mp 
				WHERE mp.idCarrera =".$id;

		$r_premios = $this->db->query($sql);

		foreach( $r_premios->result() as $row_premio )
	    {
	    	$premios [] =  array("puesto"  => $row_premio->nroPremio,
	    					  	 "importe" => $row_premio->monto);
	    }
	    return $premios;
	}

	/**
    * Obtenemos los competidores de la carrera
    *
    * @access private
    * @param  int $id
    * @return array
    */
	private function getCompetidores($id)
	{
		//traemos los premios de la carrera
		$sql = "SELECT mi.idEquino, id_studbook,me.nombre AS nombre_equino,me.kilos, kilosJockey,mc.nombre AS criador,
				 		mp.nombre AS jockey, mp.dni AS dni_jockey, gatera, diferencia, puesto
				FROM mhipo_resultado mi 
					INNER JOIN mhipo_inscripcion m ON (m.idCarrera=mi.idCarrera AND m.idEquino=mi.idEquino)
					INNER JOIN mhipo_equino me ON (mi.idEquino=me.id) 
					INNER JOIN mhipo_criador mc ON (mc.id = me.idCriador)
					INNER JOIN mhipo_persona mp ON (mi.idJockey=mp.id)
				WHERE mi.idCarrera = ".$id."
				ORDER BY puesto";

		$r_competidores = $this->db->query($sql);
		

		foreach( $r_competidores->result() as $row_competidores )
	    {
	    	$cuerpos        = explode(" ", $row_competidores->diferencia);

	    	$competidores [] =  array( "puesto"  	=> $row_competidores->puesto,
	    					  	 		"orden" 	=> $row_competidores->gatera,
		    					  	 	"yunta"  	=> null,
		    					  	 	"ejemplar" 	=> array("nombre" => $row_competidores->nombre_equino ,
		    					  	 						 "id" 	  => $row_competidores->id_studbook),
		    					  	 	"kilos_ejemplar"=> $row_competidores->kilos,
		    					  	 	"jockey" 		=> array("nombre" 	=> $row_competidores->jockey ,
			    					  	 						 "cuit"  	=> $row_competidores->dni_jockey,
			    					  	 						 "id" 	 	=> null),
		    					  	 	"cuidador"  	=> array("nombre" 	=> $row_competidores->criador,
			    					  	 						 "cuit"  	=> null,
			    					  	 						 "id" 	 	=> null),
		    					  	 	"caballeriza" 	=> array("nombre" 	=> null,
		    					  	 						 	"id" 	  	=> null),
		    					  	 	"jockey_kilos"  => $row_competidores->kilosJockey,
		    					  	 	"cuerpos"  		=> array("id"  		=> $cuerpos[0],
		    					  	 							 "nombre" 	=> $row_competidores->diferencia),
		    					  	 	"pagaria"		=> null
	    					  	 	);
	    }
	    return $competidores;
	}
}

/* Fin del archivo api_model.php */
/* Ubicacion: ./application/models/api_model.php */