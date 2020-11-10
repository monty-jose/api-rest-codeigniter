<?php if (!defined('BASEPATH')) exit('No direct script access');

class Util {

  private $CI;

  /**
   * [__construct description]
   */
  function __construct() {
    $this->CI =& get_instance();
  }

  // --------------------------------------------------------------------

  /**
   * si se ejecuta el script desde linea de comandos
   *
   * @access public
   * @return bool
   */
  public function isCommandLineInterface() {
    return (php_sapi_name() === 'cli');
  }

  // --------------------------------------------------------------------

  /**
   * obtener el nombre y su abreviatura del mes,
   * segun su numero
   * 
   * @param  int $mes
   * @return array
   */
  public function nombreMes($numero) {
    $nombre_mes = '';
    $abbr_mes   = '';

    if( !is_null($numero) && is_numeric($numero) )
    {
      switch( $numero )
      {
        case 1:  $nombre = 'Enero';       $abbr = 'Ene'; break;
        case 2:  $nombre = 'Febrero';     $abbr = 'Feb'; break;
        case 3:  $nombre = 'Marzo';       $abbr = 'Mar'; break;
        case 4:  $nombre = 'Abril';       $abbr = 'Abr'; break;
        case 5:  $nombre = 'Mayo';        $abbr = 'May'; break;
        case 6:  $nombre = 'Junio';       $abbr = 'Jun'; break;
        case 7:  $nombre = 'Julio';       $abbr = 'Jul'; break;
        case 8:  $nombre = 'Agosto';      $abbr = 'Ago'; break;
        case 9:  $nombre = 'Septiembre';  $abbr = 'Sep'; break;
        case 10: $nombre = 'Octubre';     $abbr = 'Oct'; break;
        case 11: $nombre = 'Noviembre';   $abbr = 'Nov'; break;
        case 12: $nombre = 'Diciembre';   $abbr = 'Dic'; break;
        default: $nombre = '';            $abbr = '';
      }
    }

    return array('nombre' => $nombre, 'abbr' => $abbr);
  }

  // --------------------------------------------------------------------

  /**
   * dada una fecha obtener el nombre del dia y del mes
   *
   * @access public
   * @param $fecha fecha en formato yyyy-mm-dd
   * @return string
   */
  public static function nombreFecha($fecha = '') {
    $nombre_dia = '';
    $nombre_mes = '';
    $abbr_mes   = '';

    // si la fecha no viene en formato yanqui
    if( preg_match("/^\d{2}-\d{2}-\d{4}$/", $fecha) )
    {
      $fecha = explode('-', $fecha);
      $fecha = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
    }

    if( preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha) )
    {
      $dia = date('w', strtotime ($fecha));

      switch( $dia )
      {
        case 0: $nombre_dia = 'Domingo'; break;
        case 1: $nombre_dia = 'Lunes'; break;
        case 2: $nombre_dia = 'Martes'; break;
        case 3: $nombre_dia = 'Mi&eacute;rcoles'; break;
        case 4: $nombre_dia = 'Jueves'; break;
        case 5: $nombre_dia = 'Viernes'; break;
        case 6: $nombre_dia = 'S&aacute;bado'; break;
      }


      $mes = date('n', strtotime ($fecha));
      switch( $mes )
      {
        case 1: $nombre_mes = 'Enero'; $abbr_mes = 'Ene'; break;
        case 2: $nombre_mes = 'Febrero'; $abbr_mes = 'Feb'; break;
        case 3: $nombre_mes = 'Marzo'; $abbr_mes = 'Mar'; break;
        case 4: $nombre_mes = 'Abril'; $abbr_mes = 'Abr'; break;
        case 5: $nombre_mes = 'Mayo'; $abbr_mes = 'May'; break;
        case 6: $nombre_mes = 'Junio'; $abbr_mes = 'Jun'; break;
        case 7: $nombre_mes = 'Julio'; $abbr_mes = 'Jul'; break;
        case 8: $nombre_mes = 'Agosto'; $abbr_mes = 'Ago'; break;
        case 9: $nombre_mes = 'Septiembre'; $abbr_mes = 'Sep'; break;
        case 10: $nombre_mes = 'Octubre'; $abbr_mes = 'Oct'; break;
        case 11: $nombre_mes = 'Noviembre'; $abbr_mes = 'Nov'; break;
        case 12: $nombre_mes = 'Diciembre'; $abbr_mes = 'Dic'; break;
      }
    }

