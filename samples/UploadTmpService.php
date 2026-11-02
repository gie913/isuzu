<?php
namespace App\Services;

use App\Models\TUpload;
use App\Models\TUploadTmp;
use App\Repositories\Sql\SqlUploadRepository;
use App\Repositories\Sql\SqlUploadSetupRepository;
use App\Repositories\Sql\SqlUploadTmpRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage; // For files managed by Laravel's storage system (e.g., local, S3):
use Illuminate\Support\Facades\File; //For files in the local file system that aren't managed by Laravel's storage disks:
use Ramsey\Uuid\Uuid;

//use Image;
class UploadTmpService
{
    private $upload_tmp_repository;
    private $upload_setup_repository;
    private $upload_repository;

    public function __construct(SqlUploadTmpRepository $upload_tmp_repository,
                                SqlUploadSetupRepository $upload_setup_repository,
                                SqlUploadRepository $upload_repository
                                )
    {
        $this->upload_tmp_repository = $upload_tmp_repository;
        $this->upload_setup_repository = $upload_setup_repository;
        $this->upload_repository = $upload_repository;
    }

public function create($request)
{
    $file =  $request->file('image');
    $extension = $file->getClientOriginalExtension();
    $size = $file->getSize();
    $m_module_id=$request->m_module_id;
    $name=$request->name;

    $upload_setup = $this->upload_setup_repository->findByModuleIdByName($m_module_id,$name);
    if($upload_setup)
        $path = $upload_setup->path;
    else
        return ['success'=>false,'message'=>'not found upload setup','data'=>[]];

    $id =  Uuid::uuid1()->toString();
    $t_upload_tmp_model = new TUploadTmp();
    $t_upload_tmp_model->id = $id;
    $t_upload_tmp_model->m_module_id =$m_module_id;
    $t_upload_tmp_model->m_upload_setup_id = $upload_setup->id;
    $t_upload_tmp_model->extension =$extension;
    $t_upload_tmp_model->size =$size;
    $t_upload_tmp_model->name =$name;
    $t_upload_tmp_model->path =$upload_setup->path.'/'.$id.'.'.$file->getClientOriginalExtension();
    $t_upload_tmp_model->using_thumbnail =$upload_setup->using_thumb;

    $response_file= $this->moveToFolder($file,$upload_setup,$t_upload_tmp_model);
    $t_upload_tmp_model->file_source =$response_file['url'] ;
    $save = $this->upload_tmp_repository->create($t_upload_tmp_model);

    return ['success'=>true,'message'=>'berhasil disimpan','data'=>$response_file];
}

public function createFile($request)
{
    $file =  $request->file('file');
    $extension = $file->getClientOriginalExtension();
    $size = $file->getSize();
    $m_module_id=$request->m_module_id;
    $name=$request->name;

    $upload_setup = $this->upload_setup_repository->findByModuleIdByName($m_module_id,$name);
    if($upload_setup)
        $path = $upload_setup->path;
    else
        return ['success'=>false,'message'=>'not found upload setup','data'=>[]];

    //dd( $upload_setup);
    $id =  Uuid::uuid1()->toString();
    $t_upload_tmp_model = new TUploadTmp();
    $t_upload_tmp_model->id = $id;
    $t_upload_tmp_model->m_module_id =$m_module_id;
    $t_upload_tmp_model->extension =$extension;
    $t_upload_tmp_model->size =$size;
    $t_upload_tmp_model->name =$name;
    $t_upload_tmp_model->path =$upload_setup->path.'/'.$id.'.'.$file->getClientOriginalExtension();
    $t_upload_tmp_model->using_thumbnail =$upload_setup->using_thumb;
    $t_upload_tmp_model->is_external_storage =$upload_setup->is_external_storage;
    $save = $this->upload_tmp_repository->create($t_upload_tmp_model);
    $response_file= $this->moveToFolder($file,$upload_setup,$t_upload_tmp_model);
    return ['success'=>true,'message'=>'berhasil disimpan','data'=>$response_file];
}


public function remove($file_name)
{
    $explode = explode('.',$file_name);
    $id = $explode[0];
    $tmp_upload_model = $this->upload_tmp_repository->find($id);

    if(!$tmp_upload_model)
    {
        return ['success'=>false,'message'=>'file already deleted','data'=>[]];
    }
    else
    {
        $upload_setup = $this->upload_setup_repository->findByModuleIdByName($tmp_upload_model->m_module_id,$tmp_upload_model->name);

        if(file_exists(public_path($upload_setup->path.'/'.$tmp_upload_model->id.'.'.$tmp_upload_model->extension)))
        {
            unlink(public_path($upload_setup->path.'/'.$tmp_upload_model->id.'.'.$tmp_upload_model->extension));
            $this->upload_tmp_repository->delete($id);
            return ['success'=>true,'message'=>'data has been removed','data'=>[]];
        }
        else
        {
            return ['success'=>false,'message'=>'file already deleted','data'=>[]];
        }
    }
}


private function moveToFolder($file,$setup,TUploadTmp $t_upload_tmp_model)
{
        $file_name_with_extension = $t_upload_tmp_model->id.'.'.$file->getClientOriginalExtension();

    /* if($setup->is_resized==1)
    {
    $img                     =   \ImageResize::make($file->path());

    // --------- [ Resize Image ] ---------------
    $img->resize($setup->pic_width, $setup->pic_height, function ($constraint) {
        $constraint->aspectRatio();
    })->save($setup->path.'/'.$file_name_with_extension);
    }
    else
    {
    $file->move(public_path($setup->path), $file_name_with_extension);
    }*/

    $new_path = $t_upload_tmp_model->path;

    if($setup->is_external_storage==1)
    {
    // $new_file_source =  env('AWS_ENDPOINT').'/'.env('AWS_BUCKET').'/'.$new_path;
    // $move = Storage::disk('s3')->move($t_upload_tmp_model->path,$new_path);
    //$file_s3 = $file->store($new_path, 's3');

    //$new_file_source = Storage::disk('s3')->url($file_s3); //cannot change file name
    $upload = Storage::disk('s3')->putFileAs($setup->path,$file,$file_name_with_extension,'public');
    Storage::disk('s3')->setVisibility($t_upload_tmp_model->path, 'public');
    $new_file_source =  Storage::disk('s3')->url($upload);
    }
    else
    {
        // File::move(public_path($t_upload_tmp_model->path),public_path($new_path));
        $file->move(public_path($setup->path), $file_name_with_extension);
        $new_file_source =  asset($new_path);
    }


    return ['url'=> $new_file_source,
            'file_name'=>$file_name_with_extension,
            'file_id'=>$t_upload_tmp_model->id,
            'file_extension'=>$file->getClientOriginalExtension()];
}

private function resizeImage($image_name,$source,$destination,$width,$height)
{

}

public function deleteOriImage($source)
{
    if(file_exists($source))
    {
        unlink($source);
    }
    return true;
}

public function update($request)
{
$m_module_id=$request->m_module_id;
$extension=$request->extension;
$size=$request->size;
$t_upload_tmp_model = $this->upload_tmp_repository->find($request->id);$t_upload_tmp_model->m_module_id =$m_module_id;
$t_upload_tmp_model->extension =$extension;
$t_upload_tmp_model->size =$size;
$save = $this->upload_tmp_repository->update($t_upload_tmp_model);
return ['success'=>true,'message'=>'berhasil diubah','data'=>[]];
}



public function delete($id)
{
    $delete= $this->upload_tmp_repository->delete($id);
    return ['success'=>true,'message'=>'berhasil dihapus','data'=>[]];
}

public function hardDelete($id)
{
    $delete= $this->upload_tmp_repository->hardDelete($id);
    return ['success'=>true,'message'=>'berhasil dihapus','data'=>[]];
}

public function getTableColumns()
            {
                return $this->upload_tmp_repository->getTableColumns();
            }

public function find($id)
            {
                return $this->upload_tmp_repository->find($id);
            }

public function all()
{
    return $this->upload_tmp_repository->all();
}

public function saveNUpload($id)
{
    if($id!=null)
    {
      $t_upload_tmp_model = $this->upload_tmp_repository->find($id);
      $upload_setup = $this->upload_setup_repository->findByModuleIdByName($t_upload_tmp_model->m_module_id,$t_upload_tmp_model->name);
      $new_path = str_replace('temporaries',$upload_setup->path,$t_upload_tmp_model->path);

      $t_upload_model = new TUpload();
      $t_upload_model->m_upload_setup_id = $upload_setup->id;
      $t_upload_model->m_module_id = $t_upload_tmp_model->m_module_id;
      $t_upload_model->name = $t_upload_tmp_model->name;
      $t_upload_model->type = $t_upload_tmp_model->type;
      $t_upload_model->path = $new_path;
//dd($t_upload_tmp_model);
      if($t_upload_tmp_model->is_external_storage==1)
        {
        $t_upload_model->file_source =  env('AWS_ENDPOINT').'/'.env('AWS_BUCKET').'/'.$new_path;
        $move = Storage::disk('s3')->move($t_upload_tmp_model->path,$new_path);
        }
    else
    {
        File::move(public_path($t_upload_tmp_model->path),public_path($new_path));
        $t_upload_model->file_source =  asset($new_path);
    }

      $this->upload_repository->create($t_upload_model);
    //  $this->delete($id); //temporary disambled
     // return $t_upload_model->path;
     return $t_upload_model;
  }
    else
      return null;
}


