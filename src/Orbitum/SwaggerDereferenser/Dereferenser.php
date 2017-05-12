<?php

namespace Orbitum\SwaggerDereferenser;

use Symfony\Component\Yaml\Yaml;

class Dereferenser
{
    const REF_VAR_NAME = '$ref';
    const INDEX_NAME = 'index.yml';
    const LOCAL_DEF_SYMBOL = '#';
    private $indexPath;

    private function __construct($indexPath)
    {
        if (!file_exists($indexPath)) {
            throw new \InvalidArgumentException("File not found by path: " . $indexPath);
        }

        $this->indexPath = $indexPath;
    }

    public static function dereferense($indexPath)
    {
        $dereferenser = new self($indexPath);
        return $dereferenser->parse();
    }


    private function parse()
    {
       return $this->findRef(Yaml::parse(file_get_contents($this->indexPath)));
    }

    private function findRef($data, $refPath = null)
    {
        $newData = [];

        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data), \RecursiveIteratorIterator::CATCH_GET_CHILD) as $key => $value) {
            if (is_array($value) && array_key_exists(self::REF_VAR_NAME, $value)) {

                if (!(strpos($value[self::REF_VAR_NAME], self::LOCAL_DEF_SYMBOL) === 0)) {

                    $newData[$key] = $this->getRefContents($value[self::REF_VAR_NAME], $refPath);
                }

            } elseif (is_array($value)) {
                $newData[$key] = $this->findRef($value, $key);
            } else {
                $newData[$key] = $value;
            }
        }

        if ($this->recursiveFind($newData, self::REF_VAR_NAME)) {
            $newData = $this->findRef($newData);
        }
        return $newData;
    }

    private function getRefContents($path, $refPath)
    {
        return Yaml::parse(file_get_contents($this->calculateFilePath($path, $refPath)));
    }

    /**
     * @param $relPath
     * @param $subFolder
     * @return string
     */
    public function calculateFilePath($relPath, $subFolder)
    {
        $path = dirname($this->indexPath);

        if (!is_null($subFolder) && (strpos($relPath, self::INDEX_NAME) == 0)) {
            $path .= '/' . $subFolder;
        }

        $path .= trim($relPath, ".");

        return $path;
    }

    public function recursiveFind(array $array, $needle)
    {
        $iterator = new \RecursiveArrayIterator($array);
        $recursive = new \RecursiveIteratorIterator(
            $iterator,
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($recursive as $key => $value) {
            if ($key === $needle) {
                return true;
            }
        }
        return false;
    }




}