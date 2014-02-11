<?php
/*
 *  Class to integrate with Twitter's API.
 *    Authenticated calls are done using OAuth and require access tokens for a user.
 *    API calls which do not require authentication do not require tokens (i.e. search/trends)
 *
 *  Full documentation available on github
 *    http://wiki.github.com/jmathai/twitter-async
 *
 *  @author Jaisen Mathai <jaisen@jmathai.com>
 */

class EpiSequence
{
  private $timers, $min, $max, $width = 100;
  public function __construct($timers) 
  {
    $this->timers = $timers;
    
    $min = PHP_INT_MAX;
    $max = 0;
    foreach($this->timers as $timer)
    {
      $min = min($timer['start'], $min);
      $max = max($timer['end'], $max);
    }
    $this->min = $min;
    $this->max = $max;
    $this->range = $max-$min;
    $this->step = floatval($this->range/$this->width);
  }

  public function renderAscii()
  {
    $tpl = '';
    foreach($this->timers as $timer)
     $tpl .= $this->tplAscii($timer);
    
    return $tpl;
  }

  private function tplAscii($timer)
  {
    $lpad = $rpad = 0;
    $lspace = $chars = $rspace = '';
    if($timer['start'] > $this->min)
      $lpad = intval(($timer['start'] - $this->min) / $this->step);
    if($timer['end'] < $this->max)
      $rpad = intval(($this->max - $timer['end']) / $this->step);
    $mpad = $this->width - $lpad - $rpad;
    if($lpad > 0)
      $lspace = str_repeat(' ', $lpad);
    if($mpad > 0)
      $chars = str_repeat('=', $mpad);
    if($rpad > 0)
      $rspace = str_repeat(' ', $rpad);
    
    $tpl = <<<TPL
({$timer['api']} ::  code={$timer['code']}, start={$timer['start']}, end={$timer['end']}, total={$timer['time']})
[{$lspace}{$chars}{$rspace}]

TPL;
    return $tpl;
  }
}
