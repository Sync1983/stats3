<?php

    /**
     * SabreAMF_InputStream 
     * 
     * This is the InputStream class. You construct it with binary data and it can read doubles, longs, ints, bytes, etc. while maintaining the cursor position
     * 
     * @package SabreAMF 
     * @version $Id: InputStream.php 233 2009-06-27 23:10:34Z evertpot $
     * @copyright Copyright (C) 2006-2009 Rooftop Solutions. All rights reserved.
     * @author Evert Pot (http://www.rooftopsolutions.nl) 
     * @licence http://www.freebsd.org/copyright/license.html  BSD License (4 Clause) 
     */
    class SabreAMF_InputStream {

        /**
         * cursor 
         * 
         * @var int 
         */
        private $cursor = 0;
        /**
         * rawData 
         * 
         * @var string
         */
        private $rawData = '';
        private $data_lenght;

        private $isBigEndian;


        /**
         * __construct 
         * 
         * @param string $data 
         * @return void
         */
        public function __construct($data) {

            //Rawdata has to be a string
            if (!is_string($data)) {
                throw new Exception('Inputdata is not of type String');
                return false;
            }
            $this->rawData = $data;
            $this->data_lenght = strlen($data);
            
            $testEndian = unpack("C*",pack("S*",256));
            $this->isBigEndian = !$testEndian[1]==1;
        }

        private function _checkCursor()
        {
          if($this->cursor > $this->data_lenght)
            throw new Exception('Buffer underrun at position: '. $this->cursor . '. Data lenght: ' . $this->data_lenght);
        }

        /**
         * &readBuffer 
         * 
         * @param int $length 
         * @return mixed 
         */
        public function readBuffer($length) 
        {
          $data = substr($this->rawData, $this->cursor, $length);
          $this->cursor += $length;
          $this->_checkCursor();
          return $data;
        }

        /**
         * readByte 
         * 
         * @return int 
         */
        public function readByte() 
        {
          $this->_checkCursor();
          return ord($this->rawData[$this->cursor++]);
        }

        /**
         * readInt 
         * 
         * @return int 
         */
        public function readInt() 
        {
          $this->_checkCursor();
		      return ((ord($this->rawData[$this->cursor++]) << 8) | ord($this->rawData[$this->cursor++]));
        }


        /**
         * readDouble 
         * 
         * @return float 
         */
        public function readDouble() 
        {
          $double = $this->readBuffer(8);
          if ($this->isBigEndian)
            $double = strrev($double);
          $double = unpack("d", $double);

          $this->_checkCursor();
          return $double[1];
        }

        /**
         * readLong 
         * 
         * @return int 
         */
        public function readLong() 
        {
          $data = (
            (ord($this->rawData[$this->cursor++]) << 24) |
            (ord($this->rawData[$this->cursor++]) << 16) |
            (ord($this->rawData[$this->cursor++]) << 8) |
            ord($this->rawData[$this->cursor++])
          );
          $this->_checkCursor();
          return $data;
        }

        /**
         * readInt24 
         * 
         * return int 
         */
        public function readInt24() 
        {
          $block = chr(0) . $this->readBuffer(3);
          $long = unpack("N",$block);
          return $long[1];
        }

    }



