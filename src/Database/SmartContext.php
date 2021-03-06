<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2016 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\NetteDatabaseFilters\Database;

use Nette\Caching\IStorage;
use Nette\Database\Connection;
use Nette\Database\Context;
use Nette\Database\IConventions;
use Nette\Database\IStructure;
use Zenify\NetteDatabaseFilters\Contract\FilterManagerInterface;
use Zenify\NetteDatabaseFilters\Database\Table\SmartSelection;
use Zenify\NetteDatabaseFilters\FilterManager;


final class SmartContext extends Context
{

	/**
	 * @var FilterManager
	 */
	private $filterManager;

	/**
	 * @var IStorage
	 */
	private $cacheStorage;


	public function __construct(
		Connection $connection,
		IStructure $structure,
		IConventions $conventions = NULL,
		IStorage $cacheStorage = NULL
	) {
		parent::__construct($connection, $structure, $conventions, $cacheStorage);
		$this->cacheStorage = $cacheStorage;
	}


	public function setFilterManager(FilterManagerInterface $filterManager)
	{
		$this->filterManager = $filterManager;
	}


	/**
	 * @param string $table
	 * @return SmartSelection
	 */
	public function table($table)
	{
		$selection = new SmartSelection($this->filterManager, $this, $this->getConventions(), $table, $this->cacheStorage);

		$this->filterManager->applyFilters($selection);

		return $selection;
	}

}