  public function getValidation($m_module_id,$name)
  {
    $upload_setup = $this->upload_setup_repository->findTypeAndMaxSizeByModuleIdByName($m_module_id,$name);
    if($upload_setup)
    {
        $max_size = $upload_setup->max_size;
        $type = $upload_setup->type;
        if($type=="pdf")
        {
            $validation= "required|mimes:pdf|max:".$max_size;
        }
        else if($type=="document"||$type=="file")
        {
            $validation= "required|mimes:pdf,docx,doc,xls,xlsx|max:".$max_size;
        }
        else if($type=="image")
        {
            $validation= "required|mimes:jpeg,png,jpg,gif,svg,webp|max:".$max_size;
        }
        else if($type=="video")
        {
            $validation= "required|mimes:mp4,3gp|max:".$max_size;
        }
        else
        {
            $validation= "required|max:".$max_size;
        }

       return ['success'=>true,'message'=>'ditemukan','data'=>['validation'=>$validation]];
    }
    else
    {
        return ['success'=>false,'message'=>'not found data','data'=>[]];
    }
  }


  public function createFileExternal($request)
  {
      $file =  $request->file('file_src_file');
      $extension = $file->getClientOriginalExtension();
      $size = $file->getSize();
      $m_module_id=$request->m_module_id;
      $name=$request->name;

      $path = "temporaries";
      $using_thumb = 0;
      $id =  Uuid::uuid1()->toString();
      $file_path =  $path.'/'.$id.'.'.$file->getClientOriginalExtension();
      $t_upload_tmp_model = new TUploadTmp();
      $t_upload_tmp_model->id = $id;
      $t_upload_tmp_model->m_module_id =$m_module_id;
      $t_upload_tmp_model->extension =$extension;
      $t_upload_tmp_model->size =$size;
      $t_upload_tmp_model->name =$name;
      $t_upload_tmp_model->path = $file_path;
      $t_upload_tmp_model->using_thumbnail = $using_thumb;
      $t_upload_tmp_model->file_source = env('AWS_ENDPOINT').'/'.env('AWS_BUCKET').'/'.$file_path;
      $save = $this->upload_tmp_repository->create($t_upload_tmp_model);
      $response_file= $this->moveToFolderExternal($file,$path,$save->id);
      return ['success'=>true,'message'=>'berhasil disimpan','data'=>$response_file];
  }

