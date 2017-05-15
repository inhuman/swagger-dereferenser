<?php

namespace Orbitum\SwaggerDereferenser;

use Symfony\Component\Yaml\Yaml;

class Dereferenser
{
    const REF_VAR_NAME = '$ref';
    const INDEX_NAME = 'index.yml';
    const LOCAL_DEF_SYMBOL = '#';
    private $indexPath = null;

    private $curDir;

    private function __construct($indexPath)
    {
        if (!file_exists($indexPath)) {
            throw new \InvalidArgumentException("File not found by path: " . $indexPath);
        }

        $this->indexPath = $indexPath;
    }

    /**
     * @param $indexPath
     * @return array
     */
    public static function dereferense($indexPath)
    {
        $dereferenser = new self($indexPath);

        $dereferenser->mapRefDir($indexPath);

        return $dereferenser->parse();
    }

    /**
     * @return array
     */
    private function parse()
    {
       return $this->findRef(Yaml::parse(file_get_contents($this->indexPath)));
    }


    private function mapRefDir($indexPath) {

        print_r(scandir(dirname($indexPath)));

    }


    /**
     * @param $data
     * @param null $refPath
     * @return array
     */
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

    /**
     * @param $path
     * @param $refPath
     * @return mixed
     */
    private function getRefContents($path, $refPath)
    {
        //TODO: find out how resolve ref dir
        // extra arr with indexes.ymls and paths?

        // [
        //   ['someKey?'] => 'index_dir_path'
        // ]
        //
        // scan dir and key may be an hash of file path ?


        $ref = Yaml::parse(file_get_contents($this->calculateFilePath($path, basename($this->curDir))));

        $this->curDir = dirname($this->calculateFilePath($path, $this->curDir));

        return $ref;
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

    /**
     * @param array $array
     * @param $needle
     * @return bool
     */
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