<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * The AnswerController handles the logic for creating, updating and deleting answers.
 *
 * It is called via the API from JavaScript, therefore answers JSON only.
 * 
 * @package App\Http\Controllers
 */
class AnswerController extends Controller
{
	/**
	 * Displays the list of existing answers.
	 * 
	 * @param Request $request
	 * 
	 * @return Response
	 */
	public function index(Request $request)
	{
		# Check if an optional user is logged-in.
		if ($request->bearerToken()) {
			Auth::setUser(
				Auth::guard('sanctum')->user()
			);
		}
		
		if (Auth::check())
		{
			$answers = Answer::latest()->where('status', '=', 'blocked')->paginate(10)->toArray();
		}
		else
		{
			$answers = Answer::latest()->where('status', '=', 'published')->paginate(10)->toArray();
		}
		
		return response()->json($answers);
	}
	
	/**
	 * Create a new answer.
	 * 
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(Request $request)
	{
		$request->validate([
			'nickname' => 'required',
			'answer' => 'required'
		]);
		
		if (!empty($request->input('telephone')))
		{
			return abort(500, 'Bitte das Spamschutz-Feld nicht ausfüllen.');
		}
		
		Answer::create([
			'nickname' => $request->input('nickname'),
			'answer'   => $request->input('answer')
		]);
		
		return response()->json('Die Antwort wurde gespeichert!');
	}
	
	/**
	 * Blocks an existing answer.
	 *
	 * @param Answer $answer The answer to block.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function block(Answer $answer)
	{
		$answer->update(['status' => 'blocked']);
		
		return response()->json('Die Antwort wurde geblockt!');
	}
	
	/**
	 * Releases an existing, blocked answer.
	 *
	 * @param Answer $answer The answer to block.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function publish(Answer $answer)
	{
		$answer->update(['status' => 'published']);
		
		return response()->json('Die Antwort wurde freigegeben!');
	}
}
