<?php
namespace App\Services;
use App\Models\TPortfolio;
use App\Repositories\Sql\SqlPortfolioRepository;
use Exception;
use stdClass;

class PortfolioService
{

const MODULE_CODE = "portfolio";
const MODULE_NAME = "portfolio";

    public function __construct(private SqlPortfolioRepository $portfolio_repository,
                                private UploadTmpService $upload_tmp_service,
                                private AssessmentConfigService $assessment_config_service,
                                private AssessmentSubcategoryService $assessment_subcategory_service,
                                private AssessmentSubthemeService $assessment_subtheme_service,
                                private AssessmentSumService $assessment_sum_service 
                                )
    {
       
    }

public function create($request)
{
    try{
    $title= $request->title;
    $description= $request->description;
    $date_begin= $request->date_begin;
    $date_end= $request->date_end ?? null;
    $link= $request->link;
    $link1= $request->link1;

    $file_src= $request->file_src;
    $file_type= "pdf";
    $file1_src= $request->file1_src;
    $file1_type= "pdf";
    $file2_src= $request->file2_src;
    $file2_type= "pdf";
    $file3_src= $request->file3_src;
    $file3_type= "pdf";

    $user = $request->user;
    $m_participant_id = $user->participant->id;
    $assessment_level = 1; ///need tobe verified
    $is_submitted = 1;
    $submitted_by = $user->id;
    $submitted_by_name = $user->name;
    $submitted_at = date('Y-m-d H:i:s');
    $m_assessment_config = $this->assessment_config_service->findCurrentActive()['data'];
    $m_assessment_config_id =   $m_assessment_config->id;
    $m_assessment_config_year =   $m_assessment_config->year;
    $m_assessment_subcategory_id = $request->m_assessment_subcategory_id;
    $m_assessment_subtheme_id = $request->m_assessment_subtheme_id;
    $assessment_subcategory_model = $this->assessment_subcategory_service->find($m_assessment_subcategory_id)['data'];
    $assessment_subtheme_model = $this->assessment_subtheme_service->find($m_assessment_subtheme_id)['data'];
    $m_assessment_category_id = $assessment_subcategory_model->m_assessment_category_id;    
    $m_assessment_theme_id = $assessment_subtheme_model->m_assessment_theme_id;
   
    // additional value //
    $link1 =  $request->link1;

    /* disabled *
    if($this->checkTotalPerParticipant($m_participant_id)['success']==false)
    {
        return ['success'=>false,'message'=>'Gagal tersimpan, jumlah portofolio melebihi batas maksimum '.$this->checkTotalPerParticipant($user->participant->id)['data']->max_upload,'data'=>[]];
    }
    */
    $portfolio_per_subcategory_before = $this->portfolio_repository->findBySubcategoryIdByMParticipantId($m_assessment_subcategory_id,$m_participant_id);
    if($portfolio_per_subcategory_before!=null)
    {
        return ['success'=>false,'message'=>'Gagal tersimpan| portofolio sudah ada','data'=>[]];
    }


    $code = $this->generateCode($user->participant);
    $t_portfolio_model = new TPortfolio();
    $t_portfolio_model->code =$code;
    $t_portfolio_model->title =$title;
    $t_portfolio_model->description =$description;
    $t_portfolio_model->date_begin =$date_begin;
    $t_portfolio_model->date_end =$date_end;
   
    $t_portfolio_model->link =$link;
    $t_portfolio_model->m_assessment_config_id =$m_assessment_config_id;
    $t_portfolio_model->m_assessment_config_year =$m_assessment_config_year;
    $t_portfolio_model->m_assessment_category_id =$m_assessment_category_id;
    $t_portfolio_model->m_assessment_subcategory_id =$m_assessment_subcategory_id;
    $t_portfolio_model->m_assessment_theme_id =$m_assessment_theme_id;
    $t_portfolio_model->m_assessment_subtheme_id =$m_assessment_subtheme_id;
   
    $t_portfolio_model->m_participant_id =$m_participant_id;
   
    $t_portfolio_model->file_type =$file_type;
    $t_portfolio_model->is_submitted =$is_submitted;
    $t_portfolio_model->submitted_at =$submitted_at;
    $t_portfolio_model->submitted_by =$submitted_by;
    $t_portfolio_model->submitted_by_name =$submitted_by_name;
    $t_portfolio_model->assessment_level = $assessment_level;

    //additional value 1
    $t_portfolio_model->link1 = $link1;
    
    $code = $this->generateCode($user->participant);

    if(isset($file_src))
    {
        $new_path = 'uploads/'.$user->participant->code;
        $new = $this->upload_tmp_service->saveToUploadExternal($file_src,$new_path);
        $t_portfolio_model->file_src = $new->file_source;
        $t_portfolio_model->file_type = $new->type;
    }

    if(isset($file1_src))
    {
        $new_path = 'uploads/'.$user->participant->code;
        $new = $this->upload_tmp_service->saveToUploadExternal($file1_src,$new_path);
        $t_portfolio_model->file1_src = $new->file_source;
        $t_portfolio_model->file1_type = $new->type;
    }

    if(isset($file2_src))
    {
        $new_path = 'uploads/'.$user->participant->code;
        $new = $this->upload_tmp_service->saveToUploadExternal($file2_src,$new_path);
        $t_portfolio_model->file2_src = $new->file_source;
        $t_portfolio_model->file2_type = $new->type;
    }

    if(isset($file3_src))
    {
        $new_path = 'uploads/'.$user->participant->code;
        $new = $this->upload_tmp_service->saveToUploadExternal($file3_src,$new_path);
        $t_portfolio_model->file3_src = $new->file_source;
        $t_portfolio_model->file3_type = $new->type;
    }

    $save = $this->portfolio_repository->create($t_portfolio_model);

    //set to assessment score sum
    $assessment_sum = new stdClass();
    $assessment_sum->m_participant_id=$save->m_participant_id;
    $assessment_sum->t_portfolio_id=$save->id;
    $assessment_sum->total_score=0;
    $assessment_sum->description=null;
    $assessment_sum->status=null;
    $assessment_sum->rank=null;
    $assessment_sum->assessment_level=2; // to stage qualification
    $assessment_sum->assessment_type=$save->category->assessment_type;
    $assessment_sum->submission_type='portfolio';
    $assessment_sum->m_assessment_config_id=$save->m_assessment_config_id;
    $assessment_sum->m_assessment_config_year=$save->m_assessment_config_year;
    $assessment_sum->m_assessment_category_id=$save->m_assessment_category_id;
    $assessment_sum->m_assessment_subcategory_id=$save->m_assessment_subcategory_id;
    $this->assessment_sum_service->create($assessment_sum)['data'];
    //send assessment sum

    return ['success'=>true,'message'=>'berhasil disimpan','data'=>$save];
    }
    catch(Exception $e)
    {
        return ['success'=>false,'message'=>$e->getMessage(),'data'=>[]];
    }
}

public function update($request)
{
try{
    $title= $request->title;
    $description= $request->description;
    $date_begin= $request->date_begin;
    $date_end= $request->date_end ?? null;
    $link= $request->link;
    $file_src= $request->file_src;
    $file_type= "pdf";
    $file1_src= $request->file1_src;
    $file1_type= "pdf";
    $file2_src= $request->file2_src;
    $file2_type= "pdf";
    $file3_src= $request->file3_src;
    $file3_type= "pdf";
    $link1= $request->link1;

    $user = $request->user;
    $m_participant_id = $user->participant->id;
    $assessment_level = 2; ///directly sent to qualitification
    $is_submitted = 1;
    $submitted_by = $user->id;
    $submitted_by_name = $user->name;
    $submitted_at = date('Y-m-d H:i:s');
    $m_assessment_config = $this->assessment_config_service->findCurrentActive()['data'];
    $m_assessment_config_id =   $m_assessment_config->id;
    $m_assessment_config_year =   $m_assessment_config->year;
    $m_assessment_subcategory_id = $request->m_assessment_subcategory_id;
    $m_assessment_subtheme_id = $request->m_assessment_subtheme_id;
    $assessment_subcategory_model = $this->assessment_subcategory_service->find($m_assessment_subcategory_id)['data'];
    $assessment_subtheme_model = $this->assessment_subtheme_service->find($m_assessment_subtheme_id)['data'];
    $m_assessment_category_id = $assessment_subcategory_model->m_assessment_category_id;    
    $m_assessment_theme_id = $assessment_subtheme_model->m_assessment_theme_id;
    
    // additional value //
    //$additional_val1 =  $request->additional_val1;


   // $code = $this->generateCode($user->participant);
    $t_portfolio_model = $this->portfolio_repository->find($request->id);
    //$t_portfolio_model->code =$code;

    if($m_assessment_subcategory_id!=$t_portfolio_model->m_assessment_subcategory_id)
    {
        $portfolio_per_subcategory_before = $this->portfolio_repository->findBySubcategoryIdByMParticipantId($m_assessment_subcategory_id,$m_participant_id);
            if($portfolio_per_subcategory_before!=null)
            {
                return ['success'=>false,'message'=>'Gagal tersimpan| portofolio sudah ada','data'=>[]];
            }
    }

    $t_portfolio_model->title =$title;
    $t_portfolio_model->description =$description;
    $t_portfolio_model->date_begin =$date_begin;
    $t_portfolio_model->date_end =$date_end;
   
    $t_portfolio_model->link =$link;
    $t_portfolio_model->m_assessment_config_id =$m_assessment_config_id;
    $t_portfolio_model->m_assessment_config_year =$m_assessment_config_year;
    $t_portfolio_model->m_assessment_category_id =$m_assessment_category_id;
    $t_portfolio_model->m_assessment_subcategory_id =$m_assessment_subcategory_id;
    $t_portfolio_model->m_assessment_theme_id =$m_assessment_theme_id;
    $t_portfolio_model->m_assessment_subtheme_id =$m_assessment_subtheme_id;
   
    $t_portfolio_model->m_participant_id =$m_participant_id;
   
    $t_portfolio_model->file_type =$file_type;
    $t_portfolio_model->is_submitted =$is_submitted;
    $t_portfolio_model->submitted_at =$submitted_at;
    $t_portfolio_model->submitted_by =$submitted_by;
    $t_portfolio_model->submitted_by_name =$submitted_by_name;
    $t_portfolio_model->assessment_level = $assessment_level;
   
     //additional value 1
    $t_portfolio_model->link1 = $link1;
    
    if(isset($file_src))
    {
        $new_path = 'uploads/'.$user->participant->code;
        $new = $this->upload_tmp_service->saveToUploadExternal($file_src,$new_path);
        $t_portfolio_model->file_src = $new->file_source;
        $t_portfolio_model->file_type = $new->type;
    }

    if(isset($file1_src))
    {
        $new_path = 'uploads/'.$user->participant->code;
        $new = $this->upload_tmp_service->saveToUploadExternal($file1_src,$new_path);
        $t_portfolio_model->file1_src = $new->file_source;
        $t_portfolio_model->file1_type = $new->type;
    }

    if(isset($file2_src))
    {
        $new_path = 'uploads/'.$user->participant->code;
        $new = $this->upload_tmp_service->saveToUploadExternal($file2_src,$new_path);
        $t_portfolio_model->file2_src = $new->file_source;
        $t_portfolio_model->file2_type = $new->type;
    }

    if(isset($file3_src))
    {
        $new_path = 'uploads/'.$user->participant->code;
        $new = $this->upload_tmp_service->saveToUploadExternal($file3_src,$new_path);
        $t_portfolio_model->file3_src = $new->file_source;
        $t_portfolio_model->file3_type = $new->type;
    }

    $save = $this->portfolio_repository->update($t_portfolio_model);

    //set to assessment score sum
    $assessment_sum = new stdClass();
    $assessment_sum->m_participant_id=$save->m_participant_id;
    $assessment_sum->t_portfolio_id=$save->id;
    $assessment_sum->submission_type='portfolio';
    $assessment_sum->m_assessment_config_id=$save->m_assessment_config_id;
    $assessment_sum->m_assessment_config_year=$save->m_assessment_config_year;
    $assessment_sum->m_assessment_category_id=$save->m_assessment_category_id;
    $assessment_sum->m_assessment_subcategory_id=$save->m_assessment_subcategory_id;
    $save_sum =$this->assessment_sum_service->updateCategory($assessment_sum)['data'];
    //dd($save_sum);
    //send assessment sum
    return ['success'=>true,'message'=>'berhasil diubah','data'=>$save];
    }
    catch(Exception $e)
    {
        dd($e->getMessage());
        return ['success'=>false,'message'=>$e->getMessage(),'data'=>[]];
    }
}

public function delete($id)
{
    $delete= $this->portfolio_repository->delete($id);
    return ['success'=>true,'message'=>'berhasil dihapus','data'=>[]];
}

public function getTableColumns()
{
    return $this->portfolio_repository->getTableColumns();
}

public function find($id)
{
    $model = $this->portfolio_repository->find($id);
    return ['success'=>true,'message'=>'ditemukan','data'=>$model];
}

public function all()
{
    $collection =  $this->portfolio_repository->all();
    return ['success'=>true,'message'=>'ditemukan','data'=>$collection];
}

private function generateCode($participant)
{
    $participant_code = $participant->code ;
    $total_masterpiece = $this->portfolio_repository->countByMParticipantId($participant->id);
    return $participant_code.'-'.($total_masterpiece+1);
}

private function checkTotalPerParticipant($participant_id)
{
    $current_config = $this->assessment_config_service->findCurrentActive()['data'];
    $total_masterpiece = $this->portfolio_repository->countByMParticipantId($participant_id);
    if($total_masterpiece>$current_config->max_upload)
    {
        return ['success'=>false,'data'=>$current_config];
    }
    else
    {
        return ['success'=>true,'data'=>$current_config];
    }
}

public function getTotal()
{
    $collection =  $this->portfolio_repository->getTotal();
    return ['success'=>true,'message'=>'ditemukan','data'=>$collection];
}


public function getDataTable($object)
{
        $m_assessment_config = $this->assessment_config_service->findCurrentActive()['data'];
        $object->m_assessment_config_id= $m_assessment_config->id;
        //$object->m_assessment_config_id= $m_assessment_config->id;
        $can_view = 1;
        $can_edit = 0;
        $can_delete = 1;
        $url_view = url($object->lang.'/dashboard/portfolio/detail').'/';
        $url_delete = url($object->lang.'/dashboard/portfolio/delete').'/';
        $url_edit = url($object->lang.'/dashboard/portfolio/edit').'/';
        return datatables()->of($this->portfolio_repository->getDataTable($object))
        ->addColumn('action', function($row) use($can_view,$url_view,$can_edit,$url_edit,$can_delete,$url_delete){
            $btn = buttonDataTable($can_view,$url_view.$row->id, $can_edit, $url_edit.$row->id, $can_delete,$row->id,$row->title);
            return $btn;
        })
        ->editColumn('date_begin', function($row) {
                return formatDateSlash($row->date_begin) ;
        })
        ->editColumn('submitted_at', function($row)  {
                return DateTimeFormatDateSlash($row->submitted_at) ;
        })
         ->editColumn('m_participant_name', function($row) use($object) {
                return  "<a href='".url($object->lang.'/dashboard/participant/detail/'.$row->m_participant_id)."'><small class='badge bg-warning'>".$row->m_participant_code."</small> <br>". $row->m_participant_name."</a>";
        })
           ->editColumn('m_assessment_subtheme_name', function($row)  {
                return "<small>".$row->m_assessment_theme_name."</small>. <br>".
                    $row->m_assessment_subtheme_name;
        })

         ->editColumn('is_verified',function($row){
            return passedOrNot($row->is_verified);
          })
       
        ->addIndexColumn()
        ->rawColumns(['m_assessment_subtheme_name','m_participant_name','is_verified','action'])
        ->make(true);
}

public function updateByParticipantIdByConfigId($status_level,$m_participant_id,$m_assessment_config_id)
{
    $model = $this->portfolio_repository->updateByParticipantIdByConfigId($status_level,$m_participant_id,$m_assessment_config_id);
    return ['success'=>true,'message'=>'ditemukan','data'=>$model];
}




public function getByParticipant($m_participant_id)
{
    $current_config = $this->assessment_config_service->findCurrentActive()['data'];
    $collection =  $this->portfolio_repository->getByParticipantByConfig($m_participant_id, $current_config->id);
    return ['success'=>true,'message'=>'ditemukan','data'=>$collection];
}



public function findModuleCode()
{
    return self::MODULE_CODE;
}

public function findModuleName()
{
    return self::MODULE_NAME;
}


public function getDownload()
{
    $collection =  $this->portfolio_repository->getJoin();
    return ['success'=>true,'message'=>__('label.found'),'data'=>$collection];
}


public function verify($object)
{
    $portfolio = $this->portfolio_repository->find($object->id);

    $assessment_sum = $this->assessment_sum_service->findByParticipantByPortfolioByConfig($portfolio->m_participant_id,$portfolio->id,$portfolio->m_assessment_config_id)['data'];

    try{
        $portfolio->is_verified = $object->is_verified;    
        $portfolio->verified_at = date('Y-m-d H:i:s');
        $portfolio->verified_by = $object->user->id;
        $portfolio->verified_by_name = $object->user->name;
        $portfolio->remark = $object->remark;
        $portfolio->assessment_level = 2; //to stage qualification
        $save = $this->portfolio_repository->update($portfolio);

        
        $assessment_sum->status=null;
        $assessment_sum->assessment_level=2; // to stage qualification
        $this->assessment_sum_service->updateLevel($assessment_sum)['data'];

        return ['success'=>true,'message'=>__('label.found'),'data'=>$save];
    }
     catch(Exception $e)
    {
        dd($e->getMessage());
    }

}

public function getProperties($code=null)
{
    //dd($code);
    $properties = array('K1'=>array('description'=>'siaran pers',
                                    'section_link'=>'style="display:none"',
                                    'section_link1'=>'style="display:none"',
                                    'section_published_at'=>'',
                                    'section_date_end'=>'style="display:none"',
                                    'label_published_at'=>'Tanggal publikasi',
                                    'section_file1'=>'style="display:none"',
                                    'section_file2'=>'style="display:none"',
                                    'section_file3'=>'style="display:none"',
                                    'file_label'=>'Upload PDF',
                                    'file1_label'=>'',
                                    'file2_label'=>'',
                                    'file3_label'=>'',
                                    'link_label'=>'',
                                    'link1_label'=>'',
                                ),
                           'K2'=>array('description'=>'media sosial',
                                    'section_link'=>'',
                                    'section_link1'=>'style="display:none"',
                                    'section_published_at'=>'',
                                    'section_date_end'=>'style="display:none"',
                                    'label_published_at'=>'Tanggal publikasi',
                                    'section_file1'=>'style="display:none"',
                                    'section_file2'=>'style="display:none"',
                                    'section_file3'=>'style="display:none"',
                                    'file_label'=>'Upload PDF',
                                    'file1_label'=>'',
                                    'file2_label'=>'',
                                    'file3_label'=>'',
                                    'link_label'=>'Link content',
                                    'link1_label'=>'',
                                ),
                              'K3'=>array('description'=>'website',
                                    'section_link'=>'',
                                    'section_link1'=>'',
                                    'section_published_at'=>'style="display:none"',
                                    'section_date_end'=>'style="display:none"',
                                    'label_published_at'=>'Tanggal publikasi',
                                    'section_file1'=>'',
                                    'section_file2'=>'',
                                    'section_file3'=>'',
                                    'file_label'=>'File. Google Analytic',
                                    'file1_label'=>'File. Laporan Keuangan',
                                    'file2_label'=>'File. GPR',
                                    'file3_label'=>'PDF Narasi Strategi',
                                    'link_label'=>'Halaman muka',
                                    'link1_label'=>'Halaman lapkeu',
                                ),    

                            'K4'=>array('description'=>'kampanye',
                                'section_link'=>'style="display:none"',
                                'section_link1'=>'style="display:none"',
                                'section_published_at'=>'',
                                'section_date_end'=>'',
                                'label_published_at'=>'Periode Pelaksanaan',
                                'section_file1'=>'style="display:none"',
                                'section_file2'=>'style="display:none"',
                                'section_file3'=>'style="display:none"',
                                'file_label'=>'Upload PDF',
                                'file1_label'=>'',
                                'file2_label'=>'',
                                'file3_label'=>'',
                                'link_label'=>'',
                                'link1_label'=>'',
                                    ), 

                             'K5'=>array('description'=>'audio visual',
                                'section_link'=>'',
                                'section_link1'=>'style="display:none"',
                                'section_published_at'=>'',
                                'section_date_end'=>'style="display:none"',
                                'label_published_at'=>'Tanggal publikasi',
                                'section_file1'=>'',
                                'section_file2'=>'style="display:none"',
                                'section_file3'=>'style="display:none"',
                                'file_label'=>'Upload PDF',
                                'file1_label'=>'Upload PDF viewer',
                                'file2_label'=>'',
                                'file3_label'=>'',
                                'link_label'=>'Majalah/E-Magazine',
                                'link1_label'=>'',
                                    ), 
                            
                             'K6'=>array('description'=>'media internal',
                                'section_link'=>'',
                                'section_link1'=>'style="display:none"',
                                'section_published_at'=>'',
                                'section_date_end'=>'style="display:none"',
                                'label_published_at'=>'Tanggal publikasi',
                                'section_file1'=>'',
                                'section_file2'=>'style="display:none"',
                                'section_file3'=>'style="display:none"',
                                'file_label'=>'Upload PDF Edisi',
                                'file1_label'=>'Upload PDF Viewer',
                                'file2_label'=>'',
                                'file3_label'=>'',
                                'link_label'=>'Link Website/app',
                                'link1_label'=>'',
                                    ),           
                                
                            );

    foreach (array_keys($properties) as $k) {
        $properties[strtolower($k)] = $properties[$k];
    }

    if($code!=null)
        return $properties[strtoupper($code)] ?? ($properties[strtolower($code)] ?? ($properties[$code] ?? null));                        
    else
        return $properties;
}


public function customRules($m_assessment_subcategory_code)
{
   $rules =  array('K1'=>['name'=>'siaran pers',
                          'rules'=>['title'  => 'required',
                                    'm_assessment_subcategory_id' => 'required',
                                    'm_assessment_subtheme_id' => 'required',
                                    'file_src' => 'required',
                                    ], //end rules
                          'messages'=>['title.required'=> 'Harap isi judul',
                                      'm_assessment_subcategory_id.required'=>'Harap pilih subkategori',
                                      'm_assessment_subtheme_id.required'=>'Harap Pilih subtema',
                                      'file_src.required'=>'Harap unggah file'
                                    ], //end messages
                    ],  //end K1

                   'K2'=>['name'=>'media sosial',
                          'rules'=>['title'  => 'required',
                                    'm_assessment_subcategory_id' => 'required',
                                    'm_assessment_subtheme_id' => 'required',
                                    'file_src' => 'required',
                                    'link' => 'required',
                                    ], //end rules
                          'messages'=>['title.required'=> 'Harap isi judul',
                                      'm_assessment_subcategory_id.required'=>'Harap pilih subkategori',
                                      'm_assessment_subtheme_id.required'=>'Harap Pilih subtema',
                                      'file_src.required'=>'Harap unggah file',
                                       'link.required'=>'Harap isi link konten'
                                    ], //end messages
                    ],  //end K2

                    'K3'=>['name'=>'Website',
                          'rules'=>['title'  => 'required',
                                    'm_assessment_subcategory_id' => 'required',
                                    'm_assessment_subtheme_id' => 'required',
                                    'file_src' => 'required',
                                    'file1_src' => 'required',
                                    'file2_src' => 'required',
                                    'file3_src' => 'required',
                                    'link' => 'required',
                                    ], //end rules
                          'messages'=>['title.required'=> 'Harap isi judul',
                                      'm_assessment_subcategory_id.required'=>'Harap pilih subkategori',
                                      'm_assessment_subtheme_id.required'=>'Harap Pilih subtema',
                                      'file_src.required'=>'Harap unggah file Google Analytic',
                                      'file1_src.required'=>'Harap unggah file Laporan Keuangan',
                                      'file2_src.required'=>'Harap unggah file GPR',
                                      'file3_src.required'=>'Harap unggah file PDF Narasi Strategi',
                                       'link.required'=>'Harap isi link halaman muka',
                                       'link1.required'=>'Harap isi link laporan keuangan'
                                    ], //end messages
                    ],  //end K3


                     'K4'=>['name'=>'Kampanye',
                          'rules'=>['title'  => 'required',
                                    'm_assessment_subcategory_id' => 'required',
                                    'm_assessment_subtheme_id' => 'required',
                                    'file_src' => 'required',
                                    ], //end rules
                          'messages'=>['title.required'=> 'Harap isi judul',
                                      'm_assessment_subcategory_id.required'=>'Harap pilih subkategori',
                                      'm_assessment_subtheme_id.required'=>'Harap Pilih subtema',
                                      'file_src.required'=>'Harap unggah file Google Analytic',
                                      
                                    ], //end messages
                    ],  //end K4


                     'K5'=>['name'=>'Audio visual',
                          'rules'=>['title'  => 'required',
                                    'm_assessment_subcategory_id' => 'required',
                                    'm_assessment_subtheme_id' => 'required',
                                    'file_src' => 'required',
                                    'file1_src' => 'required',
                                    'link' => 'required',
                                    ], //end rules
                          'messages'=>['title.required'=> 'Harap isi judul',
                                      'm_assessment_subcategory_id.required'=>'Harap pilih subkategori',
                                      'm_assessment_subtheme_id.required'=>'Harap Pilih subtema',
                                      'file_src.required'=>'Harap unggah file Google Analytic',
                                      'file1_src.required'=>'Harap unggah file Jml Viewer',
                                      'link.required'=>'Harap isi link Youtube',
                                    ], //end messages
                    ],  //end K5


                     'K6'=>['name'=>'Media Internal',
                          'rules'=>['title'  => 'required',
                                    'm_assessment_subcategory_id' => 'required',
                                    'm_assessment_subtheme_id' => 'required',
                                    'file_src' => 'required',
                                    'file1_src' => 'required',
                                    ], //end rules
                          'messages'=>['title.required'=> 'Harap isi judul',
                                      'm_assessment_subcategory_id.required'=>'Harap pilih subkategori',
                                      'm_assessment_subtheme_id.required'=>'Harap Pilih subtema',
                                      'file_src.required'=>'Harap unggah file Edisi',
                                      'file1_src.required'=>'Harap unggah file Jml Viewer',                                     
                                       
                                    ], //end messages
                    ],  //end K6
                    
                );

    foreach (array_keys($rules) as $k) {
        $rules[strtolower($k)] = $rules[$k];
    }

    if($m_assessment_subcategory_code!=null)
        return $rules[strtoupper($m_assessment_subcategory_code)] ?? ($rules[strtolower($m_assessment_subcategory_code)] ?? ($rules[$m_assessment_subcategory_code] ?? null));                        
    else
        return $rules;

}


public function findAssessmentSubcategoryById($m_assessment_subcategory_id)
{
    return $this->assessment_subcategory_service->find($m_assessment_subcategory_id)['data'];
}


public function getValidationRule($m_assessment_subcategory_id)
{
    $m_assessment_subcategory_model = $this->assessment_subcategory_service->find($m_assessment_subcategory_id)['data'];
    $validation_rule = $this->customRules($m_assessment_subcategory_model->code);
    return $validation_rule;
}


}
