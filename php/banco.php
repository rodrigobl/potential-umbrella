<?php

class BaseDados{
	protected $host;
	protected $dbname;
	protected $user;
	protected $pass;

	protected $db;

	protected $tabelas;
	protected $nome_tabela;
	protected $nome_tabelas_joins;
	protected $joins;
	protected $retorno_select;

	//Construtor que inicializa a conexão com o banco de dados e informações básicas para o objeto
	public function __construct($tabela)
	{
		include("acesso.php");

		//lista de tabelas utilizadas no banco
		$this->tabelas = array(
			"ordemservico",
			"exame",
			"medico",
			"paciente",
			"postocoleta",
			"ordemservicoexame"
		);

		$this->nome_tabela = $this->tabelas[$tabela];

		$this->host = $host_conf;
		$this->dbname = $dbname_conf;
		$this->user = $user_conf;
		$this->pass = $pass_conf;

		try{
			//Cria uma nova conexão utilizando os dados pré-inseridos no arquivo "acesso.php"
			$this->db = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname.";charset=utf8", $this->user, $this->pass);
		}catch(PDOException $Exception){
			throw new MyDatabaseException( $Exception->getMessage( ) , (int)$Exception->getCode( ) );
		}

		$this->nome_tabelas_joins = array();

		//cria uma lista das conexoes possiveis de cada tabela, facilitando o uso futuro
		$this->joins = array(
			"ordemservico" => array(
								"paciente" => "idPaciente",
								"postocoleta" => "idPostoColeta",
								"medico" => "idMedico"
								),

			"ordemservicoexame" => array(
									"ordemservico" => "idOrdemServico",
									"exame" => "idExame"
									)
		);