    return array('nombre_dia' => $nombre_dia, 'nombre_mes' => $nombre_mes, 'abbr_mes' => $abbr_mes);
  }

  // --------------------------------------------------------------------

  /**
   * dado un dia, obtener los dias anteriores de la semana
   *
   * @access public
   * @param int|string $dia
   * @return array
   */
  public static function diasAnteriores($dia) {
    $dias = array();

    if( !is_null($dia) )
    {
      if( is_numeric($dia) )
      {
        $dia = (int)$dia;

        switch( $dia )
        {
          // domingo
          case 0:
            $dias = array('S&aacute;bado', 'Viernes', 'Jueves', 'Mi&eacute;rcoles', 'Martes', 'Lunes');
            break;

          // lunes
          case 1:
            $dias = array('Domingo', 'S&aacute;bado', 'Viernes', 'Jueves', 'Mi&eacute;rcoles', 'Martes');
            break;

          // martes
          case 2:
            $dias = array('Lunes', 'Domingo', 'S&aacute;bado', 'Viernes', 'Jueves', 'Mi&eacute;rcoles');
            break;

          // miercoles
          case 3:
            $dias = array('Martes', 'Lunes', 'Domingo', 'S&aacute;bado', 'Viernes', 'Jueves');
            break;

          // jueves
          case 4:
            $dias = array('Mi&eacute;rcoles', 'Martes', 'Lunes', 'Domingo', 'S&aacute;bado', 'Viernes');
            break;

          // viernes
          case 5:
          case 'viernes':
            $dias = array('Jueves', 'Mi&eacute;rcoles', 'Martes', 'Lunes', 'Domingo', 'S&aacute;bado');
            break;

          // sabado
          case 6:
            $dias = array('Viernes', 'Jueves', 'Mi&eacute;rcoles', 'Martes', 'Lunes', 'Domingo');
            break;
        }
      }
      else
        if( is_string($dia) )
        {
          $dia = strtolower($dia);

          switch( $dia )
          {
            case 'domingo':
              $dias = array('S&aacute;bado', 'Viernes', 'Jueves', 'Mi&eacute;rcoles', 'Martes', 'Lunes');
              break;

            case 'lunes':
              $dias = array('Domingo', 'S&aacute;bado', 'Viernes', 'Jueves', 'Mi&eacute;rcoles', 'Martes');
              break;

            case 'martes':
              $dias = array('Lunes', 'Domingo', 'S&aacute;bado', 'Viernes', 'Jueves', 'Mi&eacute;rcoles');
              break;

            case 'miercoles':
            case 'miÈrcoles':
            case 'mi&eacute;rcoles':
              $dias = array('Martes', 'Lunes', 'Domingo', 'S&aacute;bado', 'Viernes', 'Jueves');
              break;

            case 'jueves':
              $dias = array('Mi&eacute;rcoles', 'Martes', 'Lunes', 'Domingo', 'S&aacute;bado', 'Viernes');
              break;

            case 'viernes':
              $dias = array('Jueves', 'Mi&eacute;rcoles', 'Martes', 'Lunes', 'Domingo', 'S&aacute;bado');
              break;

            case 'sabado':
            case 's·bado':
            case 's&aacute;bado':
              $dias = array('Viernes', 'Jueves', 'Mi&eacute;rcoles', 'Martes', 'Lunes', 'Domingo');
              break;
          }
        }
    }

    return $dias;
  }

  // --------------------------------------------------------------------

  /**
   *
   * @access public
   * @param $data YYYY-MM-DD | YYYY-MM-DD HH:MM:SS
   */
  public static function splitDate($date) {
    $r = array();

    if( preg_match("/^\d{4}-\d{2}-\d{2}$/",$date) )
    {
      $names = self::nombreFecha($date);

      $date = explode('-', $date);

      // comprobar la validez de la fecha
      if( checkdate($date[1] , $date[2] , $date[0]) )
      {
        $r = array(
          'year'       => $date[0],
          'month'      => $date[1],
          'day'        => $date[2],
          'name_day'   => $names['nombre_dia'],
          'name_month' => $names['nombre_mes'],
          'abbr_month' => $names['abbr_mes']
        );
      }
    }
    else
      if( preg_match("/^\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}$/",$date) )
      {
        $names = self::nombreFecha(substr($date, 0, 10));

        $hour = explode(':', substr($date, 11));
        $date = explode('-', substr($date, 0, 10));

        if( checkdate($date[1] , $date[2] , $date[0]) )
          $r = array(
            'year'       => $date[0],
            'month'      => $date[1],
            'day'        => $date[2],
            'hour'       => $hour[0],
            'minute'     => $hour[1],
            'second'     => $hour[2],
            'name_day'   => $names['nombre_dia'],
            'name_month' => $names['nombre_mes'],
            'abbr_month' => $names['abbr_mes']
          );
      }

    return $r;
  }

  // --------------------------------------------------------------------

  /**
   *
   * @access public
   * @param $data YYYY-MM-DD | YYYY-MM-DD HH:MM:SS
   */
  public static function fecha($fecha_db) {
   
    $m = substr($fecha_db,5,2);
    $d = substr($fecha_db,8,2);
    $a = substr($fecha_db,0,4);
    if (is_numeric($d) && is_numeric($m) && is_numeric($a)) 
    {
        return "$d/$m/$a";
    }
    else 
    {
        return "";
    }
  }

  // --------------------------------------------------------------------

  /**
   *
   * @access public
   * @param $data YYYY-MM-DD | YYYY-MM-DD HH:MM:SS
   */
  public static function fecha_db($fecha) {
   
    if (strstr($fecha,"/"))
        list($d,$m,$a) = explode("/",$fecha);
    elseif (strstr($fecha,"-"))
        list($d,$m,$a) = explode("-",$fecha);
    else
        return "";
    return "$a-$m-$d";
  }
  // --------------------------------------------------------------------

  /**
   * pasar a minusculas la cadena solicitada
   * 
   * @access public
   * @param string $str
   * @return string
   */
  public function lowercase($str = NULL) {
    if( is_string($str) )
    {
      if( function_exists('mb_strtolower') )
        return mb_strtolower($str,'ISO-8859-1');
      else
        return strtr(strtolower($str), '¿¡¬√ƒ≈∆«»… ÀÃÕŒœ–—“”‘’÷ÿŸ‹⁄','‡·‚„‰ÂÊÁËÈÍÎÏÌÓÔÒÚÛÙıˆ¯˘¸˙');
    }

    return '';
  }

  // --------------------------------------------------------------------


  /**
   * comprobar si es una peticion ajax
   *
   * @access public
   * @return boolean
   */
  public static function isAjax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
  }

  // --------------------------------------------------------------------

  /**
   * generar una cadena aleatoriamente
   *
   * @access public
   * @param $length cantidad de caracteres de la cadena aleatoria generada
   * @return string
   */
  public static function generateRandomString($length = 10) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ\n";
    $randomString = '';

    for( $i = 0; $i < $length; $i++ )
    {
      $random = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$random];
      if( $random%2 == 0 )
      {
        $randomString .= " ";
        $i++;
      }
    }

    return $randomString;
  }

  // --------------------------------------------------------------------

  /**
   * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
   *
   */
  public static function dump($var, $label = 'Dump', $echo = TRUE) {
    // Store dump in variable 
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    
    // Add formatting
    $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
    $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">
                ' . $label . ' => ' . $output .
              '</pre>';

    // Output
    if( $echo == TRUE )
    {
      echo $output;
    }
    else
    {
      return $output;
    }
  }

  // --------------------------------------------------------------------

  /**
   * 
   */
  public static function dump_exit($var, $label = 'Dump', $echo = TRUE) {
    self::dump($var, $label, $echo);
    exit;
  }

  // --------------------------------------------------------------------

  /**
   * compara fecha_a_comparar con la fecha actual,
   * y devuelve la diferencia en enteros
   */
  public function comparar_fechas($fecha_a_comparar = NULL) {
    if( !is_null($fecha_a_comparar) )// si no es nulo el par·metro
    {
      $fecha2 = strtotime($fecha_a_comparar);// convierte una descripciÛn de fecha/hora textual en InglÈs a una fecha Unix
      $num1=($fecha2-intval(time()))/60/60/24;
      return floor(abs($num1));
    }
    else
      return -1;
  }

  // --------------------------------------------------------------------

  /**
   * convierte grados, minutos y segundos a decimales
   * $x1,$y1,$z1 => LATITUD
   * $x2,$y2,$z2 => LONGITUD
   */
  public function degrees_to_decimal($x1,$y1,$z1,$x2,$y2,$z2) {
    //latitud 
    if( ($x1 >= -90) and ($x1  <= 90) )
      $xlat = round(abs($x1));
    else
      echo "x1 incorrect ".$x1."<br/>";
  
    if( ($y1 >= 0) and ($y1  <= 59) )
      $ylat = abs($y1/60);
    else
      echo "y1 incorrect ".$y1."<br/>";
    
    if( ($z1 >= 0) and ($y1  <= 60) )
      $zlat = round(abs($z1/3600),4); 
    else
      echo "z1 incorrect ".$z1."<br/>";
    
    $decimals_lat = $xlat+$ylat+$zlat; 
    
    
    //longitud            
    if(($x2 >= -90) and ($x2  <= 90)) 
      $xlong = round(abs($x2)); 
    else
      echo "x2 incorrect ".$x2."<br/>";
    
    if( ($y2 >= -90) and ($y2  <= 90) )
      $ylong = abs($y2/60); 
    else
      echo "y2 incorrect ".$ylong."<br/>"; 
    
    if(($z2 >= -90) and ($z2  <= 90)) 
      $zlong = round(abs($z2/3600),4); 
    else
      echo "z2 incorrect ".$zlong."<br/>";
    
    $decimals_long = $xlong+$ylong+$zlong; 
    
    if( $x2 < 0 ) 
    { 
      $decimals_long = $decimals_long * -1; 
    }
    if( $x1 < 0 ) 
    { 
      $decimals_lat = $decimals_lat * -1; 
    }
    
    return array('latitud' => $decimals_lat, 'longitud' => $decimals_long);
    //echo '-'.$decimals_lat.';-'.$decimals_long;
  }

  // --------------------------------------------------------------------

  /**
   * ordenar el arreglo $_FILES para manipularlo mas comodamente
   *
   * @param  array $file_post
   * @return array
   */
  public function reArrayFiles(&$file_post) {
    $file_ary = array();

    if( is_array($file_post) )
    {
      $file_count = count($file_post['name']);
      $file_keys  = array_keys($file_post);

      for( $i=0; $i<$file_count; $i++ )
      {
        foreach( $file_keys as $key )
        {
          $file_ary[$i][$key] = $file_post[$key][$i];
        }
      }
    }

    return $file_ary;
  }

  // --------------------------------------------------------------------

  /**
   * [formatoDinero description]
   * 
   * @param  [type] $monto [description]
   * @return [type]        [description]
   */
  public function formatoDinero($monto) {
    $monto = !is_null($monto) && is_numeric($monto) ? (float)$monto : 0;

    return number_format($monto, 2, ',', '.');
  }

  // --------------------------------------------------------------------

  /**
   * [comprimir_variable description]
   * 
   * @param  [type] $var [description]
   * @return [type]      [description]
   */
  public function comprimir_variable($var) {
    $ret = "";

    if( $var != "" )
    {
      $var = serialize($var);

      if( $var != "" )
      {
        $gz = @gzcompress($var);

        if( $gz != "" )
          $ret = base64_encode($gz);
      }
    }
    return $ret;

    return base64_encode(gzcompress(serialize($var)));
  }

  // --------------------------------------------------------------------

  /**
   * [mix_string description]
   * 
   * @param  [type] $string [description]
   * @return [type]         [description]
   */
  public function mix_string($string) {
    $split   = 4;    // mezclar cada $split caracteres
    $str     = str_replace("=","",$string);
    $string  = '';
    $str_tmp = explode(":",chunk_split($str,$split,":"));

    for( $i=0;$i<count($str_tmp);$i+=2 )
    {
      if( @strlen($str_tmp[$i+1]) != $split )
      {
        @$string .= $str_tmp[$i] . $str_tmp[$i+1];
      }
      else
      {
        $string .= $str_tmp[$i+1] . $str_tmp[$i];
      }
    }

    return str_replace(" ","+",$string);
  }

  // --------------------------------------------------------------------

  /**
   * [encode_link description]
   * 
   * @return [type] [description]
   */
  public function encode_link() {
    $args = func_num_args();

    if( $args == 2 )
    {
      $link = func_get_arg(0);
      $p    = func_get_arg(1);
    }
    else
      if( $args == 1 )
      {
        $p = func_get_arg(0);
      }

    $str    = $this->comprimir_variable($p);
    $string = $this->mix_string($str);

    if( isset($link) )
      return $link."?p=".$string;
    else
      return $string;
  }

  // --------------------------------------------------------------------

  /**
   * [firmaEmail description]
   *
   * @access public
   * @param  boolean $confiden
   * @return string
   */
  public function firmaEmail($confiden = TRUE) {
    if( $confiden )
    {
      $confiden = 'NOTA DE CONFIDENCIALIDAD' . PHP_EOL .
                  'Este mensaje (y sus anexos) es confidencial, esta dirigido exclusivamente a' . PHP_EOL .
                  'las personas direccionadas en el mail, puede contener informaciÛn de' . PHP_EOL .
                  'propiedad exclusiva de  S.A. y/o amparada por el secreto profesional.' . PHP_EOL .
                  'El acceso no autorizado, uso, reproducciÛn, o divulgaciÛn esta prohibido.' . PHP_EOL .
                  ' S.A. no asumir· responsabilidad ni obligaciÛn legal alguna por' . PHP_EOL .
                  'cualquier informaciÛn incorrecta o alterada contenida en este mensaje.' . PHP_EOL .
                  'Si usted ha recibido este mensaje por error, le rogamos tenga la amabilidad' . PHP_EOL .
                  'de destruirlo inmediatamente junto con todas las copias del mismo, notificando' . PHP_EOL .
                  'al remitente. No deber· utilizar, revelar, distribuir, imprimir o copiar' . PHP_EOL .
                  'este mensaje ni ninguna de sus partes si usted no es el destinatario.' . PHP_EOL .
                  'Muchas gracias.' . PHP_EOL;
    }
    else
      $confiden = '';

    $firma = PHP_EOL .
             'San Luis: Tel/Fax: (0266)' . PHP_EOL .
             'DirecciÛn: ' . PHP_EOL .
             'e-mail: info@.com.ar' . PHP_EOL .
             'p·gina: www..com.ar' . PHP_EOL;

    return $firma . PHP_EOL . $confiden;
  }

  // --------------------------------------------------------------------

}


/* End of file util.php */
/* Location: ./application/libraries/util.php */