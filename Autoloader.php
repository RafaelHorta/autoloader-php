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

        if (file_exists($filepath)) {
            include_once $filepath;
        } else {
            $filename = $className . '.php';

            foreach (self::DIRSNAME as $dirname) {
                if (self::load_file($dirname, $filename)) {
                    break;
                }
            }
        }
    }

    static private function load_file($dirname, $filename) : bool {
        foreach (scandir($dirname) as $file) {
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
