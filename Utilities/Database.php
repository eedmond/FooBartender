<?php
	//TODO: include Doctrine here
	require_once "../vendor/autoload.php";
	
	/***
	Creates a connection to the database.
	Common uses:
		$db = new Database();
		$arrayResults = $db->StartQuery()
			->select('*')
			->from(Database::MixedTable)
			->orderBy('something')
			->execute()
			->fetchAll();
		
		... do stuff ...
		
		$arrayResults2 = $db->StartQuery()
			->update(Database::SingleTable)
			->set('name', 'value')
			->set('otherthing', '?')
			->setParameter(0, $somevalue)
			->execute()
			->fetchAll();
			
		$arrayResults3 = $db->StartQuery()
			->insert(Database::MixedTable)
			->values(
				array(
					'key' => 'value',
					'key' => 'value',
					'key' => '?'
				)
			)
			->setParameter(0, $somevalue)
			->execute()
			->fetchAll();
		
		Where EX (incomplete):
		->where(
			$database->GetExpression()
				->eq('name','?'))
		->setParameter(0, $item)
		
	***/
	class Database
	{
		private $connection = NULL;
		private $query = NULL;
		
		const MixedTable = 'mixed';
		const SingleTable = 'single';
		const QueueTable = 'queue';
		const StationsTable = 'stations';
		
		function __construct()
		{
			$config = new \Doctrine\DBAL\Configuration();
			$connectionParams = array(
				'user' => 'pi',
				'password' => '1335e',
				'path' => '/var/www/FB.db',
				'driver' => 'pdo_sqlite',
			);
			$this->connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
		}
		
		function __destruct()
		{
			$this->connection->close();
		}
		
		public function StartQuery()
		{
			$this->query = $this->connection->createQueryBuilder();
			
			return $this->query;
		}
		
		public function GetExpression()
		{
			if (!$this->query)
				throw new Exception('Must call StartQuery before you can call Expression');
				
			return $this->query->expr();
		}
		
	}
?>