  private function moveToFolderExternal($file,$path,$file_name)
  {
      $file_name_with_extension = $file_name.'.'.$file->getClientOriginalExtension();
      $external_path = Storage::disk('s3')->putFileAs($path,$file,$file_name_with_extension,'public');
     // dd($path_);

    /* $read_s3_service =   App::make(ReadS3FileService::class);
     $read_s3 = $read_s3_service->findByPath($path)['data'];
     $expired_at = now()->addDays(7);
     $url = Storage::disk('s3')->temporaryUrl($path, $expired_at);
     $collection = new Collection();
     $collection->file_src =  $external_path;
     $collection->temporary_url =  $url;
     $collection->expired_at =  $expired_at;
     $read_s3_service->create($collection);*/

      return ['url'=> $external_path,
              'file_name'=>$file_name_with_extension,
              'file_id'=>$file_name];
  }

 public function saveToUploadExternal($id,$new_folder)
  {
      if($id!=null)
      {
        $t_upload_tmp_model = $this->upload_tmp_repository->find($id);
        $upload_setup = $this->upload_setup_repository->findByModuleIdByName($t_upload_tmp_model->m_module_id,$t_upload_tmp_model->name);
        $new_path = str_replace('temporaries',$new_folder,$t_upload_tmp_model->path);
        $move = Storage::disk('s3')->move($t_upload_tmp_model->path,$new_path);
        $t_upload_model = new TUpload();
        $t_upload_model->m_upload_setup_id = $upload_setup->id;
        $t_upload_model->m_module_id = $t_upload_tmp_model->m_module_id;
        $t_upload_model->name = $t_upload_tmp_model->name;
        $t_upload_model->type = $t_upload_tmp_model->type;
        $t_upload_model->path = $new_path;
        $t_upload_model->file_source =  env('AWS_ENDPOINT').'/'.env('AWS_BUCKET').'/'.$new_path;
        $this->upload_repository->create($t_upload_model);
        $this->delete($id);
       // return $t_upload_model->path;
       return $t_upload_model;
    }
      else
        return null;
  }