		//retorno padrão utilizado no select
		$this->retorno_select = "*";

	}

	//Desconectar do banco de dados
	public function Desconectar()
	{
		$this->db = NULL;
	}

	//Método para realizar uma busca
	public function Procurar($busca = NULL, $dados = NULL)
	{
		$sql = "SELECT ".$this->retorno_select." FROM ".$this->nome_tabela." t";

		//realiza os joins pre-registrados
		$n = 'a';
		foreach($this->nome_tabelas_joins as $tab){
			$sql .= " LEFT JOIN ".$tab." $n ON $n.".$this->joins[$this->nome_tabela][$tab]." = t.".$this->joins[$this->nome_tabela][$tab];
			$n++;
		}

		if(isset($busca)){
			$sql.=" WHERE ".$busca;
		}

		$sql .=" ORDER BY 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute($dados);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	//Método para realizar uma busca por id
	//Parametros:
	//	$id:array com o nome da coluna e id a ser buscado nas posicoes 0 e 1
	//	$unico: booleano para separar buscas com 1 resultado ou multiplos
	public function ProcurarPorId($id, $unico = TRUE)
	{
		$sql = "SELECT ".$this->retorno_select." FROM ".$this->nome_tabela." t ";

		//realiza os joins pre-registrados
		$n = 'a';
		foreach($this->nome_tabelas_joins as $tab){
			$sql .= " LEFT JOIN ".$tab." $n ON $n.".$this->joins[$this->nome_tabela][$tab]." = t.".$this->joins[$this->nome_tabela][$tab];
			$n++;
		}

		$sql .= " WHERE ".$id[0]." = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->execute(array($id[1]));

		if($unico)
			return $stmt->fetch(PDO::FETCH_ASSOC);
		else
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	//Insere na tabela, o formato é gerado pela classe Registro em registro.php
	public function Inserir($registro)
	{
		$sqlA = "INSERT INTO ".$this->nome_tabela." (";

		$sqlB = ") VALUES (";

		$valores = array();

		//constroe a query baseado nos valores encontrados no registro (que são as colunas e informações)
		foreach($registro as $chave => $valor){
			if($chave != "ex"){
				$sqlA .= " ".$chave.",";

				$sqlB .= "?,";

				$valores[] = $valor;
			}
		}

		$sqlA = rtrim($sqlA, ",");
		$sqlB = rtrim($sqlB, ",");

		$sql = $sqlA.$sqlB.")";

		$stmt = $this->db->prepare($sql);

		$stmt->execute($valores);

		return $this->db->lastInsertId();
	}

	//Método para realizar uma inserção específica de múltiplos exames (ordemservicoexame)
	//Parametros:
	//	$id:array com o nome da coluna e id a ser buscado nas posicoes 0 e 1
	//	$exames: um array com os ids dos exames a serem adicionados
	public function InserirExames($id, $exames)
	{
		foreach($exames as $ex){
			$this->Inserir(array($id[0] => $id[1], "idExame" => $ex));
		}
	}

	//Método para remover a partir do id
	//Parametros:
	//	$id:array com o nome da coluna e id a ser buscado nas posicoes 0 e 1
	public function Remover($id)
	{
		$sql = "DELETE FROM ".$this->nome_tabela." WHERE ".$id[0]." = ?";

		$stmt = $this->db->prepare($sql);

		$stmt->execute([$id[1]]);
	}

	//Método para atualizar
	//Parametros:
	//	$registro:array com a estrutura utilizada para criar as querys
	//	$id:array com o nome da coluna e id a ser buscado nas posicoes 0 e 1
	public function Atualizar($registro, $id)
	{
		$sql = "UPDATE ".$this->nome_tabela." SET ";

		$valores = array();

		foreach($registro as $chave => $valor){
			if($chave != "ex"){
				$sql .= " ".$chave." = ?,";

				$valores[] = $valor;
			}
		}

		$sql = rtrim($sql, ",");

		$sql .= " WHERE ".$id[0]." = ".$id[1];

		$stmt = $this->db->prepare($sql);

		$stmt->execute($valores);
	}

	//Método para adicionar joins ao atributo
	//Parametros:
	//	$tab:array com strings de cada tabela
	public function Joins($tab)
	{
		foreach($tab as $t)
			$this->nome_tabelas_joins[] = $t;
	}

	//Método para especificar o retorno utilizado no select
	//Parametros:
	//	$r:uma string que será concatenada no lugar de *
	public function Retornar($r)
	{
		$this->retorno_select = $r;
	}

	//Método para configurar o select da tabela adequadamente se baseando na tabela atual
	public function ConfigurarSelectTabela()
	{
		switch($this->nome_tabela){
			case "ordemservico":
				$this->Joins(array("paciente","medico","postocoleta"));
				$this->Retornar("t.idOrdemServico, t.data, CONCAT('(', t.idPaciente, ') ',a.nome) as idPaciente, t.convenio, CONCAT('(', t.idPostoColeta, ') ', c.descricao) as idPostoColeta, CONCAT('(', t.idMedico, ') ',b.nome) as idMedico");
			break;

			case "ordemservicoexame":
				$this->Joins(array("exame"));
				$this->Retornar("t.idExame as id, a.descricao as legenda");
			break;
		}
		
	}

	//Método para alterar adequadamente a tabela a partir da chave primaria
	public function CarregarTabelaPorChave($chave)
	{
		switch($chave)
		{
			case "idPaciente":
				$this->nome_tabela= $this->tabelas[3];
				$this->Retornar("idPaciente, nome as legenda");
				return;

			case "idPostoColeta":
				$this->nome_tabela= $this->tabelas[4];
				$this->Retornar("idPostoColeta, descricao as legenda");
				return;

			case "idMedico":
				$this->nome_tabela= $this->tabelas[2];
				$this->Retornar("idMedico, nome as legenda");
				return;

			case "idExame":
				$this->nome_tabela= $this->tabelas[1];
				$this->Retornar("idExame, descricao as legenda");
				return;

			case "idOrdemServico":
				$this->nome_tabela= $this->tabelas[0];
				$this->Retornar("idOrdemServico, data as legenda");
				return;

			//Chave Primária idOrdemServicoExame não existe, porém mantém o padrão utilizado
			case "idOrdemServicoExame":
				$this->nome_tabela= $this->tabelas[5];
				$this->Retornar("t.idExame, a.descricao as legenda");
				$this->Joins(array("exame"));
			break;
		}
	}

	public function ExecutarComando($sql)
	{
		return $this->db->exec($sql);
	}
}

?>