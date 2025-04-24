<?php

namespace App\Http\Controllers\Report;

use DB;
use Auth;
use Excel;
use App\Models\Post;
use App\Models\User;
use App\Models\Grade;
use Illuminate\Http\Request;

use App\Models\Group_Service;

use App\Models\Officer_Profile;
use App\Http\Controllers\Controller;
use App\Exports\PostingOfficerExport;
use Yajra\DataTables\Facades\DataTables;

class PostingController extends Controller
{

    public function index(Request $request){

        $data = $request->all();

        $group_services = Group_Service::all();
        $grades = Grade::where('id','>',16)->get();

        $group_service_id = $group_services[0]->id;
        $service_name = $group_services[0]->name;
        $grade_id = $grades[0]->id;
        $grade_id = null;
        $posts = Post::all();
        $post_id  = $posts[0]->id;

        return view('report.posting',compact(['group_services','grades','group_service_id','grade_id','service_name','posts','post_id']));
    }//end of function

    public function getReports(Request $request) {
      ini_set('max_execution_time', -1);
      $search = $request->input('search.value');
      $columns = $request->get('columns');
      $pageSize = ($request->length) ? $request->length : 10;

      $start = ($request->start) ? $request->start : 0;
      $count_filter = 0;

    //   $officers = Officer_Profile::select();

        $officers = Officer_Profile::select('*');
        // $itemCounter = $officers->get();
        // $count_total = $itemCounter->count();

        if($request->group_service_id){
            $officers
            ->where('group_service_id',$request->group_service_id);
        }

        if($request->get_post_id){
            $post_id = $request->get_post_id;

            $officers
            ->whereHas('current_post',function($query) use ($post_id){
                $query->where('post_id',$post_id);
                $query->where('is_active',1);
            });
        }

        if($request->grade_id){

            $grade_id = $request->grade_id;
            $officers->whereIn('current_grade_id',$request->grade_id);
        }

        $officers
        ->whereHas('current_post',function($query){
            $query->where('is_active',1);
        });

        $officers = $officers
        ->orderBy('current_grade_id', 'DESC')
        ->orderBy('seniority_code', 'ASC');

    	return Datatables::of($officers)
        ->addIndexColumn()
        ->addColumn('name', function($row){

            $officerName = $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name . ' ';

            $office_dob = date("d-m-Y",strtotime($row->date_of_birth));
            $dob = $office_dob.' ^'.$row->seniority_code;
            $domicile_district = $row->domicile_district ? $row->domicile_district->name : '';
            $domicile_province = $row->domicile_province ? $row->domicile_province->name : '';

            if(!empty($domicile_district) && !empty($domicile_province)){
                $actionBtn = '<p>'.$officerName.'<br>'.
                $domicile_province.'('.$domicile_district.')'.'<br>'.$dob.'<br>'.'</p>';
            }else{
                $actionBtn = '<p>'.$officerName.'<br>'.$dob.'</p>';
            }

            $mtraining = '<p>';
            $trainings = '';

            $mandatory_trainings = $row->mandatory_trainings()->whereIn('training_type_id',[1,2,3,4])->get();

            if(count($mandatory_trainings) > 0){
                foreach($mandatory_trainings as $mt=>$train){
                    $trainings .= $train->training_type->name.'-'.$train->institution."\r <br>";
                }
            }

            $mtraining.=$trainings.'</p>';
            $actionBtn = $actionBtn.$mtraining;

            return $actionBtn;
        })->addColumn('p_no', function($row){
            return $row->p_no;

        })->addColumn('rank', function($row){

            $dmgjoiningdate = date("d-m-Y",strtotime($row->dmgjoiningdate));
            $joiningdate = date("d-m-Y",strtotime($row->joiningdate));

            $promotions = $row->promotions()->orderBy('id','desc')->get();
            $promotion_date = count($promotions) > 0 ? date("d-m-Y",strtotime($promotions[0]->permotion_date)) : null;

            $current_post_date = $row->current_post->from_date ? date("d-m-Y",strtotime($row->current_post->from_date)) : null;

            $actionBtn = '<p>'.$dmgjoiningdate.'<br>'.$joiningdate.'<br>'.
            $promotion_date.'</p>';
            return $actionBtn;

        })->addColumn('posting', function($row){

            $current_post = $row->current_post->post->name ?? '';

            $ministry=$row->current_post->ministry->name ?? '';
            $division=$row->current_post->division->name ?? '';
            $dept=$row->current_post->department->name ?? '';

            $posting_area = $ministry.' '.$division.' '.$dept;

            $from = $row->current_post->from ?? '';
            $to = $row->current_post->to ?? '';

            if(empty($to)){
                $posting_date = date("d-m-Y",strtotime($from));
            }else{

                $posting_date = date("d-m-Y",strtotime($from)).' - '.date("d-m-Y",strtotime($to));
            }

            $posting_list = $row->current_post->post_list;
            $posting_organization = $row->current_post->orgnization_list;
            $posting_date = date("d-m-Y",strtotime($from));

            $actionBtn = $posting_list.'<br>'.$posting_organization.'<br>'.
            $posting_date.'</p>';
            return $actionBtn;

        })->rawColumns(['name','rank','posting'
        ])->with([
            'pageLength' => $pageSize
        ])->make(true);

    }//end of function