  public function createImageFromBlob($request)
    {
        $id =  Uuid::uuid1()->toString();
        $upload_dir = public_path("temporaries");
        $img = $_POST['image'];
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $image_data = base64_decode($img);
      //  $unique_id = uniqid();
        $extension = ".jpg";
        $file_path = $upload_dir .'/'. $id . $extension;
        $stored_file=file_put_contents($file_path, $image_data);
        //dd($stored_file);

        $m_module_id=$request->m_module_id;
        $name=$request->name;

        $upload_setup = $this->upload_setup_repository->findByModuleIdByName($m_module_id,$name);
        if($upload_setup)
            $path = $upload_setup->path;
        else
            return ['success'=>false,'message'=>'not found upload setup','data'=>[]];

          //  dd($upload_setup);
        $file_path = 'temporaries'.'/'.$id.$extension;
        $t_upload_tmp_model = new TUploadTmp();
        $t_upload_tmp_model->id = $id;
        $t_upload_tmp_model->m_module_id =$m_module_id;
        $t_upload_tmp_model->extension ='jpeg';
        $t_upload_tmp_model->size =0;
        $t_upload_tmp_model->name =$name;
        $t_upload_tmp_model->path =$file_path;
        if($upload_setup->is_external_storage==1)
        {
            $t_upload_tmp_model->file_source = env('AWS_ENDPOINT').'/'.env('AWS_BUCKET').'/'.$file_path;
        }
        else
        {
            $t_upload_tmp_model->file_source = asset($file_path);
            $path=$file_path;
        }
        $t_upload_tmp_model->is_external_storage = $upload_setup->is_external_storage;
        $t_upload_tmp_model->using_thumbnail =$upload_setup->using_thumb;
        $t_upload_tmp_model->m_upload_setup_id =$upload_setup->id;
        $save = $this->upload_tmp_repository->create($t_upload_tmp_model);

        if($upload_setup->is_external_storage==1)
        {
            $path = Storage::disk('s3')->putFileAs('temporaries',$file_path, $id.$extension,'public');
        }

        if($save)
        {

            $response = ['file_src'=> $save->file_source,
            'path'=>$path,
           'file_name'=>$id.$extension,
           'file_id'=>$id];
            return ['success'=>true,'message'=>__('label.success_created'),'data'=>$response];
        }
        else
        {
            return ['success'=>false,'message'=>__('label.failed_created'),'data'=>[]];
        }
    }




    public function deleteByIds(array $ids)
    {
        $delete_tmp = $this->upload_tmp_repository->deleteByIds($ids);
        $delete = $this->upload_repository->deleteByIds($ids);
        return ['success'=>true,'message'=>'berhasil dihapus','data'=>[]];
    }





}
