<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;

class ConciergeController extends Controller
{
//    public function calculate(Request $request)
//    {
//        $request->validate([
//            'activity' => 'required|string', // e.g. "5 min walk"
//            'weight'   => 'nullable|numeric', // optional, in kg
//            'age'      => 'nullable|numeric', // optional
//        ]);
//
//        $activity = $request->activity;
//        $weight   = $request->weight ?? 70; // default 70kg
//        $age      = $request->age ?? 25;
//
//        $prompt = "I did: {$activity}. My weight is {$weight}kg and age is {$age} years.
//        Please provide:
//        1. Calories burned
//        2. Distance covered (if applicable)
//        3. Health benefits
//        4. Tips to improve
//        Return response in JSON format.";
//
//        $response = OpenAI::chat()->create([
//            'model' => 'gpt-3.5-turbo',
//            'messages' => [
//                ['role' => 'system', 'content' => 'You are a fitness and nutrition expert. Always respond in valid JSON.'],
//                ['role' => 'user', 'content' => $prompt],
//            ],
//            'temperature' => 0.7,
//        ]);
//
//        $result = $response->choices[0]->message->content;
//
//        return response()->json([
//            'success' => true,
//            'data'    => json_decode($result),
//        ]);
//    }
//    public function calculate(Request $request)
//    {
//        $request->validate([
//            'activity' => 'required|string',
//            'weight'   => 'nullable|numeric',
//            'age'      => 'nullable|numeric',
//        ]);
//
//        $activity = $request->activity;
//        $weight   = $request->weight ?? 70;
//        $age      = $request->age ?? 25;
//
//        $prompt = "I did: {$activity}. My weight is {$weight}kg and age is {$age} years.
//    Please provide:
//    1. Calories burned
//    2. Distance covered (if applicable)
//    3. Health benefits
//    4. Tips to improve
//    Return response in valid JSON format only, no extra text.";
//
//        try {
//            $response = OpenAI::chat()->create([
//                'model' => 'gpt-5-nano',
//                'messages' => [
//                    ['role' => 'system', 'content' => 'You are a fitness and nutrition expert. Always respond in valid JSON only.'],
//                    ['role' => 'user', 'content' => $prompt],
//                ],
//            ]);
//
//            $result = $response->choices[0]->message->content;
//
//            return response()->json([
//                'success' => true,
//                'data'    => json_decode($result),
//            ]);
//
//        } catch (\OpenAI\Exceptions\TransporterException $e) {
//            return response()->json(['success' => false, 'message' => 'Connection error: ' . $e->getMessage()], 500);
//
//        } catch (\OpenAI\Exceptions\ErrorException $e) {
//            // Rate limit or API error
//            return response()->json(['success' => false, 'message' => $e->getMessage()], 429);
//
//        } catch (\Exception $e) {
//            return response()->json([
//                'success' => false,
//                'message' => $e->getMessage(),
//                'line'    => $e->getLine(),
//                'file'    => $e->getFile(),
//            ], 500);
//        }
//    }


//    public function recommendPlaces(Request $request)
//    {
//        $request->validate([
//            'experience_type' => 'required|string',
//            'budget' => 'required|string',
//            'type' => 'nullable|string',
//            'details' => 'nullable|string',
//        ]);
//
//        $prompt = "
//Suggest 5 best places in Dubai.
//
//User Preferences:
//Experience Type: {$request->experience_type}
//Budget: {$request->budget}
//Occasion Type: {$request->type}
//Additional Details: {$request->details}
//
//Return ONLY valid JSON in this format:
//
//{
//  \"recommendations\": [
//    {
//      \"name\": \"\",
//      \"category\": \"\",
//      \"location\": \"\",
//      \"estimated_budget_aed\": \"\",
//      \"description\": \"\",
//      \"why_recommended\": \"\",
//      \"rating_out_of_5\": \"\"
//    }
//  ]
//}
//
//Do not add any explanation. JSON only.
//";
//
//        try {
//
//            $response = OpenAI::chat()->create([
//                'model' => 'gpt-5-nano',
//                'messages' => [
//                    [
//                        'role' => 'system',
//                        'content' => 'You are a Dubai travel and luxury experience recommendation expert.'
//                    ],
//                    [
//                        'role' => 'user',
//                        'content' => $prompt
//                    ]
//                ],
//                'response_format' => [
//                    'type' => 'json_object'
//                ]
//            ]);
//
//            $result = $response->choices[0]->message->content;
//
//            return response()->json([
//                'success' => true,
//                'data' => json_decode($result, true)
//            ]);
//
//        } catch (\Exception $e) {
//
//            return response()->json([
//                'success' => false,
//                'message' => $e->getMessage()
//            ], 500);
//        }
//    }

    public function recommendPlaces(Request $request)
    {
        $request->validate([
            'experience_type' => 'required|string',
            'budget' => 'required|string',
            'type' => 'nullable|string',
            'details' => 'nullable|string',
        ]);

        $prompt = "
Suggest 5 best places in Dubai.

User Preferences:
Experience Type: {$request->experience_type}
Budget: {$request->budget}
Occasion Type: {$request->type}
Additional Details: {$request->details}

Return ONLY valid JSON in this format:

{
  \"recommendations\": [
    {
      \"name\": \"\",
      \"category\": \"\",
      \"location\": \"\",
      \"estimated_budget_aed\": \"\",
      \"description\": \"\",
      \"why_recommended\": \"\",
      \"rating_out_of_5\": \"\"
    }
  ]
}

Do not add any explanation. JSON only.
";

        try {

            $response = \OpenAI::factory()
                ->withApiKey(config('openai.api_key'))
                ->withHttpClient(new \GuzzleHttp\Client([
                    'timeout'         => 60,
                    'connect_timeout' => 10,
                ]))
                ->make()
                ->chat()
                ->create([
                    'model' => 'gpt-5-nano',
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => 'You are a Dubai travel and luxury experience recommendation expert.'
                        ],
                        [
                            'role'    => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'response_format' => ['type' => 'json_object']
                ]);

            $result = $response->choices[0]->message->content;

            return response()->json([
                'success' => true,
                'data'    => json_decode($result, true)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
