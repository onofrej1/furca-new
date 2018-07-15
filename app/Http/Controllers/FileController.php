<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function getFiles()
    {
      $path = request('path', 'obrazky');
      $directory = public_path($path);
      $files = $this->scanAllDir($directory);

      return response()->json($files);
    }

    private function scanAllDir($dir) {
      $files = [];
      foreach(scandir($dir) as $filename) {
        $file = [];
        if ($filename[0] === '.') continue;
        $path = $dir . '/' . $filename;

        $file['name'][] = basename($path);
        $file['path'][] = substr($path, strrpos($path, 'public/')+7);
        if (is_dir($path)) {
          $file['type'][] = 'dir';
          $file['children'][] = $this->scanAllDir($path);
        } else {
          $file['isImage'] = $this->isImage($path);
          $file['portrait'] = $this->isPortrait($path);
          $file['type'][] = 'file';
          //$result['children'] = [];
        }
        $files[] = $file;
      }
      return $files;
    }

    private function isPortrait($image)
    {
      list($width, $height) = getimagesize($image);

      return $width < $height;
    }

    private function isImage($path)
    {
        $a = getimagesize($path);
        $imageType = $a[2];

        return in_array($imageType , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP));
    }
}
