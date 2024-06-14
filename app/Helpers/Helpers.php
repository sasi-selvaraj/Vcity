<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class Helpers
{
/* ------------------------------ File Upload ---------------------------*/
    public static function fileUpload($param)
    {
        if ($param->file) {
            $file = $param->file;
            $allowed_image = array("jpeg", "png", "jpg", "pdf");
            $file_compress = File::get($file);
            $file_size = strlen($file_compress);
            // $filename = time().'_'.$file->getClientOriginalName();
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '-', $file->getClientOriginalName());
            $fileDetails['size'] = Self::formatSizeUnits($file_size);
            $fileDetails['filename'] = $filename;

            /*  File extension */
            $fileDetails['extension'] = $file->getClientOriginalExtension();
            if ($fileDetails['extension'] == "pdf") {
                $ext = "pdf";
            } else if ($fileDetails['extension'] == "mp4" || $fileDetails['extension'] == "webm") {
                $ext = "video";
            } else if ($fileDetails['extension'] == "jpg" || $fileDetails['extension'] == "png" || $fileDetails['extension'] == "jpeg" || $fileDetails['extension'] == "gif") {
                $ext = "image";
            } else if ($fileDetails['extension'] == "mp3") {
                $ext = "audio";
            } else {
                $ext = "unknown";
            }
            /*  File orginal name */
            $fileDetails['orginal_name'] = $file->getClientOriginalName();

            /*  File upload location */
            $location = $param->folder_location . '/';
            if((url('/')=='http://localhost:8000')||(url('/')=='http://127.0.0.1:8000')||(url('/')=='https://vcity.stagingzar.com')){
            /*  Upload file */

            Storage::disk('public')->put($location . $filename, $file_compress);
            /*  File path */
            $fileDetails['path'] = $location . $filename;
            $fileDetails['ext'] = $ext;
             }else{
            /*  Upload file */
            $path = Storage::disk('s3')->put($param->folder_location,$param->file);
            /*  File path */
            $fileDetails['path'] =  $path;
            $fileDetails['ext'] = $ext;
            }
            return $fileDetails;
        } else {
            return false;
        }
    }

    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}