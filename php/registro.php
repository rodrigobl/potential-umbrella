<?php

//Classe registro formata os dados recebidos pela requisição de forma centralizada
class Registro
{
	public $dados;
	public $id;
	public $base;
	public $busca;
	public $requisicao;

	public function __construct($request)
	{
		include_once("estrutura.php");

		$this->busca = (isset($request["b"]))? $request["b"] : NULL;
		$this->base = (isset($request["t"]))? $request["t"] : "0";

		//Utilizando a tabela como indice cria uma formatação adequada dinamicamente para a tabela
		foreach($estrutura[$this->base] as $chave => $valor){

			if($chave != "id"){
				$this->dados[$valor] = (isset($request[$chave]))? $request[$chave] :  NULL;
			}else{
				//toda chave primária é considerada como id
				//porem id é tratado de forma diferente no arquivo banco.php
				if(isset($request["id"])){
					$this->requisicao = "4";
					$this->id[] = $valor;
					$this->id[] = (isset($request[$chave]))? $request[$chave] :  NULL;
				}else{
					$this->requisicao = "2";
				}

				if(isset($request["r"]))
					$this->requisicao = $request["r"];
			}

		}

		//construção de uma lista para exames que existe somente no caso da tabela for ordemservico
		if($this->base == "0" && isset($request["ex"])){
			$this->dados["ex"] = array();

			$exs = explode("-", $request["ex"]);

			foreach($exs as $exame){
				$this->dados["ex"][]=$exame;
			}
		}
	}

}

?>