<?php

namespace Orbitum\SwaggerDereferenser;


use SebastianBergmann\CodeCoverage\Report\PHP;
use Symfony\Component\Yaml\Yaml;

class Dereferenser
{
    private $indexPath;

    const REF_VAR_NAME = '$ref';


    public function __construct($indexPath)
    {
        if(!file_exists($indexPath)) {
            throw new \InvalidArgumentException("File not found by path: " . $indexPath);
        }

        $this->indexPath = $indexPath;

        $this->parse();
    }


    private function parse() {

        $index = Yaml::parse(file_get_contents($this->indexPath));

        $data = $this->findRef($index);

        return $data;

    }
    
    private function findRef($data)
    {
        foreach ($data as $key => $datum) {

            if(is_array($datum) && array_key_exists(self::REF_VAR_NAME, $datum)) {

                $data[$key] = $this->getRefContents($datum[self::REF_VAR_NAME]);
            }

        }




        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data), \RecursiveIteratorIterator::CATCH_GET_CHILD) as $key => $value) {
            echo 'My node ' . print_r($key, true) . ' with value ' . print_r($value,true) . PHP_EOL;
        }

        var_dump($data);

        return $data;
    }

    private function getRefContents($path)
    {
        return Yaml::parse(file_get_contents($this->calculateFilePath($path, null)));
    }

    public function calculateFilePath($relPath, $refPath)
    {
        $path = dirname($this->indexPath);

        if(!is_null($refPath)) {
            $path .= $refPath . '/';
        }

        $path .= trim($relPath, ".");
        return  $path;
    }


}