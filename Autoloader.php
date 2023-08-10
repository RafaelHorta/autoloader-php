<?php

class Autoloader {

    private const DIRSNAME = [
        # Lista de directorios en caso de no usar namespaces
    ];

    static public function register() : void {
        spl_autoload_register(['Autoloader', 'autoload'], true, true);
    }

    static private function autoload($className) : void {
        $filepath = __DIR__.DIRECTORY_SEPARATOR. str_replace('\\', '/', $className) . '.php';
        $list_dirsname = self::DIRSNAME;

        if (file_exists($filepath)) {
            include_once $filepath;

        } elseif (!empty($list_dirsname)) {
            $filename = $className . '.php';

            foreach ($list_dirsname as $dirname) {
                if (self::load_file($dirname, $filename)) {
                    break;
                }
            }
        } else {
            throw new Exception("File '{$className}.php' not found");
        }
    }

    static private function load_file($dirname, $filename) : bool {
        foreach (scandir(__DIR__.DIRECTORY_SEPARATOR.$dirname) as $file) {
            $filepath = realpath($dirname.DIRECTORY_SEPARATOR.$file);

            if (is_file($filepath) && $file == $filename) {
                require_once $filepath;

                return true;

            } elseif ($file != '.' && $file != '..') {
                self::load_file($filepath, $filename);
            }
        }

        return false;
    }
}
