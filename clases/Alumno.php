<?php
require_once"accesoDatos.php";
class Alumno
{
//--------------------------------------------------------------------------------//
//--ATRIBUTOS
	private $id;
	private $nombre;
 	private $apellido;
  	private $legajo;
  	private $foto;
  	

//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//
//--GETTERS Y SETTERS
  	public function GetId()
	{
		return $this->ID;
	}
	public function GetApellido()
	{
		return $this->Apellido;
	}
	public function GetNombre()
	{
		return $this->Nombre;
	}
	public function GetLegajo()
	{
		return $this->Legajo;
	}
public function GetFoto()
	{
		return $this->Foto;
	}
	public function SetId($valor)
	{
		$this->id = $valor;
	}
	public function SetApellido($valor)
	{
		$this->Apellido = $valor;
	}
	public function SetNombre($valor)
	{
		$this->Nombre = $valor;
	}
	public function SetLegajo($valor)
	{
		$this->Legajo = $valor;
	}
	public function SetFoto($valor)
	{
		$this->Foto = $valor;
	}
	
//--------------------------------------------------------------------------------//
//--CONSTRUCTOR
	public function __construct($legajo=NULL)
	{
		if($legajo != NULL){
			$obj = Alumno::TraerUnaPersona($legajo);
			
			$this->apellido = $obj->apellido;
			$this->nombre = $obj->nombre;
			$this->legajo = $legajo;
			$this->Foto = $obj->foto;
		
		}
	}

//--------------------------------------------------------------------------------//
//--TOSTRING	
  	public function ToString()
	{
	  	return $this->apellido."-".$this->nombre."-".$this->legajo."-".$this->foto;
	}
//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//
//--METODO DE CLASE
	public static function TraerUnAlumno($idParametro) 
	{	


		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from alumno where id =:id");
		$consulta->bindValue(':id', $idParametro, PDO::PARAM_INT);
		$consulta->execute();
		$personaBuscada= $consulta->fetchObject('Alumno');
		return $personaBuscada;	
					
	}
	
	public static function TraerTodasLasPersonas()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from alumno");
		$consulta->execute();			
		$arrPersonas= $consulta->fetchAll(PDO::FETCH_CLASS, "Alumno");	
		return $arrPersonas;
	}
	
	public static function Borrar($idParametro)
	{	
		$alumno = Alumno::TraerUnAlumno($idParametro);
		$legajo = $alumno->GetLegajo();
	    unlink("./fotos/$legajo");
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("delete from alumno	WHERE id=:id");	
		$consulta->bindValue(':id',$idParametro, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
		
	}
	
	public static function Modificar($alumno)
	{

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update alumno 
				set Nombre=:nombre,
				Apellido=:apellido
				WHERE id=:id");
			$consulta->bindValue(':id',$alumno->GetId(), PDO::PARAM_INT);
			$consulta->bindValue(':nombre',$alumno->GetNombre(), PDO::PARAM_STR);
			$consulta->bindValue(':apellido', $alumno->GetApellido(), PDO::PARAM_STR);
			return $consulta->execute();
	}

//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//

	public static function Insertar($alumno)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into alumno (nombre,apellido,legajo,foto)values(:nombre,:apellido,:legajo,:foto)");
				$consulta->bindValue(':nombre',$alumno->Nombre, PDO::PARAM_STR);
				$consulta->bindValue(':apellido', $alumno->Apellido, PDO::PARAM_STR);
				$consulta->bindValue(':legajo', $alumno->Legajo, PDO::PARAM_INT);
				$consulta->bindValue(':foto', $alumno->Foto, PDO::PARAM_STR);

			$consulta->execute();		
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
	
				
	}	
//--------------------------------------------------------------------------------//

}