<?php

/**
 * PHP IDS
 *
 * Requirements: PHP5, SimpleXML, MultiByte Extension (optional)
 *
 * Copyright (c) 2007 PHPIDS (http://php-ids.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

/**
 * PHPIDS specific utility class to convert charsets manually
 *
 * @author      christ1an <ch0012@gmail.com>
 * @version     $Id$
 */
class IDS_Converter {

    /**
     * Converts listed UTF-7 tags to UTF-8
     *
     * @param   string  $data
     * @return  string  converted $data
     */
    public static function convertFromUTF7($data) {

        $schemes = array(
            '+AFwAIg'   => '"',
            '+AFw\''    => '\'',
            '+ADw'      => '<',
            '+AD4'      => '>',
            '+AFs'      => '[',
            '+AF0'      => ']',
            '+AHs'      => '{',
            '+AH0'      => '}',
            '+AFwAXA'   => '\\',
            '+ADs'      => ';',
            '+ACM'      => '#',
            '+ACY'      => '&',
            '+ACU'      => '%',
            '+ACQ'      => '$',
            '+AD0'      => '=',
            '+AGA'      => '`',
            '+ALQ'      => '�',
            '+IBg-'     => '�',
            '+IBk-'     => '�',     
            '+AHw-'     => '|',
            '+ACo-'     => '*',
            '+AF4-'     => '^'
        );
        
        foreach ($schemes as $scheme => $replacement) {         
            $data = str_replace($scheme, $replacement, $data);  
        }

        return $data;
    }

    /**
     * Checks if data needs to be urldecoded
     * 
     * @param   string  $key
     * @param   string  $value
     ? @return  string  $value
     */
    public static function convertToUrlencoded($key, $value) {
        
        #check if value is post and decode to normalize
        if(isset($_POST[$key]) && !preg_match('/%[01]\w/iDs', $_POST[$key])){
            $value = urldecode($value);
        }
        
        #check if value is get and matches nullbyte pattern
        if(isset($_GET[$key]) && preg_match('/%[01]\w/iDs', rawurlencode($_GET[$key]), $matches)){
            $value = urldecode(preg_replace('/%([01]\w)/iDs', "%25$1", rawurlencode($value)));
        }
        
        return $value;    
    }

    /**
     * Checks if data needs to be urldecoded
     * 
     * @param   string  $key
     ? @return  string  $value
     */ 
     public static function convertFromJSCharcode($value) {   

        #check if value matches typical charCode pattern
        if(preg_match('/(?:\d*(?:\s?,\s?\d+)+)/iDs', $value, $matches)){
            $converted = '';
            $charcode = explode(',', preg_replace('/\s/', '', $matches[0]));       
            foreach($charcode as $char){
                $converted .= chr($char);                               
            }       
            $value .= ' [' . $converted . '] ';
        }
        return $value;
     }
}