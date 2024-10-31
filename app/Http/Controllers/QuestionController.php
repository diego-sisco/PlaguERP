<?php

namespace App\Http\Controllers;

use App\Models\ControlPoint;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\ControlPointQuestion;
use App\Models\ProductCatalog;
use App\Models\PestCategory;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function create(string $pointID, string $section)
    {
        $question_options = QuestionOption::all();
        $quests = Question::all();
        $pest_category = PestCategory::all();
       
        return view('product.control_point.question.create', compact('pest_category','question_options','quests', 'pointID', 'section'));
    }
    public function store(Request $request, string $pointID, string $section){ 
        //dd($request); 
        $error = null;
        $success = null;
        $warning = null;
        $productid = session()->get('product_id');

        if ($request->filled('ask') && $request->filled('selected_option')) {
            $newquestion = new Question();
            $newquestion->question = $request['ask'];
            $newquestion->question_option_id = $request['selected_option'];
            if($request['category_select'] == 0){
                $newquestion->pest_category_id = null;
            }else{
                $newquestion->pest_category_id = $request['category_select'];
            }
            
            $newquestion->save();
            $newcontrolquestion = new ControlPointQuestion();
            $newcontrolquestion->question_id = $newquestion->id;
            $newcontrolquestion->control_point_id = $pointID;
            $newcontrolquestion->save();
        } else {
            $newcontrolquestion = new ControlPointQuestion();
            $newcontrolquestion->question_id = $request['selected_ask'];
            $newcontrolquestion->control_point_id = $pointID;
            $newcontrolquestion->save();
        }
        $quests = Question::all();
        $question_options = QuestionOption::all();
        $products = ProductCatalog::where('presentation_id', 1)->get();
        $products_included = ProductCatalog::where('presentation_id','!=', 1)->get();
        $point = ControlPoint::find($productid);
        $control_point_questions = ControlPointQuestion::where('control_point_id',$productid)->get();
        $success = 'pregunta creada correctamente';

        return redirect()->back();
    }

    public function set(Request $request, string $pointID)
    {
        if ($request->has('selected_question')) {
            $selectedQuestions = json_decode($request->input('selected_question'));
            
            if (!is_array($selectedQuestions)) {
                abort(400, 'Invalid JSON provided');
            }

            $questions = ControlPointQuestion::where('control_point_id', $pointID)->pluck('question_id')->toArray();

            if (count($selectedQuestions) != count($questions)) {
                $arr_diff1 = array_diff($selectedQuestions, $questions);
                $arr_diff2 = array_diff($questions, $selectedQuestions);

                foreach ($arr_diff2 as $qID) {
                    ControlPointQuestion::where('question_id', $qID)
                        ->where('control_point_id', $pointID)
                        ->delete();
                }

                foreach ($arr_diff1 as $qID) {
                    ControlPointQuestion::insert([
                        'question_id' => $qID,
                        'control_point_id' => $pointID,
                        'created_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->back();
    }


    public function edit($id)
    {
        $question = Question::find($id);
        $question_options = QuestionOption::all();
        $pest_category = PestCategory::all();
       
        return view('product.control_point.question.edit', compact('pest_category','question_options','question'));
    }
    public function update(Request $request, $id){
        $error = null;
        $success = null;
        $warning = null;
        $question = Question::find($id);
        $question->question = $request['ask'];
        $question->question_option_id = $request['selected_option'];
        $question->pest_category_id = $request['category_select'];
        $question->save();

        $productid = session()->get('product_id');
        $control_point_questions = ControlPointQuestion::where('control_point_id',$productid)->get();
        $question_options = QuestionOption::all();
        $quests = Question::all();
        $point = ControlPoint::find($pointID);
        $products = ProductCatalog::where('presentation_id', 1)->get();
        $products_included = ProductCatalog::where('presentation_id','!=', 1)->get();
        return view('product.control_point.edit', compact('question_options','quests','productid','control_point_questions','point','products','products_included','error','success','warning'));
    }
    
    public function destroy($id)
    {
        $question = ControlPointQuestion::find($id);
        $idprod = $question->control_point_id;
        $question->delete();
        return redirect()->route('point.edit', ['product' => $idprod]);
    }
}
