<?php
/**
 * Created by Merkury (Víctor Moreno)
 * Date: 10/02/2016
 * Time: 21:29
 */

namespace App\SaintsburysScrapper\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use App\SaintsburysScrapper\Classes\FruitScrapper;

class ScrapCommand extends Command
{

    /**
     * @var FruitScrapper
     */
    private $fruitScrapper;

    public function __construct()
    {
        parent::__construct();
        $this->fruitScrapper =  new FruitScrapper();
    }

    public function configure(){
        $this->setName("sainsburys:scrapper:run")
            ->setDescription("Run the scrapper.")
            ->setDefinition(array(
                new InputArgument("target-url", InputArgument::OPTIONAL, "Url to scrap", null)
            ))
            ->setHelp("The <info>sainsburys:scrapper:run</info> command scraps the given url and return a json.");
    }

    public function execute(InputInterface $input, OutputInterface $output){
        $data = $input->getArgument('target-url');
        $output->writeln($this->fruitScrapper->runScrap($data));

    }

}