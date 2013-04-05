<?php
namespace Neolao\Util;

/**
 * Class utility for string manipulation
 */
class String
{
    public static $accents = array(
        'Š'=>'S', 'š'=>'s', 'Ð'=>'D', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 
        'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 
        'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 
        'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 
        'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 
        'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 
        'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
    );



    /**
     * Convert all applicable characters to HTML entities
     * 
     * @param   string  $text       The text to convert
     * @return  string              The converted text
     */
    public static function escapeHtml($text)
    {
        return htmlentities($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Normalize a file name
     * 
     * @param   string  $name       The name
     * @return  string              The normalized name
     */
    public static function normalizeFileName($name)
    {
        $fileName = $name;

        //$fileName = str_replace('&', '-and-', $fileName);
        $fileName = strtr($name, self::$accents);
        $fileName = preg_replace('/[^a-z0-9_ -]/si', '', $fileName);
        $fileName = trim($fileName);
        $fileName = str_replace(' ', '-', $fileName);
        $fileName = preg_replace('/-+/', '-', $fileName);
        //$fileName = strtolower($fileName);

        return $fileName;
    }

    /**
     * Remove accents
     * 
     * @param   string  $text       The text
     * @return  string              The modified text
     */
    public static function removeAccents($text)
    {
        return strtr($text, self::$accents);
    }
}