    public function getPostingOfficer($group_service_id,$grade_id = null){

        $officers = Officer_Profile::select('*');
        if($group_service_id){
            $officers
            ->where('group_service_id',$group_service_id);
        }

        if($grade_id){
            $officers->where('current_grade_id',$grade_id);
        }

        $officers
        ->whereHas('current_post',function($query){
            $query->where('is_active',1);
        });

        $officers = $officers
        // ->orderBy('seniority','DESC')
        ->orderBy('seniority_code', 'ASC')->get();

        return $officers;

    }//end of function

    public function downloadReports(Request $request){

        ini_set('max_execution_time', -1);
        $group_service_id = $grade_id = null;
        $grade_ids = array();
        $fontSize = 9.5;

        if($request->group_service_id){
            $group_service_id = $request->group_service_id;
        }

        if($request->grade_id){
            $grade_id = $request->grade_id;
            $grade_id = explode(',', $grade_id);
        }else{
            $grade_id = [22,21,20,19,18,17];
        }

        // $grade_ids = $grade_id ? array(array('id'=>$grade_id)) : Grade::where('id','>',16)->orderBy('id','DESC')->get(['id'])->toArray();
        $grade_ids = $grade_id;

        $wordTest = new \PhpOffice\PhpWord\PhpWord();
        $tableStyle = array('borderSize' => 0, 'borderColor' => 'ffffff','marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.2,'afterSpacing' => 0, 'Spacing'=> 0, 'cellMargin'=>0  ,'wrappingStyle' => 'infront','keepLines'=>true,'pageBreakBefore'=>true,'cantSplit' => true);
        $styleCell = array('borderTopSize'=>0 ,'borderTopColor' =>'ffffff','marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.2,'borderLeftSize'=>0,'borderLeftColor' =>'ffffff','borderRightSize'=>0,'borderRightColor'=>'ffffff','borderBottomSize' =>0,'borderBottomColor'=>'ffffff');
        $fontStyle = array('size'=>$fontSize, 'name'=>'Courier New','afterSpacing' => 0, 'Spacing'=> 0, 'cellMargin'=>0 ,'marginTop'=> 0,"marginRight"=>0.4,"marginBottom"=>1,'marginLeft'=> 0.2);
        $TfontStyle = array('cellMargin'=>0,'name'=>'Courier New','align' => 'center','marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.7);
        $DfontStyle = array('cellMargin'=>0,'name'=>'Courier New','align' => 'center','marginTop'=> 0,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.7);
        $ThfontStyle = array('size'=>$fontSize, 'name' => 'Courier New','afterSpacing' => 0,'Spacing'=> 0,'cellMargin'=>0,'marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.2);
        $cfontStyle = array('size'=>$fontSize, 'name' => 'Courier New','afterSpacing' => 0, 'Spacing'=> 0, 'cellMargin'=>0,'marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.2);
        $noSpace = array('textBottomSpacing' => -1,'marginTop'=> 1,"marginRight"=>0.4,"marginBottom"=>0.3,'marginLeft'=> 0.2);
        $footerStyle = array('size'=>$fontSize, 'name'=>'Courier New','align' => 'center','afterSpacing' => 0,'Spacing'=> 0,'cellMargin'=>0,'marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>1,'marginLeft'=> 0.2);

        $textStyle = array('size'=>$fontSize);
        // $addTxtStyle = array('wrappingStyle' => 'infront');
        $tableRowStyle = array('cantSplit' => true);

        foreach ($grade_ids as $g => $g_id) {

            // $grade_id = $g_id['id'];
            $grade_id = $g_id;
            $officers = $this->getPostingOfficer($group_service_id,$grade_id);
            $group_service = Group_Service::find($group_service_id);

            if(count($officers) > 0){

                $newSection = $wordTest->addSection();//array('marginTop'=> 1,"marginRight"=>0.7,"marginBottom"=>1,'marginLeft'=> 0.7)

                $header = $newSection->addHeader();
                $footer = $newSection->addFooter();

                $desc1 = "CHECK LIST/LATEST POSTINGS FOR INTERNAL USE ONLY";
                $desc2 = "NOT TO BE QUOTED/REFERRED/REPRESENTED";
                $desc3 = "OFFICERS IN BS: ".$grade_id." ".$group_service->name."  As on: ".date("d/m/Y")." Page:({PAGE})";

                $header->addText($desc1,['name'=>'Courier New','size'=>10,'align' => 'center','marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.2,'spaceAfter'=>0], ['size'=>10,'align' => 'center','marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.2,'spaceAfter'=>0]);
                $header->addText($desc2,['name'=>'Courier New','size'=>10,'align' => 'center','marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.2,'spaceAfter'=>0], ['size'=>10,'align' => 'center','marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.2,'spaceAfter'=>0]);
                // $header->addText($desc3);

                $header->addPreserveText($desc3,['name'=>'Courier New','size'=>10,'align' => 'center','marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.2], ['size'=>10,'align' => 'center','marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.2]);

                $footerTxt = "This is not a Seniority List.";
                $footer->addText($footerTxt,['size'=>10,'align' => 'center','marginTop'=> 0.3,"marginRight"=>0.4,"marginBottom"=>0,'marginLeft'=> 0.2,'spaceAfter'=>0], $footerStyle);

                $table = $header->addTable('myOwnTableStyle',array('borderSize' => 1, 'borderColor' => '999999', 'afterSpacing' => 0, 'Spacing'=> 0, 'cellMargin'=>0  ,'wrappingStyle' => 'infront'));
                $table->addRow();

                $table->addCell(700,$styleCell)->addText('P.No.<w:br/><w:br/><w:br/>-----',$TfontStyle);
                $table->addCell(700,$styleCell)->addText("S.No<w:br/><w:br/><w:br/>----",$TfontStyle);
                $table->addCell(3200,$styleCell)->addText('OFFICER NAME'.'<w:br/>'.'DOMICILE/DT OF BIRTH'.'<w:br/>'.'TRAININGS<w:br/>--------------------',$TfontStyle);
                $table->addCell(1500,$styleCell)->addText("DT OF J GVT".'<w:br/>'."SER/OCC GP".'<w:br/>'."PRT RANK<w:br/>-----------",$TfontStyle);
                $table->addCell(3200,$styleCell)->addText("PRESENT POSTING".'<w:br/>'."WITH DATE OF APPOINTMENT<w:br/><w:br/>------------------------",$TfontStyle);

                // $table->addRow();

                // $table->addCell(700,$styleCell)->addText('-----',$DfontStyle);
                // $table->addCell(700,$styleCell)->addText("----",$DfontStyle);
                // $table->addCell(3200,$styleCell)->addText('--------------------',$DfontStyle);
                // $table->addCell(1500,$styleCell)->addText("-----------",$DfontStyle);
                // $table->addCell(3200,$styleCell)->addText("------------------------",$DfontStyle);


                $table = $newSection->addTable('myOwnTableStyle',array('borderSize' => 1, 'borderColor' => '999999', 'afterSpacing' => 0, 'Spacing'=> 0, 'cellMargin'=>0  ,'wrappingStyle' => 'infront'));
                // $table->addRow();

                // $table->addCell(1800,$styleCell)->addText('P.No.',$TfontStyle);
                // $table->addCell(1800,$styleCell)->addText("S.No",$TfontStyle);
                // $table->addCell(1800,$styleCell)->addText('Officer Name'.'<w:br/>'.'Domicile/DT of Birth'.'<w:br/>'.'Trainings',$TfontStyle);
                // $table->addCell(1800,$styleCell)->addText("DT of J GVT".'<w:br/>'."SER/OCC GP".'<w:br/>'."PRT Rank",$TfontStyle);
                // $table->addCell(1800,$styleCell)->addText("Present Posting".'<w:br/>'."with date of appointment",$TfontStyle);

                foreach ($officers as $key => $officer) {

                    $table->addRow(null, array('cantSplit' => true));
                    $officerName = $officer->first_name . ' ' . $officer->middle_name . ' ' . $officer->last_name . ' ';

                    $office_dob = date("d-m-Y",strtotime($officer->date_of_birth));
                    $dob = $office_dob.' ^'.$officer->seniority_code;
                    $domicile_district = $officer->domicile_district ? $officer->domicile_district->name : '';
                    $domicile_province = $officer->domicile_province ? $officer->domicile_province->name : '';

                    if(!empty($domicile_district) && !empty($domicile_province)){
                        $officer_training = $officerName.'<w:br/>'.
                        $domicile_province.'('.$domicile_district.')'.'<w:br/>'.$dob.'<w:br/>';
                    }else{
                        $officer_training = $officerName.'<w:br/>'.$dob.'<w:br/>';
                    }

                    $mtraining = '';
                    $trainings = '';

                    $mandatory_trainings = $officer->mandatory_trainings()->whereIn('training_type_id',[1,2,3,4])->get();
                    if(count($mandatory_trainings) > 0){

                        $manItems = count($mandatory_trainings);
                        foreach($mandatory_trainings as $mt=>$train){

                            $training_obj = $train->training_type->name.'-'.$train->institution;
                            $training_obj = preg_replace('/[^A-Za-z0-9\-]/', ' ', $training_obj);
                            if(++$mt === $manItems) {
                                $trainings .= $training_obj;
                            }else{
                                $trainings .= $training_obj."<w:br/>";
                            }

                        }
                    }

                    $mtraining.=$trainings;
                    // $mtraining = preg_replace('/[^A-Za-z0-9\-]/', '-', $mtraining);

                    $officer_training = $officer_training.$mtraining;

                    $p_no = $officer->p_no;
                    $s_no = ++$key;

                    $dmgjoiningdate = date("d-m-Y",strtotime($officer->dmgjoiningdate));
                    $joiningdate = date("d-m-Y",strtotime($officer->joiningdate));

                    $promotions = $officer->promotions()->orderBy('id','desc')->get();
                    $promotion_date = count($promotions) > 0 ? date("d-m-Y",strtotime($promotions[0]->permotion_date)) : null;

                    $rank_promotion = $dmgjoiningdate.'<w:br/>'.$joiningdate.'<w:br/>'.
                    $promotion_date;

                    $from = $officer->current_post->from ?? '';

                    $posting_list = $officer->current_post->post_list ?? '';
                    $posting_organization = $officer->current_post->orgnization_list ?? '';
                    $posting_date = date("d-m-Y",strtotime($from));

                    // $posting_list = preg_replace('/[^A-Za-z0-9\-]/', ' ', $posting_list);
                    // $posting_organization = preg_replace('/[^A-Za-z0-9\-]/', ' ', $posting_organization);

                    $posting_data = htmlspecialchars($posting_list).'<w:br/>'.htmlspecialchars($posting_organization).'<w:br/>'.
                    $posting_date;

                    // $officer_training = 'test';
                    // $rank_promotion = 'test';
                    // $posting_data = 'test';

                    $table->addCell(700,$styleCell)->addText($p_no,$fontStyle);
                    $table->addCell(700,$styleCell)->addText($s_no,$fontStyle);
                    $table->addCell(3200,$styleCell)->addText($officer_training,$fontStyle);
                    $table->addCell(1500,$styleCell)->addText($rank_promotion,$fontStyle);
                    $table->addCell(3200,$styleCell)->addText($posting_data,$fontStyle);

                }//end of foreach

            }//end of if

        }//end of foreach

        $objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, 'Word2007');
        $fileName = 'Posting_List_'.uniqid().'.docx';
        try {
            $objectWriter->save(storage_path($fileName));
        } catch (Exception $e) {

        }

        return response()->download(storage_path($fileName));

    }//end of function

    public function downloadExcelReports(Request $request){

        ini_set('max_execution_time', -1);
        $group_service_id = $grade_id = null;
        $grade_ids = array();
        $fontSize = 9.5;

        if($request->group_service_id){
            $group_service_id = $request->group_service_id;
        }

        if($request->grade_id){
            $grade_id = $request->grade_id;
            $grade_id = explode(',', $grade_id);
        }else{
            $grade_id = [22,21,20,19,18,17];
        }

        // $grade_ids = $grade_id ? array(array('id'=>$grade_id)) : Grade::where('id','>',16)->orderBy('id','DESC')->get(['id'])->toArray();
        $grade_ids = $grade_id;

        $officerUs = Officer_Profile::with(['group_service','current_post',
        'current_post.post','current_post.grade','contact'])
        ->where('group_service_id',$group_service_id)
        ->whereIn('current_grade_id',$grade_id)
        ->whereHas('current_post',function($query){
            $query->where('is_active',1);
        })
        ->orderBy('seniority','DESC')->orderBy('seniority_code', 'DESC')
        ->get();

        return (new PostingOfficerExport($officerUs))->download('Officers.xlsx');
        Excel::store(new PostingOfficerExport($officerUs), $path);

    }//end of function
}
