<?php

namespace Gecko\PigalleBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class FinishDeployCommand extends Command
{
	protected function configure()
	{
		$this
		->setName('deploy:finish')
		->setDescription('Execute the tasks to finish the deploy')
		->addOption(
			'with-drop',
			null,
			InputOption::VALUE_NONE,
			'If set, the task will drop the database'
		)
		->addOption(
			'with-fixtures-load',
			null,
			InputOption::VALUE_NONE,
			'If set, the task will load the fixtures'
		)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		if ($input->getOption('with-drop')) 
		{
			$this->runCommand('doctrine:schema:drop', $output, array('--force' => true));
			$this->runCommand('doctrine:schema:create', $output);
		}else 
		{
			$this->runCommand('doctrine:schema:update', $output, array('--force' => true));
		}
		
		if ($input->getOption('with-fixtures-load'))
		{
			$this->runCommand('doctrine:fixtures:load', $output);
		}

		$this->runCommand('cache:clear', $output);
		$this->runCommand('assets:install', $output);
		$this->runCommand('assetic:dump', $output);
		
		$output->writeln("<info>Deploy finished!</info>");
	}
	
	private function runCommand($commandName, OutputInterface $output, $arguments = array())
	{
		$command = $this->getApplication()->find($commandName);
		
		$arguments = array_merge($arguments, array(
			'command' => $commandName
		));
		
		$input = new ArrayInput($arguments);
		
		return $command->run($input, $output);
	}
}