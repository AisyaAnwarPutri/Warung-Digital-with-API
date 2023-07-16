<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Helpers{
	# Logging start
	public static function logging($param=[]){ # Parameter using key-value
		$keyForLog = ['status','url','file','title','message','line','data']; # Initial key param log, tambahkan value di baris ini jika ingin menambah parameter untuk log
		$arr = [];
		# Modify params for logging start
		foreach($keyForLog as $key => $val){
			$arr[$val] = isset($param[$val]) ? $param[$val] : ( # Cek key, apakah sudah di set
				$val=='status' ? false : ( # Jika key "status" belum di-set, isi value menjadi "false" :bool
					$val=='title' ? 'NO TITLE' : (
						$val=='message' ? 'NO MESSAGES' : '-'
					)
				)
			);
		}
		# Modify params for logging end

		$status = $arr['status']; # Status : true{program berhasil}, false{program gagal / program berhasil tapi data tidak ditemukan}
		$url    = $arr['url'];
		$file   = $arr['file'];
		$title  = $arr['title'];
		$error  = $arr['message'];
		$line   = $arr['line'];
		$data   = $arr['data'];
		$res = [
			$title => [
				'url'     => $url,
				'file'    => $file,
				'message' => $error,
				'line'    => $line,
				'data'    => $data,
			]
		];
		if($status){ # $status == true => unset key {"error","line"}
			unset($res[$title]['file'],$res[$title]['message'],$res[$title]['line']);
		}
		Log::info(json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
		return true;
	}
	# Logging end
}