<?php

include_once("banco.php");
include_once("registro.php");

$r = new Registro($_REQUEST);

//inicio do acesso ao banco de dados
$conexao = new BaseDados($r->base);

//seleção do tipo de requisicao enviada $_REQUEST["r"]
//1 procurar
//2 inserir
//3 remover
//4 atualizar
//5 procurar por id
//6 listagem de exames
switch($r->requisicao){
	case 1:
		echo json_encode($conexao->Procurar());
	break;

	case 2:
		$r->id[1] = $conexao->Inserir($r->dados);

		//no caso da tabela ordemservico é necessario inserir todos os exames cadastrados
		if($r->base == "0" && isset($_REQUEST["ex"])){
			$conexao->Desconectar();

			$r->id[0] = "idOrdemServico";

			$conexao = new BaseDados("5");
			$conexao->InserirExames($r->id, $r->dados["ex"]);
		}
	break;

	case 3:
		$conexao->Remover($r->id);
	break;

	case 4:
		$conexao->Atualizar($r->dados, $r->id);

		//no caso da tabela ordemservico é necessário readicionar cada exame cadastrado (readicionado para apenas simplificar o código)
		if($r->base == "0" && isset($_REQUEST["ex"])){
			$conexao->Desconectar();

			$conexao = new BaseDados("5");
			$conexao->Remover($r->id);

			$conexao->InserirExames($r->id, $r->dados["ex"]);
		}

	break;

	case 5:
		$resultado = $conexao->ProcurarPorId($r->id);

		include("estrutura.php");

		//estrutura os valores adequadamente para json
		$res = array();
		foreach($estrutura[$r->base] as $key => $value){
			$res[$key] = $resultado[$value];
		}

		echo json_encode($res);
	break;

	case 6:
		$conexao->ConfigurarSelectTabela();
		$resultado = $conexao->ProcurarPorId($r->id, false);
		echo json_encode($resultado);
	break;

	case 7:
		var_dump($conexao->ExecutarComando("show tables;"));

}

$conexao->Desconectar();

?>