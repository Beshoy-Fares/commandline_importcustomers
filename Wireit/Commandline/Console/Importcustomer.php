<?php
namespace Wireit\Commandline\Console;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

use Magento\Framework\Console\Cli;
use Magento\Framework\App\Area;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Csv;
use Wireit\Commandline\Helper\Customer;



class Importcustomer extends Command
{

    const FILEFORMAT = 'Format';
    const FILENAME = 'File';

    private $customerFactory;
    protected $customerHelper;
    public $dir;


    public function __construct(
        Customer $customerHelper
    )
    {
        $this->customerHelper = $customerHelper;

        parent::__construct();

    }

    protected function configure()
    {
        $commandoptions = [
            new InputOption(self::FILEFORMAT, null, InputOption::VALUE_REQUIRED, 'FILE FORMAT'),
            new InputOption(self::FILENAME, null, InputOption::VALUE_REQUIRED, 'File Name')
        ];

        $this->setName('customer:import');
        $this->setDescription('Demo command line');
        $this->setDefinition($commandoptions);

        parent::configure();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
       if($input->getOption(self::FILEFORMAT) and $input->getOption(self::FILENAME))
       {
           if ($input->getOption(self::FILEFORMAT) == 'sample-csv')
                $this->importCsvFile($input->getOption(self::FILENAME));
           elseif ($input->getOption(self::FILEFORMAT) == 'sample-json')
                $this->importJsonFile($input->getOption(self::FILENAME));
       }
    }

    private function importCsvFile($filename)
    {
        $mediaPath = BP.'\pub\\';
        $file = $mediaPath.$filename;
        $csvData = fopen($file, 'r');
        $data = [];
        while ($row = fgetcsv($csvData)) {
            $data[] = $row;
        }
        for ($x = 1; $x < count($data); $x++)
            $this->setCSVOptions($data[$x]);
    }

    public function setCSVOptions($data){
        $customerData = [
            'email'         => $data[2],
            'firstname'     => $data[0],
            'lastname'      => $data[1]
        ];
         $this->customerHelper->newCustomer($customerData);
    }

    private function importJsonFile($filename)
    {
        $mediaPath = BP.'\pub\\';
        $file = $mediaPath.$filename;
        $jsonData = file_get_contents($file);
        $json_Data_decoded = json_decode($jsonData, true);
        $data = [];
        foreach ($json_Data_decoded as $row)
           $data[] = $row;
        for ($x = 0; $x < count($data); $x++)
            $this->setJSONOptions($data[$x]);
    }

    public function setJSONOptions($data){
        $customerData = [
            'email'         => $data['emailaddress'],
            'firstname'     => $data['fname'],
            'lastname'      => $data['lname']
        ];
        $this->customerHelper->newCustomer($customerData);
    }
}
