<?php
namespace App\Http\Controllers\Api;
use App\Services\UploadTmpService;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UploadTmpController extends BaseController
{
    private $upload_tmp_service;
    public function __construct(UploadTmpService $upload_tmp_service)
    {
        $this->upload_tmp_service = $upload_tmp_service;
    }

    public function uploadImageSN(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $data_response = $this->upload_tmp_service->create($request);
       // dd($data_response);
        if($data_response['success']==true)
           {
                return $data_response['data']['url'];
           }//return $this->sendResponse('Berhasil Tersimpan',$data_response['data']);
        else
            return $this->sendError(__('label.failed_uploaded'),[]);
    }


    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        $data_response = $this->upload_tmp_service->create($request);

        if($data_response['success']==true)
           {
            return $this->sendResponse('Berhasil Tersimpan',$data_response['data']);
           }//return $this->sendResponse('Berhasil Tersimpan',$data_response['data']);
        else
            return $this->sendError('Gagal Terkirim',[]);
    }

    public function removeImage(Request $request)
    {
        $explode = explode('/',$request->src);
        $file_name = end($explode);
        $data_response = $this->upload_tmp_service->remove($file_name);
        if($data_response['success']==true)
            return $this->sendResponse('Berhasil Dihapus',$data_response['data']);
        else
            return $this->sendError('Gagal hapus file',[]);
    }


    public function removeFile($file_name)
    {
        $data_response = $this->upload_tmp_service->remove($file_name);
        if($data_response['success']==true)
            return $this->sendResponse('Berhasil Dihapus',$data_response['data']);
        else
            return $this->sendError('Gagal hapus file',[]);
    }

    public function update(Request $request)
    {
        $data_response = $this->upload_tmp_service->update((object) $request->input());
        if($data_response['success']==true)
            return $this->sendResponse('Berhasil Tersimpan',$data_response['data']);
        else
            return $this->sendError('Gagal Terkirim',[]);
    }

    public function delete($id)
    {
        $data_response = $this->upload_tmp_service->delete($id);
        if($data_response['success']==true)
            return $this->sendResponse('Berhasil Dihapus',$data_response['data']);
        else
            return $this->sendError('Gagal Terkirim',[]);
    }

    public function find($id)
    {
        $data_response = $this->upload_tmp_service->find($id);
        if($data_response['success']==true)
            return $this->sendResponse('ditemukan',$data_response['data']);
        else
            return $this->sendError('Gagal Terkirim',[]);
    }

    public function all()
    {
        $data_response = $this->upload_tmp_service->all();
        if($data_response['success']==true)
            return $this->sendResponse('ditemukan',$data_response['data']);
        else
            return $this->sendError('Gagal Terkirim',[]);
    }


    public function uploadFile(Request $request)
    {
        $upload_tmp_service_validation = $this->upload_tmp_service->getValidation($request->m_module_id,$request->name) ;
      //  dd( $upload_tmp_service_validation);
        if($upload_tmp_service_validation['success']==false)
            { return $this->sendError('not found validation',[]);}

        $validation = $upload_tmp_service_validation['data']['validation'];

        $request->validate([
            'file' => $validation,
        ]);
        $data_response = $this->upload_tmp_service->createFile($request);
      //  dd($data_response);
        if($data_response['success']==true)
           {
               // unset($data_response['data']['url']);
                return $this->sendResponse('Berhasil Tersimpan',$data_response['data']);
           }//return $this->sendResponse('Berhasil Tersimpan',$data_response['data']);
        else
            return $this->sendError('Gagal Terkirim',[]);
    }



    public function uploadExternal(Request $request)
    {
        $type = $request->name;

        if($type=="file")
        {
          $extension =  strtolower($request->file('file_src_file')->getClientOriginalExtension());
            if(in_array($extension,['mp4','webm']))
               $type="video";
            else if(in_array($extension,['aac','mp3','amr']))
               $type="audio";
            else if(in_array($extension,['pdf']))
               $type="pdf";
            else if(in_array($extension,['png','gif','jpeg','jpg']))
               $type="image";
        }

        if($type=="image")
        {
            $max_size = 102400;
            $rules = array(
            'file_src_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:'.$max_size,
            );

        }
        else if($type=="pdf")
        {
            $max_size = 102400;
            $rules = array(
                'file_src_file' => 'required|mimes:pdf|max:'.$max_size,
                );
        }
        else if($type=="audio")
        {
            $max_size = 102400;
            $rules = array(
                'file_src_file' => 'required|mimes:mp3,aac|max:'.$max_size,
                );
        }
        else if($type=="video")
        {
            $max_size = 102400 ;
            $rules = array(
                'file_src_file' => 'required|mimes:mp4|max:'.$max_size,
                );
        }

        $message = array(
            'file_src_file.required'=>'File wajib diunggah',
            'file_src_file.max'=>'Melebihi maksimal kapasitas maksimal file '.($max_size/1000).' Mb',
            'file_src_file.mimes'=> 'unggahan tidak sesuai dengan jenis berkas yang ditentukan'
        );

        $validator = Validator::make($request->all(), $rules,$message);

        if ($validator->fails()) {
                //return $this->sendError($validator->errors()->toArray()['file_src_file'][0]);
             //   return $this->sendResponse('gagal',$data_response['data'])
             return ['success'=>false,'message'=>$validator->errors()->toArray()['file_src_file'][0]];
        }

        $data_response = $this->upload_tmp_service->createFileExternal($request);

        if($data_response['success']==true)
           {
                //unset($data_response['data']['url']);
                return $this->sendResponse('Berhasil Tersimpan',$data_response['data']);
           }//return $this->sendResponse('Berhasil Tersimpan',$data_response['data']);
        else
            return $this->sendError('Gagal Terkirim',[]);
    }


    public function uploadCroppedImageFromBlob(Request $request)
    {

        $data_response = $this->upload_tmp_service->createImageFromBlob($request);
        if($data_response['success']==true)
           {
               // unset($data_response['data']['url']);
                return $this->sendResponse('Berhasil Tersimpan',$data_response['data']);
           }//return $this->sendResponse('Berhasil Tersimpan',$data_response['data']);
        else
          // dd($data_response);
            return $this->sendError('Gagal Terkirim',[]);
    }
}
