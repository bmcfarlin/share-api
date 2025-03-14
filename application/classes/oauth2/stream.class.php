<?php

namespace OAuth2;

use \Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{

  private $stream;

  public const FSTAT_MODE_S_IFIFO = 0010000;


  function __construct($stream){
    $this->stream = $stream;
  }

  /**
   * Reads all data from the stream into a string, from the beginning to end.
   *
   * This method MUST attempt to seek to the beginning of the stream before
   * reading data and read the stream until the end is reached.
   *
   * Warning: This could attempt to load a large amount of data into memory.
   *
   * This method MUST NOT raise an exception in order to conform with PHP's
   * string casting operations.
   *
   * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
   * @return string
   */
  public function __toString(): string
  {
    $result = '';
    if($this->stream){
      $this->rewind();
      $result = $this->getContents();
    }
    return $result;
  }

  /**
   * Closes the stream and any underlying resources.
   *
   * @return void
   */
  public function close(): void
  {
    if($this->stream){
      if($this->isPipe()){
        pclose($this->stream);
      }else{
        fclose($this->stream);
      }
    }
    $this->detach();
  }

  /**
   * Separates any underlying resources from the stream.
   *
   * After the stream has been detached, the stream is in an unusable state.
   *
   * @return resource|null Underlying PHP stream, if any
   */
  public function detach()
  {
    $this->stream = null;
  }

  /**
   * Get the size of the stream if known.
   *
   * @return int|null Returns the size in bytes if known, or null if unknown.
   */
  public function getSize(): ?int
  {
    $result = 0;
    if($this->stream){
      $values = fstat($this->stream);
      $result = $values['size'];
    } 
    return $result;
  }

  /**
   * Returns the current position of the file read/write pointer
   *
   * @return int Position of the file pointer
   * @throws \RuntimeException on error.
   */
  public function tell(): int
  {
    $result = 0;
    if($this->stream){
      $value = ftell($this->stream);
      if($value){
        $result = $value;
      }
    }
    return $result;
  }

  /**
   * Returns true if the stream is at the end of the stream.
   *
   * @return bool
   */
  public function eof(): bool
  {
    $result = true;
    if($this->stream){
      $result = feof($this->stream);
    }
    return $result;
  }

  /**
   * Returns whether or not the stream is seekable.
   *
   * @return bool
   */
  public function isSeekable(): bool
  {
    $result = true;
    if($this->stream){
      $result = feof($this->stream);
    }
    return $result;
  }

  private function isPipe(): bool
  {
    $result = false;
    if($this->stream){
      $values = fstat($this->stream);
      $mode = $values['mode'];
      $result = (($mode & self::FSTAT_MODE_S_IFIFO) !== 0);
    } 
    return $result;
  }

  /**
   * Seek to a position in the stream.
   *
   * @link http://www.php.net/manual/en/function.fseek.php
   * @param int $offset Stream offset
   * @param int $whence Specifies how the cursor position will be calculated
   *     based on the seek offset. Valid values are identical to the built-in
   *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
   *     offset bytes SEEK_CUR: Set position to current location plus offset
   *     SEEK_END: Set position to end-of-stream plus offset.
   * @throws \RuntimeException on failure.
   */
  public function seek(int $offset, int $whence = SEEK_SET): void
  {
  }

  /**
   * Seek to the beginning of the stream.
   *
   * If the stream is not seekable, this method will raise an exception;
   * otherwise, it will perform a seek(0).
   *
   * @see seek()
   * @link http://www.php.net/manual/en/function.fseek.php
   * @throws \RuntimeException on failure.
   */
  public function rewind(): void
  {
    if($this->stream){
      if($this->isSeekable()){
        try{
          rewind($this->stream);
        }catch(Exception $exc){
          trace($exc->getMessage());
        }
      }
    }
  }

  /**
   * Returns whether or not the stream is writable.
   *
   * @return bool
   */
  public function isWritable(): bool
  {
    $result = false;
    if($this->stream){
      $mode = $this->getMetadata('mode');
      if (is_string($mode) && (strstr($mode, 'w') !== false || strstr($mode, '+') !== false)) {
        $result = true;
      }      
    }
    return $result;
  }

  /**
   * Write data to the stream.
   *
   * @param string $string The string that is to be written.
   * @return int Returns the number of bytes written to the stream.
   * @throws \RuntimeException on failure.
   */
  public function write(string $string): int
  {
    $result = 0;
    if($this->stream){
      if($this->isWritable()){
        $value = fwrite($stream, $string);
        if($value){
          $this->size = null;
          $result = $value;
        }
      }
    }
    return $result;
  }

  /**
   * Returns whether or not the stream is readable.
   *
   * @return bool
   */
  public function isReadable(): bool
  {
    $result = false;
    if($this->stream){
      $mode = $this->getMetadata('mode');
      if (is_string($mode) && (strstr($mode, 'r') !== false || strstr($mode, '+') !== false)) {
        $result = true;
      }      
    }
    return $result;
  }

  /**
   * Read data from the stream.
   *
   * @param int $length Read up to $length bytes from the object and return
   *     them. Fewer than $length bytes may be returned if underlying stream
   *     call returns fewer bytes.
   * @return string Returns the data read from the stream, or an empty string
   *     if no bytes are available.
   * @throws \RuntimeException if an error occurs.
   */
  public function read(int $length): string
  {
    $result = '';
    if($this->stream){
      if($this->isReadable()){
        $value = fread($this->stream, $length);
      }
    }
    return $result;
  }

  /**
   * Returns the remaining contents in a string
   *
   * @return string
   * @throws \RuntimeException if unable to read or an error occurs while
   *     reading.
   */
  public function getContents(): string
  {
    $result = '';
    if($this->stream){
      $value = stream_get_contents($this->value);
      if(is_string($value)){
        $result = $value;
      }
    }
    return $result;
  }

  /**
   * Get stream metadata as an associative array or retrieve a specific key.
   *
   * The keys returned are identical to the keys returned from PHP's
   * stream_get_meta_data() function.
   *
   * @link http://php.net/manual/en/function.stream-get-meta-data.php
   * @param string|null $key Specific metadata to retrieve.
   * @return array|mixed|null Returns an associative array if no key is
   *     provided. Returns a specific key value if a key is provided and the
   *     value is found, or null if the key is not found.
   */
  public function getMetadata(?string $key = null)
  {
    $result = null;
    if($this->stream){
      $meta = stream_get_meta_data($this->stream);
      if(empty($key)){
        $result = $meta;
      }else{
        if(array_key_exists($key, $meta)){
          $result = $meta[$key];
        }
      }
    }
    return $result;
  }

}
