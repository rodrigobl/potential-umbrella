<?php

//Este arquivo possui as seguintes variaveis:
// $headers: headers utilizadas no html a partir dos names dos inputs carregados
// $estrutura: uma tradução entre as requisições/input names com as colunas utilizadas no banco de dados


$headers = array(
	array(
		"tabela" => "Ordem de Serviço",
		"id" => "#",
		"data" => "Data",
		"pac" => "Paciente",
		"con" => "Convênio",
		"pos" => "Posto de Coleta",
		"med" => "Médico"
	),

	array(
		"tabela" => "Exame",
		"id" => "#",
		"d" => "Descrição",
		"p" => "Preço"
	),

	array(
		"tabela" => "Médico",
		"id" => "#",
		"n" => "Nome",
		"esp" => "Especialidade"
	),

	array(
		"tabela" => "Paciente",
		"id" => "#",
		"n" => "Nome",
		"data" => "Data de Nascimento",
		"s" => "Sexo",
		"end" => "Endereço"
	),

	array(
		"tabela" => "Posto de Coleta",
		"id" => "#",
		"d" => "Descrição",
		"end" => "Endereço"
	)
);

$estrutura = array(

	array(
		"id" => "idOrdemServico",
		"data" => "data",
		"pac" => "idPaciente",
		"con" => "convenio",
		"pos" => "idPostoColeta",
		"med" => "idMedico"
	),

	array(
		"id" => "idExame",
		"d" => "descricao",
		"p" => "preco"
	),

	array(
		"id" => "idMedico",
		"n" => "nome",
		"esp" => "especialidade"
	),

	array(
		"id" => "idPaciente",
		"n" => "nome",
		"data" => "datanascimento",
		"s" => "sexo",
		"end" => "endereco"
	),

	array(
		"id" => "idPostoColeta",
		"d" => "descricao",
		"end" => "endereco"
	),

	array(
		"id" => "idOrdemServico",
		"ex" => "idExame",
		"p" => "preco"
	)

);

?>
