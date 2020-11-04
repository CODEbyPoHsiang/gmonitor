<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gmonitor extends Model
{
    use HasFactory;

    protected $table = 'sdwan';
  
    protected $fillable = 
    [
      'ID',
      'sn',
      'Name',
      'Seq',
      'State',
      'Latency',
      'Jitter',
      'PacketSend',
      'PacketRecv',
      'LinkBandwidthIn',
      'BandwidthOut',
      'BandwidthBi',
      'm_time',
      'm_time',
  ];

  //   const CREATED_AT = 'm_time';
  // // 修改時間戳記的value值格式
  //   protected $casts = [
  //     'm_time' => 'datetime:Y-m-d H:i:s',
  // ];
   
    // public function toArray()
    //   {
    //     return[
    //       'os'=>(if($this->os),
    //       ];
    //   }
}
